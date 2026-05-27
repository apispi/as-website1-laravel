# Controllers Specification

## Overview
Controllers follow namespaced organization:
- Admin controllers: `App\Http\Controllers\Admin\*`
- Public/Auth controllers: `App\Http\Controllers\*`

---

## AuthController

**File**: `app/Http/Controllers/AuthController.php`

### Guest-Only Routes (Middleware: `guest`)

#### Authentication Views
```php
public function showLogin()                // GET /login
public function login()                    // POST /login - email + password
public function showRegister()             // GET /register
public function register()                 // POST /register - name, email, password
```

#### Password Reset
```php
public function showForgotPassword()       // GET /forgot-password
public function sendResetLink()            // POST /forgot-password - email
public function showResetPassword($token)  // GET /reset-password/{token}
public function resetPassword()            // POST /reset-password - password, token
```

### Authenticated Routes (Middleware: `auth`)

#### Dashboard
```php
public function dashboard()                // GET /dashboard
    // Returns: Blade view 'dashboard.index'
    // Mounts Vue app to #dashboard-app
    // Props: user subscriptions, quick stats

public function catalog()                  // GET /dashboard/catalog
    // Browse available agents
    // Mounts Vue app to #catalog-app

public function userAgents()               // GET /dashboard/agents
    // User's active subscriptions
    // Mounts Vue app to #agents-list-app

public function userAgent(Subscription $subscription)  // GET /dashboard/agents/{subscription}
    // Individual subscription detail page
    // Mounts Vue app to #agent-detail-app
```

#### Profile
```php
public function profile()                  // GET /dashboard/profile
    // Shows user profile form
    // Mounts Vue app to #profile-app

public function updateProfile()            // PUT /dashboard/profile
    // Updates name, email
    // Validates email uniqueness

public function updatePassword()           // PUT /dashboard/profile/password
    // Requires current password verification
    // Hashes and saves new password
```

#### Session
```php
public function logout()                   // POST /logout
    // Destroys session, clears auth
    // Redirects to home
```

---

## AgentController

**File**: `app/Http/Controllers/AgentController.php`

### Public Routes

```php
public function index()                    // GET /agents
    // Returns: Agent::active()->get() with pagination
    // View: resources/views/agents/index.blade.php
    // Context: agent catalog, sorted by sort_order + name

public function show(string $slug)         // GET /agents/{slug}
    // Returns: Agent::where('slug', $slug)->where('is_active', true)->firstOrFail()
    // View: resources/views/agents/show.blade.php (dynamic)
    //   OR legacy static pages (resources/views/agents/{slug}.blade.php)
    // Returns 404 if agent not found or inactive
    // Context: detailed agent view with skills, pricing, FAQs
```

### Expected Data
- Agent with related `skills`, `connectors`, `subscriptions` eager-loaded
- Featured agents marked via `is_featured` flag
- Rating, price, and rich JSON content displayed

---

## SubscribeController

**File**: `app/Http/Controllers/SubscribeController.php`

```php
public function store()                    // POST /subscribe
    // Creates new subscription
    // Request body: { agent_id: number } (typically JSON)
    // Authentication: optional (can be stored in session for later)
    // Returns: 201 Created with subscription ID
    // Side effects: increments agent.users_count (via observer/event)
```

---

## ContactController

**File**: `app/Http/Controllers/ContactController.php`

```php
public function show()                     // GET /contact
    // Returns: view('contact') with AI chatbot UI
    // No data passed initially
    // Renders: static HTML + chatbot.js script

public function send()                     // POST /contact
    // Validates request body: { message: string }
    // Proxies to ChatController for Anthropic response
    // Returns: JSON response with chatbot reply
```

---

## ChatController

**File**: `app/Http/Controllers/ChatController.php`

### Rate Limiting
- Middleware: `throttle:30,1` — 30 requests per 1 minute per IP

### Public Endpoint
```php
public function send()                     // POST /chat
    // Request body: { message: string }
    // Returns: JSON { reply: string }
```

