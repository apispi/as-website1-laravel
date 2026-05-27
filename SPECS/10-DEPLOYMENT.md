# Deployment & Production Specification

## Production Environment Overview

### Hosting Information
- **Provider**: SiteGround shared hosting
- **Server Path**: `~/www/apispi.com`
- **Web Root**: `public_html/` (NOT `public/`)
- **PHP Version**: 8.2+
- **Node.js**: NOT available on server
- **Deployment Method**: Git-based (pull-to-deploy)

### Key Constraints
1. **No Node.js** → Must build frontend locally, commit built assets to git
2. **No npm** → No `npm install` on server
3. **Git deployment only** → No FTP uploads
4. **Database migrations** → Auto-applied via git pull hooks (manual: `php artisan migrate`)

---

## Deployment Checklist

### Pre-Deployment (Local Machine)

```bash
# 1. Ensure working directory is clean
git status
# (Commit or stash any uncommitted changes)

# 2. Pull latest from remote
git pull origin main

# 3. Test locally (recommended)
composer test
php artisan serve           # Verify in browser
npm run dev                 # Verify frontend builds

# 4. Install production dependencies
composer install --no-dev   # Production PHP dependencies
npm install                 # Install Node deps (dev + prod)

# 5. Build frontend assets
npm run build
# Generates: public_html/build/*

# 6. Verify build output
git status                  # Check public_html/build/ changes
ls -la public_html/build/   # Verify files exist

# 7. Run final tests
composer test
php artisan build
```

### Deployment Steps (5 Steps)

**Step 1: Build Frontend Assets Locally**
```bash
npm run build
# Output: public_html/build/{app.js,app.css,admin.js,...}
```

**Step 2: Commit Built Assets**
```bash
git add public_html/build/
git commit -m "Build frontend assets for v1.2.0"
# (Include version tag in commit message for tracking)
```

**Step 3: Create Git Tag (Optional)**
```bash
git tag -a v1.2.0 -m "Release version 1.2.0"
```

**Step 4: Push to Repository**
```bash
git push origin main
git push origin v1.2.0      # Push tag if created
```

**Step 5: Deploy to Server (SSH)**
```bash
ssh user@apispi.com
cd ~/www/apispi.com

# Pull latest code (including built assets)
git pull origin main

# Run pending migrations (if any)
php artisan migrate

# Clear application caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Restart queue workers (if any)
# php artisan queue:restart

# Optional: seed data (usually not needed in production)
# php artisan db:seed
```

### Post-Deployment Verification

```bash
# Verify assets are served
curl https://apispi.com/build/app.js | head -20

# Check application logs
tail -50 ~/www/apispi.com/storage/logs/laravel.log

# Verify database
php artisan migrate:status

# Test critical functionality
# 1. Login page loads
# 2. Create account works
# 3. Dashboard accessible
# 4. Agents display correctly
```

---

## Rollback Procedure

### Quick Rollback (Git-based)
```bash
# On local machine
git revert <commit-hash>    # Creates new "revert" commit
git push origin main

# On server
git pull origin main
php artisan migrate:rollback  # If needed (run only if migrations added)
```

### Safe Rollback (Tag-based)
```bash
# On local machine
git checkout v1.1.0         # Go to previous tag
git push origin main --force  # Force push (use with caution!)

# On server
git pull origin main
php artisan migrate:rollback  # If needed
```

### Database Rollback
```bash
# On server
php artisan migrate:rollback --step=2  # Rollback last 2 batches
# Or rollback specific migration
php artisan migrate:rollback --path=database/migrations/migration_name.php
```

---

## Production Environment Configuration

### .env File (Server)
Create/update `~/www/apispi.com/.env`:

```ini
# Application
APP_NAME="APISPI"
APP_ENV=production
APP_DEBUG=false                 # NEVER true in production!
APP_URL=https://apispi.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost               # Usually localhost on shared hosting
DB_PORT=3306
DB_DATABASE=apispi_prod         # Production database name
DB_USERNAME=apispi_user         # Production DB user
DB_PASSWORD=VERY_STRONG_PASSWORD

# Cache
CACHE_DRIVER=file               # 'file', 'redis', or 'memcached'
CACHE_STORE=file

# Session (REQUIRED)
SESSION_DRIVER=database         # Must be 'database' for shared hosting
SESSION_LIFETIME=120            # 2 hours
SESSION_SECURE_COOKIES=true     # HTTPS only
SESSION_HTTP_ONLY=true          # No JS access
SESSION_SAME_SITE=lax           # CSRF protection

# Queue
QUEUE_CONNECTION=sync           # Use 'sync' (process immediately) on shared hosting
# Or 'database' if async jobs needed

# AI Chatbot
ANTHROPIC_API_KEY=sk-ant-...    # From Anthropic account
ANTHROPIC_MODEL=claude-sonnet-4-5

# Email
MAIL_MAILER=smtp
MAIL_HOST=mail.apispi.com       # Your mail server
MAIL_PORT=587
MAIL_USERNAME=noreply@apispi.com
MAIL_PASSWORD=MAIL_PASSWORD
MAIL_FROM_ADDRESS=noreply@apispi.com
MAIL_FROM_NAME="APISPI"

# Security
APP_KEY=base64:XXXXX            # Copy from local .env
```

