# Development & Testing Specification

## Development Setup

### Prerequisites
- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18 or higher
- **npm**: Latest version
- **Database**: MySQL 8.0+ or MariaDB 10.4+
- **Git**: For version control

### Initial Setup (5 Steps)

```bash
# 1. Clone repository
git clone https://github.com/yourorg/as-website1-laravel.git
cd as-website1-laravel

# 2. Install PHP dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Setup database
php artisan migrate
php artisan db:seed              # Optional: populate sample data

# 5. Setup frontend
npm install
npm run build                     # Build frontend assets locally
```

### Local Development Environment

**Terminal 1**: Start Laravel development server
```bash
php artisan serve
# Runs on http://localhost:8000
```

**Terminal 2**: Start Vite development server (HMR enabled)
```bash
npm run dev
# Runs on http://localhost:5173
# Assets served to http://localhost:8000
```

**Browser**: Visit `http://localhost:8000`

### Environment Configuration

Create `.env` file with these key variables:

```ini
# Application
APP_NAME="APISPI"
APP_ENV=local
APP_KEY=base64:XXXXX              # Generated via php artisan key:generate
APP_DEBUG=true                    # Set to false in production
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apispi_dev
DB_USERNAME=root
DB_PASSWORD=

# Session (REQUIRED)
SESSION_DRIVER=database

# AI Chatbot
ANTHROPIC_API_KEY=sk-ant-...      # From Anthropic console
ANTHROPIC_MODEL=claude-sonnet-4-5

# Optional: Email
MAIL_MAILER=log                   # Use 'log' driver to see emails in console
```

---

## Development Commands

### Laravel/PHP Commands

```bash
# Start development server (on http://localhost:8000)
php artisan serve

# Database
php artisan migrate                    # Run pending migrations
php artisan migrate:fresh              # Reset DB completely + migrate
php artisan migrate:refresh            # Rollback all + migrate
php artisan db:seed                    # Run all seeders
php artisan db:seed --class=AgentSeeder  # Run specific seeder

# Code Formatting (Laravel Pint)
./vendor/bin/pint                      # Format all code
./vendor/bin/pint --check              # Check without changing
./vendor/bin/pint app/Models           # Format specific directory

# Clear Caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear             # Clear all optimization files

# Interactive Shell (Tinker REPL)
php artisan tinker
  > $user = User::first()
  > $user->is_admin = true
  > $user->save()
  > $agents = Agent::active()->get()
  > exit
```

### Node/Frontend Commands

```bash
# Install dependencies
npm install

# Development server with HMR
npm run dev                        # Vite dev server (http://localhost:5173)

# Production build
npm run build                      # Build to public_html/build/

# Preview production build locally
npm run preview                    # Preview without deploying

# Check build size
npm run build -- --analyze         # Show bundle analysis
```

---

## Testing

### Test Structure
```
tests/
├── TestCase.php                   # Base test class with helpers
├── Feature/                       # Feature/integration tests
│   ├── AuthTest.php               # Authentication flow tests
│   ├── AgentTest.php              # Agent CRUD tests
│   ├── SubscriptionTest.php
│   └── ...
└── Unit/                          # Unit tests
    ├── Models/
    │   ├── UserTest.php
    │   └── AgentTest.php
    ├── Services/
    │   └── OAuthServiceTest.php
    └── ...
```

### Running Tests

```bash
# All tests
composer test                      # Runs phpunit + Pest

# Specific test file
php artisan test tests/Feature/AuthTest.php

# Specific test method
php artisan test tests/Feature/AuthTest.php --filter=test_user_can_register

# With code coverage
php artisan test --coverage        # Generate coverage report
php artisan test --coverage-html=coverage  # HTML report in coverage/ dir

# Stop on first failure
php artisan test --stop-on-failure

# Verbose output
php artisan test --verbose

# Parallel execution (faster)
php artisan test --parallel

# Watch mode (re-run on file changes)
php artisan test --watch
```

### Writing Tests