### Implementation Details
- Maintains conversation history in session (last 10 messages)
- Uses Laravel `Http::post()` facade (no Anthropic SDK)
- Proxies to: `https://api.anthropic.com/v1/messages`
- Model: `claude-sonnet-4-5` (from `ANTHROPIC_MODEL` env var)
- Requires: `ANTHROPIC_API_KEY` in `.env`
- Returns 429 status if rate limit exceeded

### Example Request/Response
```
POST /chat
{
  "message": "How do I get started with this agent?"
}

Response:
{
  "reply": "To get started with this agent, you can..."
}
```

---

## ConnectorOAuthController

**File**: `app/Http/Controllers/ConnectorOAuthController.php`

### Authenticated Routes (Middleware: `auth`)

```php
public function authorize(string $slug)    // GET /connectors/{slug}/authorize
    // Initiates OAuth 2.0 authorization flow
    // Retrieves connector config (client_id, oauth_auth_url, scopes)
    // Generates state token for CSRF protection
    // Redirects user to provider's authorization URL
    // Returns: HTTP redirect to provider

public function callback(string $slug)     // GET /connectors/{slug}/callback
    // OAuth callback handler (provider redirects here with code)
    // Validates state token
    // Exchanges authorization code for access token via OAuthService
    // Creates ConnectorToken with encrypted access_token
    // Updates UserConnector.is_connected = true
    // Returns: redirect to /dashboard/connectors with success message

public function disconnect(string $slug)   // POST /connectors/{slug}/disconnect
    // Revokes connector connection
    // Calls OAuthService::revokeToken() (if provider supports)
    // Deletes ConnectorToken
    // Updates UserConnector.is_connected = false
    // Returns: redirect to /dashboard/connectors
```

### Implementation Details
- Uses `OAuthService` for token exchange
- Stores encrypted tokens in `connector_tokens` table
- Supports `refresh_token` and token expiry
- CSRF protection via state parameter
- Error handling: user-friendly redirects on failed auth

---

## PageController

**File**: `app/Http/Controllers/PageController.php`

```php
public function home()                     // GET /
    // Returns: view('home')
    // Featured agents, CTA sections

public function about()                    // GET /about
    // Returns: view('about')
    // Company info, mission

public function training()                 // GET /training
    // Returns: view('training')
    // Training resources, docs

public function checkout()                 // GET /checkout
    // Returns: view('checkout')
```

---

## BlogController

**File**: `app/Http/Controllers/BlogController.php`

```php
public function index()                    // GET /blog
    // Returns: paginated blog posts
    // View: view('blog.index')

public function show(string $slug)         // GET /blog/{slug}
    // Returns: view('blog.show')
    // Returns 404 if not found
```

---

## DashboardChatController

**File**: `app/Http/Controllers/DashboardChatController.php`

```php
public function send()                     // POST /dashboard/chat
    // Similar to ChatController but for authenticated users
    // Rate limit: 30 requests/min
    // Maintains user-scoped conversation history
    // May include user context in prompts (subscriptions, agents)
```

---

## AvatarController

**File**: `app/Http/Controllers/AvatarController.php`

```php
public function index()                    // GET /digital-avatars
    // Returns: view('digital-avatars')
    // Displays digital avatar info and lead form

public function store()                    // POST /digital-avatars (throttled: 5/10min)
    // Validates lead form (name, email, phone, company, message)
    // Creates AvatarLead record
    // Returns: redirect with success message
```

---

## CheckoutController

**File**: `app/Http/Controllers/CheckoutController.php`

```php
public function show()                     // GET /checkout
    // Returns: view('checkout')

public function createSession()            // POST /checkout/session
    // Creates Stripe checkout session
    // Request body: { agent_id: number }
    // Returns: JSON { session_id: string }

public function success()                  // GET /checkout/success
    // Checkout success confirmation page
```

---

## PartnerController

**File**: `app/Http/Controllers/PartnerController.php`

```php
public function show()                     // GET /partners
    // Returns: view('partners')
    // Partner info and partnerships

public function store()                    // POST /partners (throttled: 5/10min)
    // Submits partner inquiry
    // Creates activity log entry
```

---

## Admin Controllers

