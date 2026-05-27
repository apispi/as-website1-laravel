# Frontend Architecture Specification

## Build & Deployment

### Build Tool: Vite
- **Config**: `vite.config.js`
- **Entry points**: `resources/js/{admin,dashboard,catalog,agents-list,agent-detail,profile,app}.js`
- **Public directory**: `public_html/` (not `public/`)
- **Build output**: `public_html/build/` — committed to git
- **Dev server**: `http://localhost:5173` (HMR enabled)

### Build Commands
```bash
npm run build              # Production build to public_html/build/
npm run dev                # Start Vite dev server with HMR
npm run preview            # Preview production build locally
```

### Critical Deployment Requirement
- **NO Node.js on production server**
- **Built assets MUST be committed to git**
- Workflow:
  1. `npm run build` — build locally
  2. `git add public_html/build/`
  3. `git commit -m "Build assets"`
  4. `git push`
  5. Server: `git pull` (assets auto-served)

---

## Vue Entry Points & Application Architecture

Each Vue app is a separate Vite entry point, mounts to a specific Blade div, receives props via `data-*` JSON attributes.

| Entry Point | Mount Div | Routing | Purpose |
|---|---|---|---|
| `admin.js` | `#admin-app` | `data-page="name"` | Dynamic admin pages (single-page routing) |
| `dashboard.js` | `#dashboard-app` | Static | User dashboard overview (subscriptions, stats) |
| `catalog.js` | `#catalog-app` | Static | Agent marketplace browsing |
| `agents-list.js` | `#agents-list-app` | Static | User's subscriptions list |
| `agent-detail.js` | `#agent-detail-app` | Static | Individual subscription detail page |
| `profile.js` | `#profile-app` | Static | User profile & password change |
| `app.js` | — | — | Global styles & minimal JS (entry for CSS) |

---

## Admin Page Routing (admin.js)

Admin uses **client-side routing** via `data-page` attribute to dynamically load components.

### How It Works
```blade
<!-- Blade view: resources/views/admin/agents.blade.php -->
<div id="admin-app" data-page="agents" data-props='@json($props)'></div>
<script type="module" src="{{ asset('build/admin.js') }}"></script>
```

```js
// resources/js/admin.js
import DashboardPage from './components/admin/DashboardPage.vue'
import AgentsPage from './components/admin/AgentsPage.vue'
import SkillsPage from './components/admin/SkillsPage.vue'
// ... import all admin pages

const pages = {
  'dashboard': DashboardPage,
  'agents': AgentsPage,
  'skills': SkillsPage,
  // ... register all admin pages
}

export default pages

// In mount logic:
const page = document.querySelector('#admin-app')?.dataset.page
const Component = pages[page]
// Mount Component to #admin-app
```

### Adding a New Admin Page (5 Steps)

1. **Create Vue component**: `resources/js/components/admin/YourPage.vue`
   ```vue
   <template>...</template>
   <script setup>
   defineProps({ /* data from Blade */ })
   </script>
   ```

2. **Register in admin.js**: Import and add to `pages` map
   ```js
   import YourPage from './components/admin/YourPage.vue'
   const pages = { ..., 'your-page': YourPage }
   ```

3. **Create Blade view**: `resources/views/admin/your-page.blade.php`
   ```blade
   <div id="admin-app" 
        data-page="your-page" 
        data-props='@json(["data" => $data])'></div>
   <script type="module" src="{{ asset('build/admin.js') }}"></script>
   ```

4. **Add Laravel route**: `routes/web.php`
   ```php
   Route::get('/admin/your-page', [AdminYourController::class, 'index'])
        ->middleware(['auth', 'admin'])
        ->name('admin.your-page.index');
   ```

5. **Controller**: Pass props to Blade view
   ```php
   public function index() {
       return view('admin.your-page', ['data' => ...]);
   }
   ```

---

## Props Pattern: Blade → Vue

### Mechanism
Props are **serialized as JSON in HTML** and **hydrated on client**:

```blade
{{-- resources/views/dashboard/agents.blade.php --}}
<div id="agents-list-app" data-props='@json([
    "agents" => $agents,
    "user" => auth()->user(),
    "stats" => $stats,
])'></div>
<script type="module" src="{{ asset('build/agents-list.js') }}"></script>
```

```js
// resources/js/agents-list.js
import App from './components/AgentsList.vue'

const el = document.getElementById('agents-list-app')
const props = JSON.parse(el.dataset.props || '{}')

createApp(App, props).mount(el)
```

```vue
<!-- resources/js/components/AgentsList.vue -->
<script setup>
defineProps({
  agents: Array,
  user: Object,
  stats: Object,
})
</script>

<template>
  <div>
    <h1>My Agents ({{ agents.length }})</h1>
    <!-- Use props.agents, props.user, etc. -->
  </div>
</template>
```

### Why This Pattern?
- Server renders initial HTML with data
- Vue hydrates with pre-computed data
- No `loading` spinners for initial page
- SEO-friendly (content in HTML)
- No runtime API calls to fetch initial data

---

## Static Chatbot (NOT Vite-compiled)

Chatbot is a **static JavaScript file**, not managed by Vite.

### Files
- **Chatbot JS**: `public_html/js/chatbot.js`
- **NLP Bundle**: `public_html/js/nlp.min.js` (node-nlp v3.10.2)

### Behavior
1. `/contact` page loads `chatbot.js` unconditionally
2. On first user interaction, lazy-loads `nlp.min.js`
3. NLP classifier trained on 16 intent patterns (in-browser)
4. User messages classified against intents
5. Matched intent routed to appropriate response or Anthropic API

