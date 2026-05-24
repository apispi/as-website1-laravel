# Project Overview

## Summary
A Laravel 11 web application for managing AI agents, skills, connectors, and user subscriptions. Features public agent catalog, user dashboard, admin management, OAuth connector integration, and an AI chatbot.

## Tech Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue 3 (via Vite), Tailwind CSS
- **Database**: MySQL/MariaDB
- **Build Tool**: Vite
- **Auth**: Laravel's built-in authentication with custom `is_admin` flag
- **Session**: Database-backed sessions

## Core Domains

### 1. Agents
- Marketplace products representing AI agents
- Have skills, connectors, trainings, and subscriptions
- Support rich content: features, use_cases, pricing_plans, faqs
- Public catalog browsing and detailed views

### 2. Skills
- Capabilities/features that agents possess
- Many-to-many relationship with agents
- Categorized and sortable

### 3. Subscriptions
- Users subscribe to agents
- Track status, start date, expiration
- Drive user dashboard content

### 4. Connectors
- OAuth and API integrations (Slack, Zapier, etc.)
- Many-to-many with agents
- Support dynamic config schemas
- User can connect/disconnect via OAuth flow

### 5. Users
- Authentication: login, register, password reset
- Admin flag for access control
- Subscriptions to agents
- Connector integrations

### 6. Admin Panel
- User-locked by `auth` + `admin` middleware
- Manage agents, skills, connectors, subscriptions, trainings, users
- Activity logging of user actions
- Red/rose color scheme

### 7. AI Chatbot (Aria)
- On `/contact` page
- Proxies to Anthropic API
- Maintains 10-message conversation history
- Rate-limited by IP (30 requests/min)
- In-browser intent classification with node-nlp

## Hosting
**Production**: SiteGround shared hosting (`~/www/apispi.com`)
- No Node.js on server
- Must commit built assets to git
- Web root: `public_html/` (not `public/`)
- Auto-deployed on `git pull`

## Key Files Structure
```
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Namespaced by Admin/
│   │   └── Middleware/
│   ├── Models/                   # User, Agent, Skill, etc.
│   ├── Services/
│   │   └── OAuthService.php
│   └── Providers/
├── resources/
│   ├── js/                        # Vue components & entry points
│   ├── views/                     # Blade templates
│   └── css/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── public_html/                   # Web root (not public/)
│   ├── index.php
│   ├── build/                     # Vite build output
│   └── js/
│       ├── chatbot.js             # Static file (not Vite-compiled)
│       └── nlp.min.js             # node-nlp v3.10.2 browser bundle
├── routes/
│   └── web.php
└── tests/
```

## Deployment Workflow
```bash
npm run build                      # Build frontend assets
git add public_html/build/
git commit -m "..."
git push
# Then on server:
cd ~/www/apispi.com && git pull
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
