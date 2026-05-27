# Routes Specification

## Route Structure

All routes defined in `routes/web.php`.

---

## Public Routes (No Authentication Required)

### Pages
```
GET  /                          → PageController@home
GET  /about                     → PageController@about
GET  /training                  → PageController@training
```

### Contact & Chat (AI)
```
GET  /contact                   → ContactController@show
POST /contact                   → ContactController@send
POST /chat                      → ChatController@send (rate-limited: 30 requests/min per IP)
```

### Agents Marketplace
```
GET  /agents                    → AgentController@index
GET  /agents/{slug}             → AgentController@show
```

### Blog
```
GET  /blog                      → BlogController@index
GET  /blog/{slug}               → BlogController@show
```

### Digital Avatars & Forms
```
GET  /digital-avatars           → AvatarController@index
POST /digital-avatars           → AvatarController@store (rate-limited: 5 requests/10min per IP)

GET  /checkout                  → CheckoutController@show
POST /checkout/session          → CheckoutController@createSession
GET  /checkout/success          → CheckoutController@success

GET  /partners                  → PartnerController@show
POST /partners                  → PartnerController@store (rate-limited: 5 requests/10min per IP)
```

### Subscriptions
```
POST /subscribe                 → SubscribeController@store
```

---

## Authentication Routes (Guests Only)

Middleware: `guest` — redirects authenticated users to `/dashboard`

### Login
```
GET  /login                     → AuthController@showLogin
     Route name: login
POST /login                     → AuthController@login
```

### Registration
```
GET  /register                  → AuthController@showRegister
     Route name: register
POST /register                  → AuthController@register
```

### Password Reset
```
GET  /forgot-password           → AuthController@showForgotPassword
     Route name: password.request
POST /forgot-password           → AuthController@sendResetLink
     Route name: password.email

GET  /reset-password/{token}    → AuthController@showResetPassword
     Route name: password.reset
     Param: token (password reset token)
POST /reset-password            → AuthController@resetPassword
     Route name: password.store
```

---

## Authenticated Routes

Middleware: `auth` — requires login, redirects to `/login` if not authenticated

### Dashboard
```
GET  /dashboard                 → AuthController@dashboard
     Route name: dashboard
     Renders: Blade view 'dashboard.index' with Vue app mounting to #dashboard-app

GET  /dashboard/catalog         → AuthController@catalog
     Route name: dashboard.catalog
     Browse available agents via Vue

GET  /dashboard/agents          → AuthController@userAgents
     Route name: dashboard.agents.index
     User's subscriptions, mounts Vue app to #agents-list-app

GET  /dashboard/agents/{subscription}  → AuthController@userAgent
     Route name: dashboard.agents.show
     Param: subscription (Subscription model ID)
     Individual subscription detail, mounts Vue app to #agent-detail-app
```

### Connectors Management
```
GET  /dashboard/connectors      → AuthController@userConnectors
     Route name: dashboard.connectors
     List user's OAuth connector connections

GET  /dashboard/connectors/{userConnector}/edit  → AuthController@editConnector
     Route name: dashboard.connectors.edit
     Edit connector config

PUT  /dashboard/connectors/{userConnector}       → AuthController@updateConnector
     Route name: dashboard.connectors.update
```

### Profile
```
GET  /dashboard/profile         → AuthController@profile
     Route name: dashboard.profile
     Mounts Vue app to #profile-app

PUT  /dashboard/profile         → AuthController@updateProfile
     Route name: dashboard.profile.update
     Updates user name, email

PUT  /dashboard/profile/password → AuthController@updatePassword
     Route name: dashboard.profile.password
     Updates password (requires current password verification)
```

### Dashboard Chat
```
POST /dashboard/chat            → DashboardChatController@send
     Route name: dashboard.chat
     Rate-limited: 30 requests/min
     For authenticated users (context-aware)
```

### Session Management
```
POST /logout                    → AuthController@logout
     Route name: logout
     Destroys session and logs out user
```

