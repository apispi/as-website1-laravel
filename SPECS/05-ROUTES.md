# Routes Specification

## Route Structure

All routes defined in `routes/web.php`.

---

## Public Routes (No Auth Required)

### Pages
```
GET  /                          → PageController@home
GET  /about                     → PageController@about
GET  /training                  → PageController@training
GET  /checkout                  → PageController@checkout
```

### Contact & Chat
```
GET  /contact                   → ContactController@show
POST /contact                   → ContactController@send
POST /chat                      → ChatController@send (throttle: 30/min per IP)
```

### Subscription
```
POST /subscribe                 → SubscribeController@store
```

### Agents (Catalog)
```
GET  /agents                    → AgentController@index
GET  /agents/{slug}             → AgentController@show
```

### Blog
```
GET  /blog                      → BlogController@index
GET  /blog/{slug}               → BlogController@show
```

---

## Authentication Routes (Guests Only)

Middleware: `guest` — redirects authenticated users to `/dashboard`

```
GET  /login                     → AuthController@showLogin
POST /login                     → AuthController@login

GET  /register                  → AuthController@showRegister
POST /register                  → AuthController@register

GET  /forgot-password           → AuthController@showForgotPassword
POST /forgot-password           → AuthController@sendResetLink

GET  /reset-password/{token}    → AuthController@showResetPassword
POST /reset-password            → AuthController@resetPassword
```

---

## Authenticated Routes

Middleware: `auth` — requires login

### Dashboard
```
GET  /dashboard                 → AuthController@dashboard
     Returns: Blade view 'dashboard.index' mounting Vue app to #dashboard-app

GET  /dashboard/catalog         → AuthController@catalog
     Browse agent marketplace

GET  /dashboard/agents          → AuthController@userAgents
     User's active subscriptions, mounts Vue app to #agents-list-app

GET  /dashboard/agents/{subscription}  → AuthController@userAgent
     Individual agent/subscription detail, mounts Vue app to #agent-detail-app

GET  /dashboard/connectors      → AuthController@userConnectors
     User's OAuth connector connections
```

### Profile
```
GET  /dashboard/profile         → AuthController@profile
     Mounts Vue app to #profile-app

PUT  /dashboard/profile         → AuthController@updateProfile
     Updates name, email

PUT  /dashboard/profile/password → AuthController@updatePassword
     Updates password (requires current password verification)
```

### Logout
```
POST /logout                    → AuthController@logout
```

### Connectors (OAuth)
```
GET  /connectors/{slug}/authorize    → ConnectorOAuthController@authorize
     Initiates OAuth flow, redirects to provider

GET  /connectors/{slug}/callback     → ConnectorOAuthController@callback
     OAuth callback, exchanges code for token

POST /connectors/{slug}/disconnect   → ConnectorOAuthController@disconnect
     Revokes connection
```

---

## Admin Routes

Middleware: `auth`, `admin` — requires login AND `users.is_admin = true`

Prefix: `/admin/` — Name prefix: `admin.`

### Dashboard
```
GET  /admin/                    → Admin\DashboardController@index
```

### Activity Logs
```
GET  /admin/activity            → Admin\ActivityLogController@index
```

### Users Management
```
GET  /admin/users               → Admin\UserController@index
GET  /admin/users/{user}        → Admin\UserController@show
```

### Agents Management
```
GET  /admin/agents              → Admin\AgentController@index
GET  /admin/agents/create       → Admin\AgentController@create
POST /admin/agents              → Admin\AgentController@store
GET  /admin/agents/{agent}/edit → Admin\AgentController@edit
PUT  /admin/agents/{agent}      → Admin\AgentController@update
DELETE /admin/agents/{agent}    → Admin\AgentController@destroy
```

### Skills Management
```
GET  /admin/skills              → Admin\SkillController@index
GET  /admin/skills/create       → Admin\SkillController@create
POST /admin/skills              → Admin\SkillController@store
GET  /admin/skills/{skill}/edit → Admin\SkillController@edit
PUT  /admin/skills/{skill}      → Admin\SkillController@update
DELETE /admin/skills/{skill}    → Admin\SkillController@destroy
```

### Connectors Management
```
GET  /admin/connectors          → Admin\ConnectorController@index
GET  /admin/connectors/create   → Admin\ConnectorController@create
POST /admin/connectors          → Admin\ConnectorController@store
GET  /admin/connectors/{connector}/edit → Admin\ConnectorController@edit
PUT  /admin/connectors/{connector}      → Admin\ConnectorController@update
DELETE /admin/connectors/{connector}    → Admin\ConnectorController@destroy
```

### Trainings Management
```
GET  /admin/trainings           → Admin\TrainingController@index
GET  /admin/trainings/create    → Admin\TrainingController@create
POST /admin/trainings           → Admin\TrainingController@store
GET  /admin/trainings/{training}/edit → Admin\TrainingController@edit
PUT  /admin/trainings/{training}      → Admin\TrainingController@update
DELETE /admin/trainings/{training}    → Admin\TrainingController@destroy
```

### Subscriptions Management
```
GET  /admin/subscriptions       → Admin\SubscriptionController@index
```

### User Connectors Management
```
GET  /admin/connectors/users    → Admin\UserConnectorController@index
```

---

## Route Naming Conventions

- Public routes: `{resource}.{action}` e.g. `agents.index`, `agents.show`
- Admin routes: `admin.{resource}.{action}` e.g. `admin.agents.index`
- Named routes accessible via `route('name')` in Blade and `route('name', params)` in code

---

## Middleware

### `guest`
- Redirects authenticated users (via `\App\Http\Middleware\Authenticate`)
- Used on auth routes to prevent logged-in users from seeing login form

### `auth`
- Requires authentication
- Redirects to `/login` if not authenticated

### `admin`
- Custom middleware: `\App\Http\Middleware\IsAdmin`
- Checks `users.is_admin` boolean flag
- Returns 403 if not admin

### `throttle:30,1`
- Rate limiting: max 30 requests per 1 minute per IP
- Applied to `/chat` endpoint
