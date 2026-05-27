# Authentication & Authorization Specification

## Laravel Authentication

### Build-in Features
- **Guard**: `web` (session-based)
- **Provider**: `users` table
- **Password hashing**: `bcrypt` (via `Hash::make()`, automatic via `password` cast)
- **Session**: Database-backed (`SESSION_DRIVER=database`)
- **Email verification**: Not enabled
- **Password reset**: Token-based email reset flow

### User Registration

**Route**: `POST /register` (guest middleware)

```php
// Request body
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

**Process**:
1. Validate name, email (unique), password (min 8 chars, confirmed)
2. Hash password via `Hash::make()`
3. Create `User` record
4. Auto-login user (set session)
5. Redirect to `/dashboard`

**Side Effects**:
- `ActivityLog::log('registered', '...')` created
- User assigned to default group (if applicable)

### User Login

**Route**: `POST /login` (guest middleware)

```php
// Request body
{
  "email": "john@example.com",
  "password": "secret123",
  "remember": true  // optional: extend session to 2 weeks
}
```

**Process**:
1. Validate email and password
2. Hash submitted password, compare to `users.password`
3. On success:
   - Set session with user ID
   - Store `remember_token` if "remember me" checked
   - Redirect to `/dashboard` (or intended destination)
4. On failure:
   - Flash error to session
   - Redirect back to login

**Remember Me**:
- Stores token in `users.remember_token` and browser cookie
- Allows auto-login for ~2 weeks
- Revoked on logout

### Password Reset

**Flow**:
1. User visits `/forgot-password`
2. Submits email address
3. Laravel sends reset email with token link: `/reset-password/{token}`
4. User clicks link, sees password reset form
5. Submits new password (confirmed)
6. Password hashed and saved
7. Token invalidated (one-time use)

**Token Features**:
- Generated via `password_reset` table
- Expires after 60 minutes (configurable)
- One-time use only
- Checked for validity on reset form

---

## Admin Authorization

### Admin Flag
- **Column**: `users.is_admin` (boolean, default false)
- **Set By**: Admin manually (not self-registration)
- **Toggle Route**: `POST /admin/users/{user}/toggle-admin` (admin-only)
- **Activity Log**: Logged when toggled

### Middleware: IsAdmin

**File**: `app/Http/Middleware/IsAdmin.php`

```php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check()) {
        abort(401, 'Unauthenticated');
    }
    
    if (!auth()->user()->is_admin) {
        abort(403, 'Not authorized for this resource');
    }
    
    return $next($request);
}
```

### Admin Route Protection
```php
// All /admin/* routes require BOTH auth + admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ...
});
```

### Admin UI Color Scheme
- Red/Rose palette (e.g., `bg-rose-50`, `text-red-600`, `border-red-200`)
- Distinct from user dashboard (amber/gold palette)
- Visual protection against accidental administrative actions

### Admin Actions Requiring Logging
All admin actions call `ActivityLog::log()`:
- Create/Update/Delete agents, skills, connectors
- Manage users (toggle admin status)
- Create/manage trainings
- View/manage subscriptions and activity

---

## Session Management

### Configuration
- **Driver**: `database` (MUST be set in `.env` and `.env.example`)
- **Table**: `sessions` (auto-created via migration)
- **Lifetime**: 2 hours (default, configurable in `config/session.php`)
- **Encryption**: Session payload encrypted via `APP_KEY`

### Session Storage
```php
// sessions table columns
- id (string): Session ID (primary key)
- user_id (bigint): Authenticated user ID (nullable)
- ip_address (string): Client IP address
- user_agent (text): Browser user agent
- payload (longtext): Serialized session data (encrypted)
- last_activity (int): Unix timestamp of last activity
```

### Session Lifecycle
1. **Creation**: On successful login, session created with user_id
2. **Activity**: Last activity timestamp updated on each request
3. **Expiration**: Sessions older than lifetime removed (via garbage collector)
4. **Destruction**: On logout, session record deleted

### Logout
**Route**: `POST /logout` (auth middleware)

```php
public function logout()
{
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
}
```

**Process**:
1. Clear `auth()` state
2. Delete session from database
3. Regenerate CSRF token (new form CSRF tokens valid)
4. Redirect to home

---

## Session Driver Setup

### Required Migration
Session driver requires `sessions` table:
```bash
php artisan migrate
```

If table doesn't exist, session storage will fail with errors.

### Verify Configuration
```php
// config/session.php
'driver' => env('SESSION_DRIVER', 'database'),  // Must be 'database'
'lifetime' => env('SESSION_LIFETIME', 120),      // Minutes
'expire_on_close' => false,                      // Session survives browser close
'secure' => env('SESSION_SECURE_COOKIES'),       // HTTPS only in production
'http_only' => true,                             // No JS access to cookie
'same_site' => 'lax',                            // CSRF protection
```

### Production Session Settings
```
# .env (production)
SESSION_DRIVER=database
SESSION_LIFETIME=120          # 2 hours
SESSION_SECURE_COOKIES=true   # HTTPS only
SESSION_SAME_SITE=lax
```

---

## CSRF Protection

### Automatic Setup
- `VerifyCsrfToken` middleware applied to all routes (except API routes)
- Token automatically included in forms via `@csrf`
- Token stored in session
- Token sent in form submit validated server-side

### Form CSRF Example
```blade
<form method="POST" action="/login">
    @csrf
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
</form>
```

### AJAX CSRF Example
```js
// Fetch request with CSRF token
fetch('/api/action', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({ /* data */ })
})
```

**Token Locations**:
- `<meta name="csrf-token">` in Blade layout (for AJAX)
- Form hidden input `<input type="hidden" name="_token" value="..." />`
- Session stored token

### Excluding Routes from CSRF
```php
// config/middleware.php (if needed)
protected $except = [
    'webhooks/*',  // Stripe, etc.
];
```

---

## Authentication State in Views & Controllers

### Checking Authentication

**In Controllers**:
```php
if (auth()->check()) {
    // User is logged in
    $user = auth()->user();  // App\Models\User instance
}