**⚠️ IMPORTANT**: Never commit `.env` to git (add to `.gitignore`)

### Web Server Configuration

#### Apache (SiteGround .htaccess)
File: `public_html/.htaccess`
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

    # Cache-busting for assets
    <FilesMatch "\.(js|css|jpg|jpeg|png|gif|ico|woff|woff2)$">
        Header set Cache-Control "max-age=31536000, public"
    </FilesMatch>
</IfModule>
```

#### Nginx (if available)
```nginx
server {
    listen 443 ssl http2;
    server_name apispi.com www.apispi.com;

    root /home/user/www/apispi.com/public_html;
    index index.php;

    # SSL Certificates
    ssl_certificate /etc/ssl/certs/apispi.com.crt;
    ssl_certificate_key /etc/ssl/private/apispi.com.key;

    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "DENY" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Block access to sensitive files
    location ~ /\.env {
        deny all;
    }
    location ~ /\.git {
        deny all;
    }
}
```

---

## Database Management

### Production Database Backups
**On server**, automate daily backups:
```bash
# Manual backup
mysqldump -u apispi_user -p apispi_prod > ~/backups/apispi_prod_$(date +%Y%m%d).sql

# Or use SiteGround backup system (through cPanel)
```

### Running Migrations

```bash
# On server
cd ~/www/apispi.com

# See migration status
php artisan migrate:status

# Run pending migrations
php artisan migrate

# Preview SQL without executing
php artisan migrate --pretend

# Rollback last batch
php artisan migrate:rollback

# Rollback specific number of steps
php artisan migrate:rollback --step=2
```

### Zero-Downtime Migrations
For critical tables:
1. Create new column (nullable)
2. Backfill data in separate migration
3. Update application code
4. Drop old column (in later migration)

Example:
```php
// Migration 1: Add new column
Schema::table('users', function (Blueprint $table) {
    $table->string('new_email')->nullable();
});

// Migration 2: Backfill data
DB::statement('UPDATE users SET new_email = email');

// Migration 3: Drop old column (after code updated)
Schema::table('users', function (Blueprint $table) {
    $table->dropColumn('email');
    $table->renameColumn('new_email', 'email');
});
```

---

## Monitoring & Maintenance

### Application Logs
```bash
# View application log
tail -100 ~/www/apispi.com/storage/logs/laravel.log

# Clear old logs (if disk space needed)
php artisan logs:clear

# Watch logs in real-time
tail -f ~/www/apispi.com/storage/logs/laravel.log
```

### Performance Optimization
```bash
# Cache optimization (after deployment)
php artisan config:cache      # Cache config files
php artisan route:cache       # Cache routes
php artisan view:cache        # Cache compiled views

# Clear before next deployment
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Scheduled Tasks
Laravel scheduler (if enabled):
```bash
# Add to server crontab
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

### Disk Space Management
```bash
# Check disk usage
df -h
du -sh ~/www/apispi.com

