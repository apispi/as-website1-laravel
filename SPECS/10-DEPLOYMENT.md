# Deployment & Production Specification

## Production Environment

### Hosting
- **Provider**: SiteGround shared hosting
- **Path**: `~/www/apispi.com`
- **Web Root**: `public_html/` (NOT `public/`)
- **Node.js**: NOT available on server
- **PHP Version**: 8.2+

### Key Constraints
- No Node.js on server → Built assets MUST be committed to git
- Git deployments only (no FTP file uploads)
- Database mutations via migrations (auto-applied on pull)

---

## Deployment Workflow

### Pre-deployment Checklist
```bash
# 1. Ensure all changes committed
git status

# 2. Update dependencies
composer update --no-dev    # Production composer.lock
npm install --production    # Prune dev dependencies

# 3. Run tests
composer test
npm run build               # Ensure frontend builds
```

### Deployment Steps

**Step 1: Build frontend locally**
```bash
npm run build
# Outputs to public_html/build/
```

**Step 2: Commit built assets**
```bash
git add public_html/build/
git commit -m "Build assets for v1.2.0"
```

**Step 3: Push to repository**
```bash
git push origin main
```

**Step 4: Deploy to server (via SSH)**
```bash
ssh user@apispi.com
cd ~/www/apispi.com
git pull origin main

# Run pending migrations (if any)
php artisan migrate

# Clear application caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optional: Run seeders
# php artisan db:seed
```

### Rollback Procedure
```bash
git revert <commit-hash>    # Creates new commit
git push
# Then on server:
git pull
php artisan migrate:rollback  # Rollback last migration (if needed)
```

---

## Configuration for Production

### Environment Variables
```
# .env on server
APP_NAME="APISPI"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://apispi.com

DB_CONNECTION=mysql
DB_HOST=localhost           # Or VPS IP
DB_DATABASE=apispi_prod
DB_USERNAME=apispi_user
DB_PASSWORD=<strong-password>

ANTHROPIC_API_KEY=sk-...
ANTHROPIC_MODEL=claude-sonnet-4-5

SESSION_DRIVER=database
CACHE_DRIVER=file           # or redis if available
QUEUE_CONNECTION=sync       # or database if async jobs needed

MAIL_MAILER=smtp
MAIL_HOST=mail.apispi.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@apispi.com
MAIL_FROM_NAME="APISPI"

# Security
SECURE_HEADERS_ENABLED=true
SESSION_SECURE_COOKIES=true
SESSION_HTTP_ONLY=true
```

### Web Server Configuration

#### Apache (.htaccess)
```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

#### Nginx
```nginx
server {
    listen 443 ssl http2;
    server_name apispi.com www.apispi.com;

    root /home/user/www/apispi.com/public_html;
    index index.php;

    ssl_certificate /path/to/cert.crt;
    ssl_certificate_key /path/to/key.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

---

## Database Migrations

### Production Migration Safety
```bash
# Preview migration SQL before applying
php artisan migrate --pretend

# Apply specific migration
php artisan migrate --path=database/migrations/2026_05_20_100000_create_agents_table.php

# Roll back last batch
php artisan migrate:rollback

# Roll back to specific migration
php artisan migrate:rollback --step=3
```

### Zero-downtime Migrations
- Use `change()` column modifications carefully (may lock table)
- For renaming columns: create new → copy → drop old (separate migrations)
- For table renames: avoid during peak traffic

### Example Safe Migration
```php
public function up(): void
{
    // Safe: adding nullable column
    Schema::table('agents', function (Blueprint $table) {
        $table->string('new_field')->nullable();
    });
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
