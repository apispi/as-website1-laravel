# Frontend Architecture Specification

## Build & Deployment

### Build Tool: Vite
- **Config**: `vite.config.js`
- **Entry points**: `resources/js/*.js`
- **Public dir**: `public_html/` (not `public/`)
- **Build output**: `public_html/build/`

### Build Commands
```bash
npm run build     # Production build
npm run dev       # Dev server (HMR enabled)
npm run preview   # Preview production build
```

### Deployment Requirement
- **Built assets MUST be committed to git** (no Node.js on server)
- Workflow: `npm run build` → `git add public_html/build/` → `git push` → `git pull` on server

---

## Vue Entry Points & Mounting Pattern

Each Vue app mounts to a specific `#mount-id` div in Blade views.  
Props passed via `data-*` attributes as JSON strings.  
Components receive props via `defineProps()`.

| Entry Point | Mount ID | Routes | Purpose |
|---|---|---|---|
| `admin.js` | `#admin-app` | `data-page="page-name"` | All admin pages (dynamic routing) |
| `dashboard.js` | `#dashboard-app` | Static | User dashboard overview |
| `catalog.js` | `#catalog-app` | Static | Agent catalog browsing |
| `agents-list.js` | `#agents-list-app` | Static | User's subscriptions |
| `agent-detail.js` | `#agent-detail-app` | Static | Individual subscription/agent detail |
| `profile.js` | `#profile-app` | Static | User profile & password |
| `app.js` | — | — | Global styles & minimal JS |

---

## Admin Page Routing (admin.js)

### Pattern
The admin Vue app uses a `data-page` attribute to select which component to mount.

### Implementation
```js
// resources/js/admin.js
import DashboardPage from './components/admin/DashboardPage.vue'
import AgentsPage from './components/admin/AgentsPage.vue'
import SkillsPage from './components/admin/SkillsPage.vue'
// ... etc

const pages = {
  'dashboard': DashboardPage,
  'agents': AgentsPage,
  'skills': SkillsPage,
  // ... register all admin pages
}

export default pages
```

### Adding a New Admin Page
1. Create `resources/js/components/admin/YourPage.vue`
2. Import and register in `admin.js`'s `pages` map
3. Create Blade view: `resources/views/admin/your-page.blade.php`
4. Pass `data-page="your-page"` and `data-props="{...}"` JSON to mount div
5. Add route to `routes/web.php` with admin middleware

### Example: Admin Agents Page
**Blade** (`resources/views/admin/agents.blade.php`):
```blade
<div id="admin-app" data-page="agents" data-props='@json($props)'></div>
<script type="module" src="{{ asset('build/admin.js') }}"></script>
```

**JS** (`resources/js/admin.js`):
```js
import AgentsPage from './components/admin/AgentsPage.vue'
const pages = { agents: AgentsPage, ... }
```

**Component** (`resources/js/components/admin/AgentsPage.vue`):
```vue
<template>
  <!-- UI for managing agents -->
</template>

<script setup>
defineProps({
  agents: Array,
  // ... other props from Blade
})
</script>
```

---

## Global Styles

### app.css & app.js
- `resources/css/app.css` — Global Tailwind + custom styles
- `resources/js/app.js` — Minimal global JS (rarely used)
- Compiled/imported by all Vue entry points

### Tailwind Configuration
- `tailwind.config.js` — Defines theme, colors, plugins
- Color schemes:
  - **Admin UI**: Red/Rose palette
  - **User Dashboard**: Amber/Gold palette

---

## Static Chatbot (Not Vite-compiled)

### Files
- **Location**: `public_html/js/chatbot.js` (static file, not Vite output)
- **NLP Bundle**: `public_html/js/nlp.min.js` (node-nlp v3.10.2 browser bundle)

### Behavior
1. On page load, `/contact` page includes chatbot.js
2. Chatbot lazy-loads `nlp.min.js` on first user interaction
3. In-browser NLP classifier trained on 16 intents
4. Classifies user messages against intent patterns
5. Routes to appropriate responses or API call

### Deployment
- Changes to chatbot.js or nlp.min.js committed directly to git (not Vite output)
- No build step required for chatbot changes

---

## Props Pattern

### Passing Props from Blade to Vue
```blade
{{-- In Blade view --}}
<div id="agent-detail-app" 
     data-page="show" 
     data-props='@json(["agent" => $agent, "user" => auth()->user()])'>
</div>
<script type="module" src="{{ asset('build/agent-detail.js') }}"></script>
```

```js
// In Vue component
<script setup>
const props = defineProps({
  agent: Object,
  user: Object
})

console.log(props.agent.name)
</script>
```

### Why via data-* Attributes?
- Blade views are server-rendered once
- Props serialized as JSON in HTML
- Vue app hydrates with props on client
- No runtime server communication for initial page load

---

## Asset Linking in Blade

### Vite Asset Helper
```blade
{{-- Images, fonts, etc. in resources/images/ --}}
<img src="{{ asset('build/my-image.png') }}" />

{{-- JavaScript entry points --}}
<script type="module" src="{{ asset('build/admin.js') }}"></script>

{{-- Stylesheet (optional if bundled) --}}
<link rel="stylesheet" href="{{ asset('build/app.css') }}" />
```

### Development vs Production
- **Dev**: Vite dev server serves `http://localhost:5173/...`
- **Prod**: Files served from `public_html/build/...`

---

## Vue 3 Standards

### Component Structure
```vue
<template>
  <!-- Single root element required -->
</template>

<script setup>
// Composition API preferred
import { ref, computed } from 'vue'

const props = defineProps({ /* ... */ })
const emit = defineEmits(['event-name'])

const state = ref(null)
const derived = computed(() => state.value?.transform())
</script>

<style scoped>
/* Component styles, scoped to prevent leaks */
</style>
```

### Composition API
- Use `<script setup>` syntax (modern, concise)
- `ref()` for reactive state
- `computed()` for derived values
- `watch()` for side effects
- `onMounted()`, `onUnmounted()` for lifecycle

### Avoid
- Options API (older style)
- Global `window` state (use Pinia if needed)
- Direct DOM manipulation (use refs in last resort)

---

## Component Organization

```
resources/js/
├── admin.js                    # Entry: Admin pages
├── dashboard.js                # Entry: User dashboard
├── catalog.js                  # Entry: Agent catalog
├── agents-list.js              # Entry: User agents list
├── agent-detail.js             # Entry: Agent detail
├── profile.js                  # Entry: User profile
├── app.js                      # Global entry
├── components/
│   ├── admin/
│   │   ├── DashboardPage.vue
│   │   ├── AgentsPage.vue
│   │   ├── SkillsPage.vue
│   │   └── ... other admin pages
│   ├── shared/
│   │   ├── Header.vue
│   │   ├── Sidebar.vue
│   │   └── ... reusable components
│   └── ... page-specific components
└── composables/
    └── ... reusable logic hooks
```