# Clear temporary files
php artisan cache:clear
rm -rf ~/www/apispi.com/storage/framework/cache/*
rm -rf ~/www/apispi.com/storage/logs/laravel-*.log
```

---

## Security Checklist

- ✅ `.env` file permissions: `600` (not readable by web)
- ✅ `storage/` directory permissions: `775` (writable by web server)
- ✅ `bootstrap/cache/` permissions: `775`
- ✅ `APP_DEBUG=false` in production
- ✅ HTTPS enabled (SSL certificate)
- ✅ `SESSION_SECURE_COOKIES=true`
- ✅ Strong database passwords
- ✅ Regular backups automated
- ✅ Security headers configured (nginx/apache)
- ✅ `.env` and `.git/` directories blocked from web access

---

## Continuous Integration/Deployment (Optional)

### GitHub Actions Example
```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Build frontend
        run: |
          npm install
          npm run build
      
      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd ~/www/apispi.com
            git pull origin main
            php artisan migrate
            php artisan cache:clear
```

---

## Troubleshooting Production Issues

| Issue | Solution |
|-------|----------|
| 500 error after deploy | Check `storage/logs/laravel.log`, verify migrations ran |
| Assets not loading (404) | Verify `npm run build` completed, check `public_html/build/` exists |
| Database connection error | Check `.env` credentials, ensure MySQL is running |
| Session errors | Verify `sessions` table exists, run `php artisan migrate` |
| Permission denied errors | Check file permissions on `storage/` and `bootstrap/cache/` |
| Email not sending | Check `MAIL_*` environment variables, verify SMTP access |
}

public function down(): void
{
    Schema::table('agents', function (Blueprint $table) {
        $table->dropColumn('new_field');
    });
}
```

---

## Monitoring & Maintenance

### Application Logs
- **Location**: `storage/logs/`
- **Format**: Daily log files (configurable)
- **Tail logs**: `tail -f storage/logs/laravel.log`

### Database Maintenance
```bash
# Optimize tables
php artisan db:optimize

# Check for missing indexes
php artisan query:cache-tables

# Prune old activity logs (monthly)
php artisan tinker
  > ActivityLog::where('created_at', '<', now()->subMonths(3))->delete()
```

### Cache Warming
```bash
# Pre-compile views
php artisan view:cache

# Cache configuration
php artisan config:cache
```

### Session Cleanup
Laravel automatically cleans expired sessions. Force cleanup:
```bash
php artisan session:prune-stale-sessions
```

---

## Backup & Recovery

### Database Backup
```bash
# Backup via mysqldump
mysqldump -u user -p database > backup.sql

# Restore from backup
mysql -u user -p database < backup.sql

# Via Laravel (if backup package installed)
php artisan backup:run
```

### File Backups
- Version control (git) handles code backups
- Store uploaded files in cloud storage (S3, etc.) if used
- Keep weekly snapshots of `storage/` directory

---

## Performance Optimization

### Caching
```php
// Cache expensive queries
$agents = cache()->remember('agents:active', 3600, function () {
    return Agent::active()->get();
});

// Clear when data changes
cache()->forget('agents:active');
```

### Database Optimization
```bash
# Add indexes via migration
Schema::table('subscriptions', function (Blueprint $table) {
    $table->index(['user_id', 'agent_id']);
});
```

### Frontend Asset Optimization
- Vite automatically minifies & chunks assets
- Browser caching via `Cache-Control` headers
- Gzip compression on web server

### Query Optimization
```php
// Eager load relationships
Agent::with(['skills', 'connectors'])->get();

// Avoid N+1 queries
foreach (agents as $agent) {
    // Skills loaded separately per agent (bad)
    // $agent->skills;  ← DO NOT DO THIS
}

// Instead:
$agents = Agent::with('skills')->get();
foreach ($agents as $agent) {
    // Skills already loaded
}
```

---

## Security Best Practices

### Environment Variables
- Never commit `.env` file
- Store secrets in server environment
- Rotate `APP_KEY` carefully (invalidates sessions)

### Password Hashing
- Laravel defaults to bcrypt (configurable to argon2id)
- Never log passwords
- Passwords auto-hashed via Eloquent cast

### CSRF Protection
- Automatically applied via middleware
- Token included in all forms via `@csrf`

### SQL Injection Prevention
- Use query builder or Eloquent (parameterized queries)
- Never concatenate user input into SQL

### XSS Protection
- Blade auto-escapes output: `{{ $variable }}`
- Use `{!! $html !!}` only for trusted HTML

### Rate Limiting
- `/chat` endpoint: 30 requests per minute per IP
- Configured via middleware: `throttle:30,1`

### SSL/TLS
- Use HTTPS in production
- Redirect HTTP to HTTPS
- Update `APP_URL` to `https://`

---

## Disaster Recovery

### Recovery Steps (Database Corruption)
1. Stop application (`php artisan down`)
2. Restore database from backup
3. Clear caches (`php artisan cache:clear`)
4. Bring application up (`php artisan up`)

### Recovery Steps (Code Issue)
1. Identify problematic commit: `git log --oneline`
2. Revert: `git revert <commit-hash>`
3. Push and deploy

### Recovery Steps (Asset Corruption)
1. Delete `public_html/build/`
2. Revert problematic commits
3. Rebuild assets: `npm run build`
4. Commit and push

---

## Monitoring Checklist

- [ ] Application error logs monitored daily
- [ ] Database backups automated and tested
- [ ] SSL certificate expiration tracked
- [ ] Disk space monitored (logs grow over time)
- [ ] Database query performance monitored
- [ ] API rate limits respected
- [ ] Session cleanup scheduled
- [ ] Logs rotated (daily, weekly, etc.)