All admin controllers located in `app/Http/Controllers/Admin/`.

### Admin\DashboardController
```php
public function index()                    // GET /admin/
    // Returns: view('admin.dashboard')
    // Stats: user count, agent count, recent activity
```

### Admin\ActivityLogController
```php
public function index()                    // GET /admin/activity
    // Paginated activity logs
    // Filters: action, user, date range
    // Shows actor_id (admin performing action)
```

### Admin\UserController
```php
public function index()                    // GET /admin/users
    // Paginated users list with counts (subscriptions, etc.)

public function show(User $user)           // GET /admin/users/{user}
    // User detail: subscriptions, activity, connectors

public function toggleAdmin(User $user)    // POST /admin/users/{user}/toggle-admin
    // Toggle user's is_admin flag
    // Logs action via ActivityLog::log()
```

### Admin\AgentController (CRUD)
```php
public function index()                    // GET /admin/agents
public function create()                   // GET /admin/agents/create
public function store()                    // POST /admin/agents
public function edit(Agent $agent)         // GET /admin/agents/{agent}/edit
public function update(Agent $agent)       // PUT /admin/agents/{agent}
public function destroy(Agent $agent)      // DELETE /admin/agents/{agent}
```

### Admin\SkillController (CRUD)
```php
public function index()                    // GET /admin/skills
public function create()                   // GET /admin/skills/create
public function store()                    // POST /admin/skills
public function edit(Skill $skill)         // GET /admin/skills/{skill}/edit
public function update(Skill $skill)       // PUT /admin/skills/{skill}
public function destroy(Skill $skill)      // DELETE /admin/skills/{skill}
```

### Admin\ConnectorController (CRUD)
```php
public function index()                    // GET /admin/connectors
public function create()                   // GET /admin/connectors/create
public function store()                    // POST /admin/connectors
public function edit(Connector $connector) // GET /admin/connectors/{connector}/edit
public function update(Connector $connector) // PUT /admin/connectors/{connector}
public function destroy(Connector $connector) // DELETE /admin/connectors/{connector}
```

### Admin\SubscriptionController
```php
public function allAgents()                // GET /admin/subscriptions
    // All subscriptions grouped by agent
    // Filters: status, date range

public function show(Subscription $subscription) // GET /admin/subscriptions/{subscription}
    // Subscription detail with user info
```

### Admin\TrainingController (CRUD)
```php
public function index()                    // GET /admin/trainings
public function create()                   // GET /admin/trainings/create
public function store()                    // POST /admin/trainings
public function edit(Training $training)   // GET /admin/trainings/{training}/edit
public function update(Training $training) // PUT /admin/trainings/{training}
public function destroy(Training $training) // DELETE /admin/trainings/{training}
```

### Admin\LeadsController
```php
public function index()                    // GET /admin/leads
    // Paginated avatar leads list

public function destroy(AvatarLead $lead)  // DELETE /admin/leads/{lead}
    // Delete lead record
```

### Admin\UserConnectorController
```php
public function index()                    // GET /admin/connectors/users
    // Lists user connector associations
```

### Admin\AgentSkillController
```php
public function index()                    // GET /admin/agents/{agent}/skills
    // Agent's assigned skills with pivot data

public function store()                    // POST /admin/agents/{agent}/skills
    // Assign skill to agent

public function destroy()                  // DELETE /admin/agents/{agent}/skills/{skill}
    // Remove skill from agent
```

### Admin\SubscriptionSkillController
```php
public function index()                    // GET /admin/subscriptions/{subscription}/skills
    // Skills assigned to subscription

public function store()                    // POST /admin/subscriptions/{subscription}/skills
    // Assign skill to subscription
```

---

## Exception Handling
- 404 errors for inactive agents or not-found resources
- 403 Forbidden for unauthorized admin access
- 429 Too Many Requests for rate-limited endpoints
- 422 Validation errors returned as JSON or redirect with errors

---

## Activity Logging
Controllers call `ActivityLog::log()` for:
- User registration/login
- Admin actions (creating/updating/deleting resources)
- Connector OAuth connections/disconnections
- Subscription changes
