# ApiSpi — AI Agents Platform

ApiSpi is a production Laravel application powering [apispi.com](https://apispi.com) — an AI agents marketplace where users discover, subscribe to, and manage AI agents and their integrations.

## Stack

| Layer | Tech |
|---|---|
| Backend | Laravel 12 · PHP 8.2+ |
| Frontend | Vue 3 · Vite 8 |
| Hosting | SiteGround shared hosting (no Node.js on server) |
| AI | Anthropic Claude API (chatbot + agents) |
| Auth | Laravel session auth (database driver) |

## Core Domain

### Agents
Production-ready AI agents available in the marketplace. Each agent has a catalog entry (name, description, pricing plans, skills, use cases, FAQs) and users subscribe to them.

### Skills Catalog
Discrete capabilities an agent can perform (e.g. Document Analysis, Contract Review). Many-to-many relationship with agents via `agent_skill` pivot.

### Connector Catalog
External service integrations (Slack, Gmail, Salesforce, GitHub, etc.) that agents can connect to. Connectors store their own OAuth 2.0 configuration (auth URL, token URL, scopes, client credentials) directly in the database — adding a new OAuth connector requires no code changes, only an admin form entry.

### User Connectors
An instance of a connector for a specific user. Created automatically when a user completes an OAuth flow, or manually by an admin. Tracks `status` (active/disconnected) and timestamps.

### Connector Tokens
OAuth access and refresh tokens stored per user per connector. Access tokens are encrypted at rest. The `OAuthService` handles the full authorization code flow and auto-refreshes expired tokens.

### Subscriptions
A user's active instance of an agent. Assigned by admins or through checkout. Tracks status (active/cancelled/expired) and expiry dates.

### Trainings
A training catalog for agents — courses, modules, or content that supports agent capability development.

## Admin Panel (`/admin`)

Protected by `auth` + `IsAdmin` middleware. Red/rose colour palette.

| Section | URL |
|---|---|
| Dashboard | `/admin` |
| Users | `/admin/users` |
| Active Agents | `/admin/subscriptions` |
| Agent Catalog | `/admin/agents` |
| Skills Catalog | `/admin/skills` |
| Training Catalog | `/admin/trainings` |
| User Connectors | `/admin/user-connectors` |
| Connector Catalog | `/admin/connectors` |
| Activity Log | `/admin/activity` |

Per-user management pages exist for agents (`/admin/users/{id}/agents`) and connectors (`/admin/users/{id}/connectors`).

## User Dashboard (`/dashboard`)

Authenticated users see their subscribed agents, can browse the agent catalog, manage their profile, and connect external integrations via OAuth.

## AI Chatbot (Aria)

Available on `/contact`. Proxies messages to the Anthropic API via Laravel's `Http` facade — no SDK. Maintains a 10-message conversation history, rate-limits by IP, and uses `claude-sonnet-4-5` by default.

## Frontend Architecture

Each page section has its own Vite entry point:

| Entry | Mount ID | Purpose |
|---|---|---|
| `admin.js` | `#admin-app` | All admin pages, routed via `data-page` attribute |
| `dashboard.js` | `#dashboard-app` | User dashboard overview |
| `agent-detail.js` | `#agent-detail-app` | Individual agent detail (user-facing) |
| `catalog.js` | `#catalog-app` | Agent catalog browsing |
| `agents-list.js` | `#agents-list-app` | Public agents marketplace |
| `profile.js` | `#profile-app` | User profile / password |

Props are passed from Blade views via `data-props` JSON on the mount element.

## Development

```bash
# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed          # agents, skills, connectors, trainings

# Dev server (PHP built-in)
php artisan serve

# Frontend watch
npm run dev

# Frontend build (required before deploy)
npm run build
```

## Deployment

Production runs on SiteGround shared hosting at `~/www/apispi.com`. There is **no Node.js on the server** — built assets must be committed to the repository.

```bash
# Local: build and commit assets
npm run build
git add public_html/build/
git commit -m "build assets"
git push

# Remote: pull and migrate
ssh user@host "cd ~/www/apispi.com && git pull && php artisan migrate"
```

The web root is `public_html/` (not `public/`). Vite outputs there via `publicDirectory: 'public_html'` in `vite.config.js`.

## Key Environment Variables

```env
ANTHROPIC_API_KEY=          # Powers the Aria chatbot on /contact
ANTHROPIC_MODEL=claude-sonnet-4-5
SESSION_DRIVER=database     # Required — sessions table must exist
APP_KEY=                    # Used to encrypt connector OAuth secrets
```

OAuth connector credentials (client ID, secret, endpoints) are stored in the `connectors` database table and managed via the admin UI — no per-connector environment variables needed.

## Data Model Overview

```
User
 ├── Subscription → Agent
 │    └── Agent ↔ Skill (many-to-many via agent_skill)
 ├── UserConnector → Connector
 └── ConnectorToken (connector_slug, access_token encrypted, refresh_token, expires_at)

Connector
 └── oauth_client_secret (encrypted), oauth_auth_url, oauth_token_url, oauth_scopes, oauth_extra_params
```
