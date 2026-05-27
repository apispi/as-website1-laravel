# Project Overview

## Summary
A Laravel 11 web application for managing AI agents, skills, connectors, and user subscriptions. Features public agent catalog, user dashboard, admin management, OAuth connector integration, and an AI chatbot (Aria) powered by Anthropic.

## Tech Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue 3 (via Vite), Tailwind CSS
- **Database**: MySQL 8.0+ / MariaDB 10.4+
- **Build Tool**: Vite
- **Authentication**: Laravel's built-in auth + custom `is_admin` flag
- **Session Storage**: Database-backed
- **AI Integration**: Anthropic API (claude-sonnet-4-5)

## Core Domains

### 1. Agents
- Marketplace products representing AI agents
- Possess skills and connectors (many-to-many relationships)
- Support rich JSON content: `features`, `includes`, `use_cases`, `pricing_plans`, `faqs`
- Pricing, ratings, categorization, and featured status
- Public catalog browsing and individual detail pages
- Tracking user subscription counts

### 2. Skills
- Capabilities/features that agents possess
- Many-to-many relationship with agents via pivot table
- Support rich descriptions, categories, sorting
- Active/inactive status

### 3. Subscriptions
- Users subscribe to agents
- Track status (`active`, etc.), start date, expiration
- Drive user dashboard content and agent access
- Link users → agents

### 4. Connectors
- OAuth 2.0 integrations (Slack, Zapier, etc.)
- Many-to-many relationship with agents
- Support dynamic config schemas (JSON)
- OAuth metadata: client ID, secret, auth/token URLs, scopes
- User can authenticate and disconnect via OAuth flow

### 5. Users
- Standard authentication: login, register, password reset
- Email-based with password hashing
- Admin flag (`is_admin` boolean) for access control
- Relationships: subscriptions, connector connections, activity logs

### 6. Admin Panel
- Protected by `auth` + `admin` middleware
- Manage agents, skills, connectors, subscriptions, users, trainings, activity logs
- User impersonation (activity actor tracking)
- Red/rose color scheme (vs. user dashboard's amber/gold)

### 7. AI Chatbot (Aria)
- Public endpoint: `/contact`
- Proxies user messages to Anthropic API
- Maintains conversation history (last 10 messages)
- Rate-limited: 30 requests/min per IP
- Static JS (`chatbot.js`) with in-browser NLP intent classification (node-nlp v3.10.2)

## Hosting & Deployment
**Production**: SiteGround shared hosting (`~/www/apispi.com`)
- No Node.js runtime on server → built assets MUST be committed to git
- Web root: `public_html/` (not `public/`)
- Git-based deployments only (pull-to-deploy workflow)
- Migrations auto-applied on push

## Key File Structure
```
apispi/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Namespaced: Admin/, public controllers
│   │   ├── Middleware/           # IsAdmin middleware
│   │   └── Resources/            # JSON API responses
│   ├── Models/                   # User, Agent, Skill, Subscription, etc.
│   ├── Services/                 # OAuthService
│   └── Providers/
├── resources/
│   ├── js/                        # Vue entry points & components
│   ├── views/                     # Blade templates
│   └── css/
├── database/
│   ├── migrations/                # Schema changes
│   ├── seeders/                   # Sample data
│   └── factories/                 # Test data
├── public_html/                   # Web root (SiteGround)
│   ├── index.php
│   ├── build/                     # Vite output (committed to git)
│   ├── css/
│   ├── js/
│   │   ├── chatbot.js             # Static, not Vite-compiled
│   │   └── nlp.min.js             # node-nlp browser bundle
│   ├── robots.txt
│   └── ...
├── routes/
│   ├── web.php                    # All routes
│   └── console.php
├── config/
├── tests/
├── storage/
└── vendor/
```

## Development Workflow
```bash
# Setup
composer install && npm install && npm run build

# Dev servers (run in separate terminals)
php artisan serve                  # http://localhost:8000
npm run dev                         # HMR for frontend

# Before commit
npm run build && ./vendor/bin/pint && composer test
```

## Deployment Workflow (Production)
```bash
npm run build                       # Build frontend assets locally
git add public_html/build/
git commit -m "Build frontend assets"
git push origin main
# Then on server (SiteGround):
cd ~/www/apispi.com && git pull && php artisan migrate
```
php artisan migrate               # if needed
```

## Environment Variables
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apispi
DB_USERNAME=user
DB_PASSWORD=pass

ANTHROPIC_API_KEY=sk-...
ANTHROPIC_MODEL=claude-sonnet-4-5

SESSION_DRIVER=database           # REQUIRED
```