if (auth()->guest()) {
    // User is not logged in
}
```

**In Blade Templates**:
```blade
@auth
    <!-- User is logged in -->
    <p>Welcome, {{ auth()->user()->name }}!</p>
@endauth

@guest
    <!-- User is not logged in -->
    <p><a href="/login">Login</a></p>
@endguest

@admin
    <!-- User is logged in AND is_admin = true -->
    <a href="/admin">Admin Panel</a>
@endadmin
```

### Redirecting After Login
```php
// Redirect to intended page (from 'intended' session)
return redirect()->intended('/dashboard');

// Or default to dashboard
return redirect('/dashboard');
```

---

## Admin-Only Blade Directive

**File**: `app/Providers/AppServiceProvider.php` (or custom provider)

```php
public function boot()
{
    Blade::if('admin', function () {
        return auth()->check() && auth()->user()->is_admin;
    });
}
```

**Usage**:
```blade
@admin
    <a href="/admin">Admin Panel</a>
@endadmin
```

---

## Token & Session Tampering Protection

### Server-side Validation
- Session payload validated on each request
- Invalid sessions rejected automatically
- IP mismatches logged (optional, configurable)
- User agent mismatches logged (optional)

### Client-side Security
- Session cookie: `HttpOnly` flag prevents JavaScript access
- Secure flag: HTTPS-only in production
- SameSite: `Lax` prevents cross-site session theft
- CSRF token: Regenerated on login

---

## Password Hashing Algorithm

### Algorithm: Bcrypt
- **Cost**: 10 iterations (default, configurable)
- **Algorithm**: `BCRYPT` (not MD5 or SHA1)
- **Hashing done at**: Model level via `password` cast

### Verifying Password
```php
if (Hash::check($plainPassword, $user->password)) {
    // Password is correct
}
```

### Upgrading Hashes
Laravel automatically re-hashes weak passwords on login (configurable).

---

## Two-Factor Authentication
**Not Currently Implemented**. Can be added via `laravel/fortify` package.

---

## Single Sign-On (SSO) / OAuth
**Not Currently Implemented for user login**. OAuth is used for **connectors** (e.g., Slack, Zapier) via `OAuthService`, not for user authentication.

### API Requests
- AJAX requests must include `X-CSRF-Token` header or `_token` in body
- Token accessible via `document.querySelector('[name=csrf-token]').content`

---

## Authentication States

### Unauthenticated
- Session doesn't exist or expired
- `auth()->check()` returns false
- Attempting to access protected route redirects to `/login`

### Authenticated User
- Session exists with valid `user_id`
- `auth()->check()` returns true
- `auth()->user()` returns `User` model instance
- Can access `/dashboard/*` routes

### Authenticated Admin
- Same as user, PLUS `users.is_admin = true`
- Can access `/admin/*` routes
- ActivityLog should include `actor_id` for admin actions

### Remember Me
- Login form includes "Remember me" checkbox
- Sets persistent `remember_token` in users table
- Auto-authenticates on return visit (24 hours by default)

---

## Testing Authentication

### TestCase Helper Methods
```php
// In tests extending TestCase
$user = User::factory()->create();
$admin = User::factory()->admin()->create();

// Acting as user
$this->actingAs($user)
    ->get('/dashboard')
    ->assertOk();

// Acting as guest
$this->get('/login')
    ->assertOk();

// Unauthenticated access to protected route
$this->get('/dashboard')
    ->assertRedirect('/login');

// Non-admin access to admin routes
$this->actingAs($user)
    ->get('/admin')
    ->assertStatus(403);
```

### Authentication Testing Examples
```php
class AuthTest extends TestCase
{
    public function test_user_can_register()
    {
        $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->assertRedirect('/dashboard');
        
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create(['password' => 'password123']);
        
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])
        ->assertRedirect('/dashboard');
        
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_access_admin_routes()
    {
        $admin = User::factory()->admin()->create();
        
        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_non_admin_cannot_access_admin_routes()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(403);
    }
}
```