### Deployment
- Edit directly in `public_html/js/`
- No build step required
- Commit changes to git (static assets, not Vite output)

---

## Global Styles & Configuration

### Tailwind CSS
- **Config**: `tailwind.config.js`
- **Main CSS**: `resources/css/app.css` (includes Tailwind + custom styles)
- **Theme**:
  - Admin UI: Red/Rose palette (`text-red-500`, `bg-rose-100`)
  - User Dashboard: Amber/Gold palette (`text-amber-600`, `bg-amber-50`)

### CSS Organization
```
resources/css/
├── app.css              # Main entry: @tailwind directives + globals
└── ... component-scoped styles (in <style scoped> in .vue)
```

### Custom Utilities
Custom Tailwind utilities defined in `tailwind.config.js`:
```js
theme: {
  extend: {
    colors: { /* custom colors */ },
    spacing: { /* custom spacing */ },
  }
}
```

---

## Asset Linking in Blade

### Public Assets via `asset()` Helper
```blade
{{-- Vite-compiled assets --}}
<script type="module" src="{{ asset('build/admin.js') }}"></script>
<link rel="stylesheet" href="{{ asset('build/app.css') }}" />

{{-- Static images/fonts in public_html/ --}}
<img src="{{ asset('images/logo.png') }}" alt="Logo" />
<img src="{{ asset('build/my-asset-hash.png') }}" alt="Built asset" />
```

### Image Assets
- Imported in JS/Vue: `import img from '@/images/logo.png'`
- Static in `public_html/`: served directly as `/images/...`
- Via Vite build: hashed for cache-busting

---

## Vue 3 Component Standards

### Component Structure
```vue
<template>
  <!-- Single root element (or Fragment in Vue 3.3+) -->
  <div>
    <h1>{{ title }}</h1>
    <button @click="handleClick">Click me</button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Props from parent or Blade
const props = defineProps({
  title: String,
  items: Array,
})

// Emitted events
const emit = defineEmits(['item-selected'])

// State
const count = ref(0)

// Computed
const doubled = computed(() => count.value * 2)

// Methods
const handleClick = () => {
  count.value++
  emit('item-selected', count.value)
}
</script>

<style scoped>
/* Scoped styles only affect this component */
h1 { color: blue; }
</style>
```

### Composition API (Preferred)
- Use `<script setup>` syntax (concise, modern)
- `ref()` for reactive values
- `computed()` for derived state
- `watch()` for side effects
- `onMounted()`, `onUnmounted()` for lifecycle
- Avoid: Options API (older style)

### Avoid Anti-patterns
- Direct `window` mutation (use refs, composables)
- DOM manipulation with `querySelector` (use `ref` attribute)
- Global mutable state (use Pinia if multi-component state needed)

---

## Component Organization

```
resources/js/
├── admin.js                    # Admin entry point
├── dashboard.js                # Dashboard entry point
├── catalog.js                  # Catalog entry point
├── agents-list.js              # Agents list entry point
├── agent-detail.js             # Agent detail entry point
├── profile.js                  # Profile entry point
├── app.js                      # Global styles entry
├── components/
│   ├── admin/                  # Admin-specific components
│   │   ├── DashboardPage.vue   # Data: admin.dashboard
│   │   ├── AgentsPage.vue      # Data: admin.agents.index
│   │   ├── SkillsPage.vue      # Data: admin.skills.index
│   │   ├── ConnectorsPage.vue  # Data: admin.connectors.index
│   │   ├── UsersPage.vue       # Data: admin.users.index
│   │   ├── TrainingsPage.vue   # Data: admin.trainings.index
│   │   └── ...
│   ├── shared/                 # Reusable components
│   │   ├── Header.vue
│   │   ├── Sidebar.vue
│   │   ├── Modal.vue
│   │   ├── Form.vue
│   │   └── ... shared UI
│   ├── AgentsList.vue          # agents-list.js
│   ├── AgentDetail.vue         # agent-detail.js
│   ├── Dashboard.vue           # dashboard.js
│   ├── Profile.vue             # profile.js
│   ├── Catalog.vue             # catalog.js
│   └── ... other page components
└── composables/
    ├── useAuth.js              # Auth state/methods
    ├── useApi.js               # API call wrapper
    └── ... reusable logic
```

---

## Development Workflow

### Local Development
```bash
# Terminal 1: Start Vite dev server (HMR)
npm run dev
# Server runs on http://localhost:5173

# Terminal 2: Start Laravel dev server
php artisan serve
# Visits http://localhost:8000, assets served from Vite dev server

# Browser: http://localhost:8000
# Hot Module Replacement (HMR) enabled
# Changes to .vue/.js/.css auto-reload in browser
```

### Before Commit
```bash
# Build for production
npm run build

# Check that public_html/build/ has new files
git status

# Commit built assets
git add public_html/build/
git commit -m "Build frontend assets"
```

---

## Performance Optimization

### Code Splitting
Vite automatically code-splits at entry points:
- `admin.js` — all admin pages in one chunk (or lazy-loaded)
- `dashboard.js` — dashboard-specific code
- `agents-list.js` — agents list-specific code
- etc.

### Image Optimization
- Use modern formats (WebP, AVIF)
- Lazy-load images with `<img loading="lazy">`
- Optimize before committing

### CSS Purging
Tailwind automatically purges unused CSS in production builds (via `content` in `tailwind.config.js`).

---

## Browser Support
- Modern browsers: Chrome 60+, Firefox 55+, Safari 12+, Edge 79+
- No IE11 support (ES6+ features used)

