# Authentication & Authorization Specification

## Laravel Authentication

### Built-in Features Used
- Standard Laravel Auth guard (`web`)
- Password hashing via `Hash::make()` (automatic via `password` cast)
- Session-based authentication (database-backed)
- Email verification not currently enabled
- Password reset via email tokens

### User Registration
- Name, email, password
- Password auto-hashed via mutator
- No email verification required
- Auto-logs in after successful registration

### User Login
- Email + password
- "Remember me" option stores token
- Redirects to `/dashboard` on success
- Redirects to login on unauthorized access

### Password Reset
1. User submits email on `/forgot-password`
2. Email sent with reset link (token-based)
3. User clicks link → `/reset-password/{token}`
4. Submits new password → hashed and saved
5. Token invalidated after use

---

## Admin Authorization

### Admin Flag
- `users.is_admin` boolean column
- Set by admin manually (not self-registration)
- Cannot be set via normal registration

### Middleware: IsAdmin
**File**: `app/Http/Middleware/IsAdmin.php`

```php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Unauthorized');
    }
    return $next($request);
}
```

### Admin Route Protection
```php
// All routes under /admin/* require BOTH auth + admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // ...
});
```

### Color Scheme
- Admin UI: Red/rose palette (vs user dashboard's amber/gold)
- Visual distinction to prevent accidental actions

---

## Session Management

### Configuration
- **Driver**: `database` (REQUIRED in `.env`)
- **Table**: `sessions` (must exist)
- **Lifetime**: Configurable in `config/session.php` (default: ~2 hours)

### Session Storage
- User ID stored in `sessions.user_id`
- IP address logged in `sessions.ip_address`
- User agent in `sessions.user_agent`
- Payload (serialized data) in `sessions.payload`

### Logout
- Destroys session record
- Clears authentication state
- Redirects to home

---

## Middleware Stack

### Guest Middleware
- Redirects authenticated users away from auth pages
- Applied to: login, register, password reset routes
- Prevents already-logged-in users from seeing these pages

### Auth Middleware
- Redirects unauthenticated users to `/login`
- Applied to: all `/dashboard/*`, `/connectors/*` routes

### Admin Middleware
- Requires `auth` + `users.is_admin = true`
- Applied to: all `/admin/*` routes
- Returns 403 if not admin

### Throttle Middleware
- Rate limiting per IP
- Applied to: `/chat` endpoint (30 requests/minute per IP)

---

## Session Driver Configuration

### Database Setup
Ensure `SESSION_DRIVER=database` in `.env` and run:
```bash
php artisan migrate
```

This creates the `sessions` table.

### Session Payload
- Session payload is serialized PHP
- Contains user ID, CSRF token, authentication state
- Encrypted by default (see `config/app.php` -> `APP_KEY`)

### Session Cleanup
Laravel includes a garbage collector to delete expired sessions. Configure via:
```php
// config/session.php
'lottery' => [2, 100],  // 2% chance of cleanup per request
```

---

## CSRF Protection

### Automatic Protection
- All POST/PUT/DELETE requests require CSRF token
- Token automatically included in forms via `@csrf`
- Stored in session
- Verified by `VerifyCsrfToken` middleware (applied globally)

### Token in Forms
```blade
<form method="POST" action="/login">
    @csrf
    <input type="email" name="email" />
    <input type="password" name="password" />
    <button type="submit">Login</button>
</form>
```

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