### OAuth Connector Flow
```
GET  /connectors/{slug}/authorize    → ConnectorOAuthController@authorize
     Route name: connectors.authorize
     Initiates OAuth flow, redirects to provider

GET  /connectors/{slug}/callback     → ConnectorOAuthController@callback
     Route name: connectors.callback
     OAuth callback handler, exchanges code for token

POST /connectors/{slug}/disconnect   → ConnectorOAuthController@disconnect
     Route name: connectors.disconnect
     Revokes and deletes connector connection
```

---

## Admin Routes

Middleware: `auth` + `admin` — requires login AND `users.is_admin = true`  
Prefix: `/admin/` — Route name prefix: `admin.`  
Returns 403 Forbidden if not admin

### Dashboard
```
GET  /admin/                    → Admin\DashboardController@index
     Route name: admin.dashboard
     Admin overview: stats, recent activity
```

### Activity Logs
```
GET  /admin/activity            → Admin\ActivityLogController@index
     Route name: admin.activity
     Paginated activity logs with filters
```

### Users Management
```
GET  /admin/users               → Admin\UserController@index
     Route name: admin.users.index
     Paginated users list with stats

GET  /admin/users/{user}        → Admin\UserController@show
     Route name: admin.users.show
     User detail: subscriptions, activity, connectors

POST /admin/users/{user}/toggle-admin  → Admin\UserController@toggleAdmin
     Route name: admin.users.toggle-admin
     Toggle user's is_admin flag
```

### Agents Management (CRUD)
```
GET  /admin/agents              → Admin\AgentController@index
     Route name: admin.agents.index

GET  /admin/agents/create       → Admin\AgentController@create
     Route name: admin.agents.create

POST /admin/agents              → Admin\AgentController@store
     Route name: admin.agents.store

GET  /admin/agents/{agent}/edit → Admin\AgentController@edit
     Route name: admin.agents.edit

PUT  /admin/agents/{agent}      → Admin\AgentController@update
     Route name: admin.agents.update

DELETE /admin/agents/{agent}    → Admin\AgentController@destroy
     Route name: admin.agents.destroy
```

### Skills Management (CRUD)
```
GET  /admin/skills              → Admin\SkillController@index
     Route name: admin.skills.index

GET  /admin/skills/create       → Admin\SkillController@create
     Route name: admin.skills.create

POST /admin/skills              → Admin\SkillController@store
     Route name: admin.skills.store

GET  /admin/skills/{skill}/edit → Admin\SkillController@edit
     Route name: admin.skills.edit

PUT  /admin/skills/{skill}      → Admin\SkillController@update
     Route name: admin.skills.update

DELETE /admin/skills/{skill}    → Admin\SkillController@destroy
     Route name: admin.skills.destroy
```

### Connectors Management (CRUD)
```
GET  /admin/connectors          → Admin\ConnectorController@index
     Route name: admin.connectors.index

GET  /admin/connectors/create   → Admin\ConnectorController@create
     Route name: admin.connectors.create

POST /admin/connectors          → Admin\ConnectorController@store
     Route name: admin.connectors.store

GET  /admin/connectors/{connector}/edit  → Admin\ConnectorController@edit
     Route name: admin.connectors.edit

PUT  /admin/connectors/{connector}       → Admin\ConnectorController@update
     Route name: admin.connectors.update

DELETE /admin/connectors/{connector}     → Admin\ConnectorController@destroy
     Route name: admin.connectors.destroy
```

### Trainings Management (CRUD)
```
GET  /admin/trainings           → Admin\TrainingController@index
     Route name: admin.trainings.index

GET  /admin/trainings/create    → Admin\TrainingController@create
     Route name: admin.trainings.create

POST /admin/trainings           → Admin\TrainingController@store
     Route name: admin.trainings.store

GET  /admin/trainings/{training}/edit  → Admin\TrainingController@edit
     Route name: admin.trainings.edit

PUT  /admin/trainings/{training}       → Admin\TrainingController@update
     Route name: admin.trainings.update

DELETE /admin/trainings/{training}     → Admin\TrainingController@destroy
     Route name: admin.trainings.destroy
```

