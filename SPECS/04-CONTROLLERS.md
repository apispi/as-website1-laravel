# Controllers Specification

## Overview
Controllers follow namespaced organization: admin controllers in `App\Http\Controllers\Admin\`, public/auth in `App\Http\Controllers\`.

---

## AuthController

**File**: `app/Http/Controllers/AuthController.php`

### Public Routes (guests only)
```php
public function showLogin()              // GET /login
public function login()                  // POST /login
public function showRegister()           // GET /register
public function register()               // POST /register
public function showForgotPassword()     // GET /forgot-password
public function sendResetLink()          // POST /forgot-password
public function showResetPassword(string $token)  // GET /reset-password/{token}
public function resetPassword()          // POST /reset-password
```

### Authenticated Routes
```php
public function dashboard()              // GET /dashboard
public function catalog()                // GET /dashboard/catalog - browse agent catalog
public function userAgents()             // GET /dashboard/agents - user's subscriptions
public function userAgent(Subscription $subscription)  // GET /dashboard/agents/{subscription}
public function userConnectors()         // GET /dashboard/connectors
public function profile()                // GET /dashboard/profile
public function updateProfile()          // PUT /dashboard/profile
public function updatePassword()         // PUT /dashboard/profile/password
public function logout()                 // POST /logout
```

### Expected Behavior
- Login/register create `User` record
- Dashboard uses Vue.js mounting to `#dashboard-app` div
- Subscriptions indexed by subscription ID, not agent slug
- Profile allows name/email update and password change
- Logout clears session

---

## AgentController

**File**: `app/Http/Controllers/AgentController.php`

### Public Routes
```php
public function index()                  // GET /agents
  // Returns: Agent::active()->get()
  // View: agents.index with $agents

public function show(string $slug)       // GET /agents/{slug}
  // Returns: Agent::where('slug', $slug)->where('is_active', true)->firstOrFail()
  // View: agents.show with $agent
```

### Expected Behavior
- Index shows all active agents, ordered by sort_order, name
- Show returns 404 if agent not found or not active
- Views may include legacy static pages (e.g. `bid-tender.blade.php`) OR dynamic `show.blade.php`
- Dynamic `show.blade.php` drives agent detail pages

---

## SubscribeController

**File**: `app/Http/Controllers/SubscribeController.php`

### Public Routes
```php
public function store()                  // POST /subscribe
  // Creates subscription (authenticated or stored in session for later)
  // Expected body: { agent_id: number }
```

---

## ContactController

**File**: `app/Http/Controllers/ContactController.php`

### Public Routes
```php
public function show()                   // GET /contact
  // Returns: view('contact') with AI chatbot UI

public function send()                   // POST /contact
  // Validates message, proxies to Anthropic API via ChatController
  // Returns: JSON response with chatbot reply
```

---

## ChatController

**File**: `app/Http/Controllers/ChatController.php`

### Public Routes
```php
public function send()                   // POST /chat (rate-limited: 30/min per IP)
  // Body: { message: string }
  // Maintains conversation history (last 10 messages)
  // Proxies to Anthropic API: claude-sonnet-4-5
  // Returns: JSON { reply: string }
```

### Implementation Details
- Uses Laravel's `Http::post()` facade (no SDK)
- Requires `ANTHROPIC_API_KEY` in `.env`
- Rate-limited via `RateLimiter` by IP
- Session-based conversation history
- Returns 429 if rate limit exceeded

---

## ConnectorOAuthController

**File**: `app/Http/Controllers/ConnectorOAuthController.php`

### Authenticated Routes
```php
public function authorize(string $slug)      // GET /connectors/{slug}/authorize
  // Initiates OAuth flow for connector
  // Redirects to connector's oauth_auth_url

public function callback(string $slug)       // GET /connectors/{slug}/callback
  // OAuth callback handler
  // Receives authorization code
  // Exchanges for access token
  // Creates/updates ConnectorToken

public function disconnect(string $slug)     // POST /connectors/{slug}/disconnect
  // Revokes user's connector connection
  // Deletes UserConnector and ConnectorToken records
```

### Implementation Details
- Uses `OAuthService` to handle token exchange
- Creates `ConnectorToken` with encrypted access_token
- Updates `UserConnector.is_connected = true`
- Supports optional refresh_token and expiry tracking

---

## PageController

**File**: `app/Http/Controllers/PageController.php`

### Public Routes
```php
public function home()                   // GET /
public function about()                  // GET /about
public function training()               // GET /training
public function checkout()               // GET /checkout
```

---

## BlogController

**File**: `app/Http/Controllers/BlogController.php`

### Public Routes
```php
public function index()                  // GET /blog
public function show(string $slug)       // GET /blog/{slug}
```

---

## Admin Controllers

**Location**: `app/Http/Controllers/Admin/`

### DashboardController
```php
public function index()                  // GET /admin
  // Admin overview dashboard
```

### ActivityLogController
```php
public function index()                  // GET /admin/activity
  // Lists all activity logs, paginated
```

### UserController
```php
public function index()                  // GET /admin/users
public function show(User $user)         // GET /admin/users/{user}
```

### AgentController (Admin)
```php
public function index()                  // GET /admin/agents
public function create()                 // GET /admin/agents/create
public function store()                  // POST /admin/agents
public function edit(Agent $agent)       // GET /admin/agents/{agent}/edit
public function update(Agent $agent)     // PUT /admin/agents/{agent}
public function destroy(Agent $agent)    // DELETE /admin/agents/{agent}
```

### SkillController (Admin)
```php
public function index()                  // GET /admin/skills
public function create()                 // GET /admin/skills/create
public function store()                  // POST /admin/skills
public function edit(Skill $skill)       // GET /admin/skills/{skill}/edit
public function update(Skill $skill)     // PUT /admin/skills/{skill}
public function destroy(Skill $skill)    // DELETE /admin/skills/{skill}
```

### ConnectorController (Admin)
```php
public function index()                  // GET /admin/connectors
public function create()                 // GET /admin/connectors/create
public function store()                  // POST /admin/connectors
public function edit(Connector $connector)  // GET /admin/connectors/{connector}/edit
public function update(Connector $connector) // PUT /admin/connectors/{connector}
public function destroy(Connector $connector) // DELETE /admin/connectors/{connector}
```

### TrainingController (Admin)
```php
public function index()                  // GET /admin/trainings
public function create()                 // GET /admin/trainings/create
public function store()                  // POST /admin/trainings
public function edit(Training $training) // GET /admin/trainings/{training}/edit
public function update(Training $training) // PUT /admin/trainings/{training}
public function destroy(Training $training) // DELETE /admin/trainings/{training}
```

### SubscriptionController (Admin)
```php
public function index()                  // GET /admin/subscriptions
  // Lists all user subscriptions
```

### UserConnectorController (Admin)
```php
public function index()                  // GET /admin/connectors/users
  // Lists user connector associations
```

---

## General Controller Patterns

### Error Handling
- Use `firstOrFail()` to automatically return 404 on missing models
- Validate request input in controller or via Form Requests
- Return `view()` for Blade, `response()->json()` for AJAX

### Admin Logging
- Call `ActivityLog::log()` after state-changing operations
- Include `actor_id` (current admin) and affected `user_id` if applicable
- Optional metadata for additional context

### Vue Component Props
- Pass data via `data-props="..."` JSON on mount div
- Components access via `defineProps()`
- Props mutually incompatible with inline v-model bindings (use events)
