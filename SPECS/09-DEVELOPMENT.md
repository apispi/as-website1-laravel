# Development & Testing Specification

## Development Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL 8.0+ or MariaDB 10.4+

### Initial Setup
```bash
# 1. Clone repository
git clone https://github.com/yourorg/apispi.git
cd apispi

# 2. Install PHP dependencies
composer install

# 3. Copy environment file and generate APP_KEY
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
# DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Run migrations
php artisan migrate

# 6. Seed initial data (optional)
php artisan db:seed

# 7. Install Node dependencies
npm install

# 8. Build frontend assets
npm run build

# 9. Start development servers
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Vite (for HMR)
npm run dev
```

### Environment Variables
```
APP_NAME="APISPI"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apispi_dev
DB_USERNAME=root
DB_PASSWORD=

ANTHROPIC_API_KEY=sk-...
ANTHROPIC_MODEL=claude-sonnet-4-5

SESSION_DRIVER=database
```

---

## Development Commands

### PHP/Laravel
```bash
# Run development server
php artisan serve                  # Runs on http://localhost:8000

# Database
php artisan migrate                # Run pending migrations
php artisan migrate:fresh          # Reset & migrate
php artisan db:seed                # Run all seeders
php artisan db:seed --class=AgentSeeder  # Run specific seeder

# Tinker (interactive REPL)
php artisan tinker
  > $user = User::first()
  > $user->is_admin = true
  > $user->save()

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Code formatting (Laravel Pint)
./vendor/bin/pint                  # Format code
./vendor/bin/pint --check         # Check formatting without changes
```

### Node/Frontend
```bash
# Install dependencies
npm install

# Build production assets
npm run build                      # Output to public_html/build/

# Watch mode (development)
npm run dev                        # Vite dev server + HMR

# Preview production build
npm run preview                    # Local preview of production build
```

---

## Testing

### Test Structure
```
tests/
├── TestCase.php                   # Base test class
├── Feature/                       # Feature tests
│   ├── AuthTest.php
│   ├── AgentTest.php
│   └── ...
└── Unit/                          # Unit tests
    ├── Models/
    ├── Services/
    └── ...
```

### Base TestCase
**File**: `tests/TestCase.php`

```php
abstract class TestCase extends BaseTestCase
{
    // Add test helpers here
}
```

### Running Tests
```bash
# All tests
composer test                      # Runs phpunit

# Specific test class
php artisan test tests/Feature/AuthTest.php

# Specific test method
php artisan test tests/Feature/AuthTest.php --filter=test_user_can_login

# With coverage
php artisan test --coverage

# Stop on first failure
php artisan test --stop-on-failure

# Parallel execution
php artisan test --parallel
```

### Test Configuration
**File**: `phpunit.xml`
```xml
<phpunit bootstrap="bootstrap/app.php">
  <!-- Coverage & output settings -->
  <testsuites>
    <testsuite name="Unit">
      <directory suffix="Test.php">./tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
      <directory suffix="Test.php">./tests/Feature</directory>
    </testsuite>
  </testsuites>
</phpunit>
```

### Example Feature Tests

```php
// tests/Feature/AuthTest.php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ]);

        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
```

```php
// tests/Feature/AgentTest.php
class AgentTest extends TestCase
{
    public function test_guest_can_browse_agents()
    {
        $agents = Agent::factory(3)->create(['is_active' => true]);

        $response = $this->get('/agents');

        $response->assertOk();
        $response->assertViewHas('agents');
        $this->assertCount(3, $response->viewData('agents'));
    }

    public function test_guest_can_view_agent_detail()
    {
        $agent = Agent::factory()->create(['slug' => 'aria-agent', 'is_active' => true]);

        $response = $this->get('/agents/aria-agent');

        $response->assertOk();
        $response->assertViewHas('agent');
    }

    public function test_inactive_agent_returns_404()
    {
        $agent = Agent::factory()->create(['slug' => 'hidden-agent', 'is_active' => false]);

        $this->get('/agents/hidden-agent')
            ->assertNotFound();
    }
}
```

```php
// tests/Feature/SubscriptionTest.php
class SubscriptionTest extends TestCase
{
    public function test_user_can_subscribe_to_agent()
    {
        $user = User::factory()->create();
        $agent = Agent::factory()->create();

        $response = $this->actingAs($user)->post('/subscribe', [
            'agent_id' => $agent->id,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'agent_id' => $agent->id,
        ]);
    }
}
```

### Example Unit Tests

```php
// tests/Unit/Models/UserTest.php
namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_can_be_admin()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->assertFalse($user->is_admin);

        $user->is_admin = true;
        $user->save();

        $this->assertTrue($user->fresh()->is_admin);
    }

    public function test_user_has_subscriptions()
    {
        $user = User::factory()->hasSubscriptions(3)->create();
        $this->assertCount(3, $user->subscriptions);
    }
}
```

### Factory Pattern

**File**: `database/factories/UserFactory.php`

```php
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->email(),
            'password' => 'password',  // unencrypted in factory
            'is_admin' => false,
        ];
    }

    public function admin()
    {
        return $this->state(['is_admin' => true]);
    }
}
```

### Seeders

Run seeders to populate test/dev databases:

```bash
php artisan db:seed                # All seeders
php artisan db:seed --class=AgentSeeder  # Specific seeder
php artisan migrate:fresh --seed   # Migrate & seed
```

---

## Code Quality

### Linting & Formatting
```bash
# Laravel Pint (PHP formatter)
./vendor/bin/pint                  # Fix code style

# Check without fixing
./vendor/bin/pint --check

# Specific files/directories
./vendor/bin/pint app/ routes/
```

### Standards
- PSR-12 coding standard (enforced by Pint)
- 80-character line limit (configurable)
- Consistent indentation (4 spaces)

### IDE Setup
- Use `.editorconfig` for consistent formatting
- Install Pint extension in VS Code
- Enable format on save

---

## Common Tasks

### Add New Migration
```bash
php artisan make:migration create_table_name
# or with table scaffold
php artisan make:migration create_users_table --create=users
php artisan make:migration add_field_to_table --table=existing_table
```

### Create Model + Migration + Controller
```bash
php artisan make:model Agent -mcr
# -m: migration, -c: controller, -r: resource controller
```

### Generate API Documentation
```bash
# Via Scribe (if installed)
php artisan scribe:generate
```

### Database Inspection
```php
// In tinker
> Schema::getColumnListing('agents')
> Schema::getColumns('agents')
```