### Subscriptions Management
```
GET  /admin/subscriptions       → Admin\SubscriptionController@allAgents
     Route name: admin.subscriptions.index
     All subscriptions grouped by agent

GET  /admin/subscriptions/{subscription}  → Admin\SubscriptionController@show
     Route name: admin.subscriptions.show
     Subscription detail with user info
```

### User Connectors & Leads Management
```
GET  /admin/connectors/users    → Admin\UserConnectorController@index
     Route name: admin.user-connectors.index
     User connector associations

GET  /admin/leads               → Admin\LeadsController@index
     Route name: admin.leads.index
     Avatar leads list

DELETE /admin/leads/{lead}      → Admin\LeadsController@destroy
     Route name: admin.leads.destroy
```

---

## Route Naming Conventions

- **Public routes**: `{resource}.{action}` 
  - Examples: `agents.index`, `agents.show`, `contact`, `blog.show`

- **Authenticated routes**: `dashboard.{resource}.{action}`
  - Examples: `dashboard.agents.index`, `dashboard.profile`, `dashboard.connectors`

- **Admin routes**: `admin.{resource}.{action}`
  - Examples: `admin.agents.index`, `admin.users.show`, `admin.trainings.edit`

- **Connector OAuth**: `connectors.{action}`
  - Examples: `connectors.authorize`, `connectors.callback`, `connectors.disconnect`

---

## Middleware Stack

### Global Middleware
- `TrimStrings` — trim whitespace from requests
- `ConvertEmptyStringsToNull`
- `TrustProxies` — proxy configuration
- `RequestSize` — limit request size

### Named Middleware

#### `guest`
- **File**: Laravel built-in
- **Behavior**: Redirects authenticated users away (e.g., from `/login` to `/dashboard`)
- **Used on**: `/login`, `/register`, `/forgot-password`, `/reset-password` routes

#### `auth`
- **File**: Laravel built-in
- **Behavior**: Requires authentication, redirects to `/login` if not logged in
- **Used on**: All `/dashboard/*` routes, authenticated OAuth routes

#### `admin`
- **File**: `App\Http\Middleware\IsAdmin`
- **Behavior**: Checks `users.is_admin = true`, returns 403 if false
- **Used on**: All `/admin/*` routes (in combination with `auth`)

#### `throttle:30,1`
- **Behavior**: Max 30 requests per 1 minute per IP
- **Used on**: `/chat`, `/dashboard/chat`

#### `throttle:5,10`
- **Behavior**: Max 5 requests per 10 minutes per IP
- **Used on**: `/digital-avatars`, `/partners` (form submissions)

---

## CSRF Protection
- All POST, PUT, DELETE routes require CSRF token
- Token passed via:
  - `@csrf` Blade directive (forms)
  - `X-CSRF-Token` header (AJAX)
  - Cookie: `XSRF-TOKEN`

---

## Route Parameters & Binding

### Model Binding
```php
Route::get('/dashboard/agents/{subscription}')
     // Automatically resolves to Subscription model by ID
     
Route::put('/admin/agents/{agent}')
     // Automatically resolves to Agent model by ID
```

### Custom Parameters
```php
Route::get('/agents/{slug}')
     // Custom parameter: slug (not auto-bound to model)
     // Resolved manually in controller: Agent::where('slug', $slug)->...

Route::get('/connectors/{slug}/authorize')
     // Custom parameter: connector slug
     // Resolved manually in controller
```

---

## Status Codes
- **200 OK**: Successful GET/PUT/DELETE
- **201 Created**: Successful POST (resource created)
- **204 No Content**: Successful DELETE (no response body)
- **302 Found**: Redirects (POST → redirect pattern)
- **400 Bad Request**: Validation errors
- **401 Unauthorized**: Not authenticated
- **403 Forbidden**: Authenticated but not authorized (e.g., not admin)
- **404 Not Found**: Resource not found
- **422 Unprocessable Entity**: Validation errors (API responses)
- **429 Too Many Requests**: Rate limit exceeded
