# Project Rebuild Guide

This directory contains complete specification files for rebuilding the APISPI project.

## Quick Navigation

| Spec File | Purpose |
|---|---|
| [01-PROJECT-OVERVIEW.md](01-PROJECT-OVERVIEW.md) | High-level architecture, tech stack, core domains |
| [02-DATABASE-SCHEMA.md](02-DATABASE-SCHEMA.md) | Complete database schema with SQL & relationships |
| [03-MODELS.md](03-MODELS.md) | Eloquent models, attributes, relationships, scopes |
| [04-CONTROLLERS.md](04-CONTROLLERS.md) | All controllers, routes they handle, expected behavior |
| [05-ROUTES.md](05-ROUTES.md) | Complete route definitions, middleware, naming conventions |
| [06-FRONTEND.md](06-FRONTEND.md) | Vue 3 entry points, component structure, Vite config |
| [07-AUTHENTICATION.md](07-AUTHENTICATION.md) | Auth flow, admin authorization, middleware, CSRF |
| [08-SERVICES.md](08-SERVICES.md) | OAuthService, Anthropic API integration, service patterns |
| [09-DEVELOPMENT.md](09-DEVELOPMENT.md) | Setup, testing, code quality, common tasks |
| [10-DEPLOYMENT.md](10-DEPLOYMENT.md) | Production deployment, monitoring, security |

## Rebuild From Scratch (Step-by-Step)

### Phase 1: Project Setup (Day 1)
1. Create Laravel 11 project: `laravel new apispi`
2. Install dependencies: `composer install`
3. Follow [01-PROJECT-OVERVIEW.md](01-PROJECT-OVERVIEW.md) for tech stack setup
4. Configure Vite per [06-FRONTEND.md](06-FRONTEND.md)

### Phase 2: Database (Day 1-2)
1. Create migrations per [02-DATABASE-SCHEMA.md](02-DATABASE-SCHEMA.md)
2. Run: `php artisan migrate`
3. Create model factories for testing
4. Create seeders for initial data

### Phase 3: Models (Day 2)
1. Build models per [03-MODELS.md](03-MODELS.md)
2. Define relationships, scopes, casts
3. Test with tinker: `php artisan tinker`

### Phase 4: Authentication (Day 2-3)
1. Implement auth per [07-AUTHENTICATION.md](07-AUTHENTICATION.md)
2. Create `IsAdmin` middleware
3. Test login, register, password reset flows

### Phase 5: Controllers & Routes (Day 3-4)
1. Create controllers per [04-CONTROLLERS.md](04-CONTROLLERS.md)
2. Define routes per [05-ROUTES.md](05-ROUTES.md)
3. Test basic CRUD operations for all resources
4. Implement admin panel routes

### Phase 6: Services (Day 4)
1. Build OAuthService per [08-SERVICES.md](08-SERVICES.md)
2. Integrate Anthropic API for chatbot
3. Add ConnectorOAuthController logic

### Phase 7: Frontend (Day 5-7)
1. Setup Vue 3 per [06-FRONTEND.md](06-FRONTEND.md)
2. Create Vue entry points (admin.js, dashboard.js, etc.)
3. Build reusable components (navigation, forms, etc.)
4. Mount Vue apps in Blade templates
5. Implement each page's components
6. Test HMR with `npm run dev`

### Phase 8: Testing (Day 7-8)
1. Create test suite per [09-DEVELOPMENT.md](09-DEVELOPMENT.md)
2. Write feature tests for all major flows
3. Write unit tests for models
4. Achieve >80% code coverage (optional)

### Phase 9: Deployment (Day 8-9)
1. Prepare `.env` for production
2. Build assets: `npm run build`
3. Commit to git
4. Deploy to SiteGround per [10-DEPLOYMENT.md](10-DEPLOYMENT.md)
5. Verify live site

### Phase 10: Monitoring & Maintenance (Day 9+)
1. Setup log monitoring
2. Configure database backups
3. Schedule maintenance tasks
4. Monitor application health

## Implementation Notes

### Database-First Approach
- Start with schema (migrations) before models
- Use Laravel's blueprint syntax
- Test relationships early with tinker

### Blade-Centric Initially
- Build Blade views first (simpler to test)
- Add Vue incrementally (per feature)
- Don't over-engineer frontend too early

### Testing as You Go
- Write tests alongside features
- Use factories for test data
- Test auth/authorization thoroughly

### Environment-Specific Config
- Local: `.env.local` (git-ignored)
- Testing: `.env.testing`
- Production: Server-managed `.env`

## Common Gotchas

### 1. Session Driver
**Issue**: Sessions not persisting
**Fix**: Ensure `SESSION_DRIVER=database` in `.env` and run migrations

### 2. Vue Props Not Passing
**Issue**: Vue component receives undefined props
**Fix**: Ensure `data-props` JSON is properly escaped in Blade: `@json($data)`

### 3. CSRF Token Missing
**Issue**: POST requests fail with CSRF error
**Fix**: Include `@csrf` in all forms OR `X-CSRF-Token` header for AJAX

### 4. Built Assets Not Deployed
**Issue**: Old JavaScript served in production
**Fix**: Rebuild locally (`npm run build`), commit `public_html/build/`, push & deploy

### 5. Migrations Not Running
**Issue**: Database schema outdated
**Fix**: SSH to server and run `php artisan migrate` after git pull

### 6. Admin Middleware Errors
**Issue**: Non-admin users can access `/admin`
**Fix**: Check `IsAdmin` middleware exists and is registered in route group

## Testing the Rebuild

### Manual Testing Checklist
- [ ] User registration & login
- [ ] Password reset email flow
- [ ] Agent browsing & detail view
- [ ] Subscribe to agent
- [ ] Admin CRUD for agents/skills
- [ ] OAuth connector connect/disconnect
- [ ] Chatbot sends messages
- [ ] Activity logs recorded
- [ ] User dashboard shows subscriptions
- [ ] Profile update works
- [ ] Mobile responsive design

### Automated Testing
```bash
php artisan test
npm run build
```

## Updating from Old Version

If rebuilding from an existing codebase:

1. **Backup database**: `mysqldump ...`
2. **Review CLAUDE.md**: Check for any project-specific notes
3. **Check git log**: Understand recent changes
4. **Run tests**: Ensure current state is stable
5. **Incremental migrations**: Don't rewrite everything at once

## Configuration Files to Create

Essential config files beyond migrations:

- `.env` — Environment variables (git-ignored)
- `vite.config.js` — Frontend build configuration
- `tailwind.config.js` — Utility CSS framework
- `phpunit.xml` — Test suite configuration
- `pint.json` — Code style rules (optional)
- `.gitignore` — Git exclusions (provided by Laravel)

## Support & Debugging

### Check Documentation
- Laravel: https://laravel.com/docs
- Vue: https://vuejs.org/guide/
- Vite: https://vitejs.dev/
- Tailwind: https://tailwindcss.com/docs

### Common Commands
```bash
php artisan tinker           # Interactive REPL
php artisan migrate:status   # Check migrations
php artisan route:list       # View all routes
npm run build -- --analyze   # Analyze bundle size
```

### Debug Mode
- Set `APP_DEBUG=true` in `.env` for detailed error pages
- Check `storage/logs/laravel.log` for errors
- Use browser DevTools for frontend debugging

---

**Last Updated**: May 24, 2026
**Project**: APISPI (AI Agent Marketplace)
**Version**: 1.0