**Feature Test Example** (test real HTTP requests):
```php
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
            'password' => 'password123',
            'password_confirmation' => 'password123',
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

    public function test_user_cannot_access_admin_panel()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/');

        $response->assertForbidden();
    }
}
```

**Unit Test Example** (test logic in isolation):
```php
namespace Tests\Unit;

use App\Models\Agent;
use Tests\TestCase;

class AgentModelTest extends TestCase
{
    public function test_active_scope_filters_inactive_agents()
    {
        Agent::factory()->create(['is_active' => true]);
        Agent::factory()->create(['is_active' => false]);

        $agents = Agent::active()->get();

        $this->assertCount(1, $agents);
    }
}
```

### Test Configuration
**File**: `phpunit.xml`
- Defines test suites (Unit, Feature)
- Database: uses separate in-memory SQLite or test database
- Coverage: configures which files to include in coverage reports

---

## Debugging

### Debug Bar (Laravel Debugbar)
Optional: Install `laravel/debugbar` for development profiling:
```bash
composer require barryvdh/laravel-debugbar --dev
```

Shows: queries, timings, exceptions, request/response data

### Log Files
```bash
# View recent logs (last 50 lines)
tail -50 storage/logs/laravel.log

# Follow log updates in real-time
tail -f storage/logs/laravel.log

# Clear logs
php artisan logs:clear
```

### Var Dump
```php
// In code
dd($variable);      // Die and dump
dump($variable);    // Dump (continue)

// In blade
@dd($variable)
@dump($variable)
```

### Local Development Tools
- **VS Code Extensions**: Laravel, PHP Intelephense, Volar
- **Browser DevTools**: Vue.js DevTools, React DevTools
- **Database GUI**: MySQL Workbench, Sequel Pro (Mac), DBeaver (multi-platform)

---

## Common Development Tasks

### Creating a New Route
1. Add to `routes/web.php`
2. Create controller: `php artisan make:controller MyController`
3. Add controller method
4. Create/update view template
5. Test route locally

### Creating a New Model
```bash
php artisan make:model MyModel -m   # Create model + migration
php artisan make:model MyModel -c   # Create model + controller
```

### Creating a New Migration
```bash
php artisan make:migration create_my_table
php artisan make:migration add_field_to_my_table

# Then edit migration file and:
php artisan migrate
```

### Creating a New Vue Component
1. Create file: `resources/js/components/MyComponent.vue`
2. Import in entry point: `import MyComponent from '@/components/MyComponent.vue'`
3. Register component
4. Use in template

---

## Code Standards

### PHP Code Style
- Laravel Pint (opinionated formatter)
- PSR-12 Standard
- Run before commit: `./vendor/bin/pint`

### JavaScript/Vue
- ESLint (if configured)
- Prettier (if configured)
- Vue 3 Composition API recommended
- `<script setup>` syntax preferred

### Database
- Use migrations for all schema changes
- Never manually edit database structure
- Keep migrations small and focused

---

## Performance Optimization in Development

### Query Optimization
- Use eager loading: `Agent::with('skills')->get()`
- Avoid N+1 queries: check Laravel Debugbar
- Use `select()` to fetch only needed columns

### Frontend Performance
- Build assets: `npm run build` before commit
- Check bundle size: `npm run build -- --analyze`
- Use code splitting for large components

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| `SQLSTATE[HY000]: General error: 1030` | Check database disk space, increase PHP memory limit |
| `npm ERR! code ERESOLVE` | `npm install --legacy-peer-deps` |
| `php artisan migrate` fails | Check `.env` DB credentials, ensure database exists |
| Vite not loading assets | Restart dev servers, clear browser cache |
| Vue component not rendering | Check console for errors, verify mount div exists |
| Session issues | Ensure `SESSION_DRIVER=database`, run migrations |

---

## Git Workflow

```bash
# Create feature branch
git checkout -b feature/my-feature

# Make changes, test locally
# ...

# Stage changes
git add .

# Commit with descriptive message
git commit -m "feat: add new feature"

# Push to remote
git push origin feature/my-feature

# Create pull request on GitHub
# After review & CI passes, merge to main
```

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
