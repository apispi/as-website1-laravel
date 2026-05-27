<template>
  <div class="db-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <!-- Overlay (mobile) -->
    <div class="db-overlay" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside class="db-sidebar">
      <div class="db-sidebar-header">
        <a href="/" class="db-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="db-logo-icon">
            <defs>
              <linearGradient id="dlg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dlg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dlg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="db-sidebar-close" @click="sidebarOpen = false" aria-label="Close menu">✕</button>
      </div>

      <nav class="db-nav">
        <span class="db-nav-label">Workspace</span>
        <a href="/dashboard" class="db-nav-link active">
          <span class="db-nav-icon">⬡</span> Home
        </a>
        <a href="/dashboard/agents" class="db-nav-link">
          <span class="db-nav-icon">◈</span> My Agents
        </a>
        <a href="/dashboard/connectors" class="db-nav-link">
          <span class="db-nav-icon">⬡</span> My Connectors
        </a>
        <a href="/dashboard/catalog" class="db-nav-link">
          <span class="db-nav-icon">◎</span> Catalog
        </a>
        <a href="/dashboard/aria"   class="db-nav-link"><span class="db-nav-icon">◇</span> Aria</a>
        <a href="/training" class="db-nav-link">
          <span class="db-nav-icon">◷</span> Training
        </a>

        <span class="db-nav-label">Account</span>
        <a href="/contact" class="db-nav-link">
          <span class="db-nav-icon">◉</span> Support
        </a>

        <template v-if="user.is_admin">
          <span class="db-nav-label">Administration</span>
          <a href="/admin" class="db-nav-link db-nav-admin">
            <span class="db-nav-icon">⬡</span> Admin Panel
          </a>
        </template>
      </nav>

      <div class="db-sidebar-footer">
        <a href="/dashboard/profile" class="db-user-row">
          <div class="db-avatar">{{ initial }}</div>
          <div class="db-user-text">
            <div class="db-user-name">{{ user.name }}</div>
            <div class="db-user-email">{{ user.email }}</div>
          </div>
        </a>
        <form method="POST" :action="logoutUrl">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="db-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="db-main">
      <!-- Top bar -->
      <header class="db-topbar">
        <button class="db-menu-btn" @click="sidebarOpen = true" aria-label="Open menu">
          <span></span><span></span><span></span>
        </button>
        <a href="/" class="db-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="db-logo-icon">
            <use href="#dlg-ref"/>
            <defs>
              <linearGradient id="dlg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dlg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dlg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="db-topbar-right">
          <div class="db-avatar db-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <!-- Content -->
      <main class="db-content">

        <!-- Welcome -->
        <div class="db-welcome">
          <h1 class="db-welcome-title">Welcome back, <span class="db-highlight">{{ firstName }}</span></h1>
          <p class="db-welcome-sub">Here's what's happening with your account today.</p>
        </div>

        <!-- Stats -->
        <div class="db-stat-grid">
          <div v-for="stat in stats" :key="stat.label" class="db-stat-card">
            <div class="db-stat-icon">{{ stat.icon }}</div>
            <div class="db-stat-value">{{ stat.value }}</div>
            <div class="db-stat-label">{{ stat.label }}</div>
          </div>
        </div>

        <!-- Quick Actions -->
        <section class="db-section">
          <h2 class="db-section-title">Quick Actions</h2>
          <div class="db-action-grid">
            <a v-for="action in actions" :key="action.label" :href="action.href" class="db-action-card">
              <div class="db-action-icon">{{ action.icon }}</div>
              <div class="db-action-text">
                <div class="db-action-title">{{ action.label }}</div>
                <div class="db-action-desc">{{ action.desc }}</div>
              </div>
              <span class="db-action-arrow">→</span>
            </a>
          </div>
        </section>

        <!-- Getting Started -->
        <section class="db-section">
          <h2 class="db-section-title">Getting Started</h2>
          <div class="db-steps">
            <div v-for="(step, i) in gettingStarted" :key="i" class="db-step" :class="{ done: step.done }">
              <div class="db-step-check">{{ step.done ? '✓' : (i + 1) }}</div>
              <div class="db-step-body">
                <div class="db-step-title">{{ step.title }}</div>
                <div class="db-step-desc">{{ step.desc }}</div>
              </div>
              <a v-if="!step.done" :href="step.href" class="db-step-cta">Start →</a>
            </div>
          </div>
        </section>

      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user:          { type: Object, default: () => ({}) },
  csrfToken:     { type: String, default: '' },
  subscriptions: { type: Array,  default: () => [] },
});

const sidebarOpen = ref(false);
const logoutUrl = '/logout';

const initial = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());
const firstName = computed(() => (props.user.name || 'there').split(' ')[0]);
const stats = computed(() => [
  { icon: '◈', value: props.subscriptions.length, label: 'Active Agents' },
  { icon: '⬡', value: '0', label: 'API Calls' },
  { icon: '◷', value: '0', label: 'Pipelines Run' },
]);

const actions = [
  { icon: '◎', label: 'Agent Catalog', desc: 'Explore and deploy AI agents', href: '/dashboard/catalog' },
  { icon: '◷', label: 'Training', desc: 'Learn to build better AI workflows', href: '/training' },
  { icon: '◉', label: 'Support', desc: 'Get help from our team', href: '/contact' },
];

const gettingStarted = computed(() => [
  { title: 'Create your account', desc: 'You\'re in! Account is set up and ready.', done: true, href: '#' },
  { title: 'Explore our AI agents', desc: 'Browse agents designed for your workflows.', done: props.subscriptions.length > 0, href: '/agents' },
  { title: 'Run your first pipeline', desc: 'Chain AI steps to automate complex tasks.', done: false, href: '/training' },
  { title: 'Integrate via API', desc: 'Connect agents to your own applications.', done: false, href: '/contact' },
]);
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* Shell */
.db-shell {
  display: flex;
  min-height: 100vh;
  background: #0a0805;
  color: #e5e7eb;
  font-family: 'Instrument Sans', system-ui, sans-serif;
}

/* Overlay */
.db-overlay {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.65);
  z-index: 90;
}
.sidebar-open .db-overlay { display: block; }

/* Sidebar */
.db-sidebar {
  width: 240px;
  background: rgba(14, 11, 6, 0.98);
  border-right: 1px solid rgba(217, 119, 6, 0.15);
  position: fixed; top: 0; left: 0;
  height: 100vh;
  display: flex; flex-direction: column;
  z-index: 100;
  overflow-y: auto;
  transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
}
.db-sidebar-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.25rem 1rem 1rem;
  border-bottom: 1px solid rgba(217,119,6,0.1);
  flex-shrink: 0;
}
.db-logo {
  display: flex; align-items: center; gap: 0.5rem;
  text-decoration: none; color: #e5e7eb;
  font-size: 1.15rem; font-weight: 700;
}
.db-logo-icon { width: 26px; height: 26px; }
.db-sidebar-close {
  display: none;
  background: none; border: none; cursor: pointer;
  color: #6b7280; font-size: 1.1rem; padding: 0.25rem;
}

.db-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.db-nav-label {
  font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.1em; color: #4b5563;
  padding: 0.75rem 0.5rem 0.25rem;
}
.db-nav-link {
  display: flex; align-items: center; gap: 0.75rem;
  padding: 0.625rem 0.75rem;
  border-radius: 0.5rem;
  text-decoration: none;
  color: #9ca3af; font-size: 0.875rem;
  transition: all 0.18s;
  min-height: 44px;
}
.db-nav-link:hover { background: rgba(217,119,6,0.1); color: #FCD34D; }
.db-nav-link.active { background: rgba(217,119,6,0.15); color: #FCD34D; font-weight: 600; }
.db-nav-admin { color: #fca5a5 !important; }
.db-nav-admin:hover { background: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
.db-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.db-sidebar-footer {
  padding: 1rem 0.75rem;
  border-top: 1px solid rgba(217,119,6,0.1);
  flex-shrink: 0;
}
.db-user-row {
  display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;
  text-decoration: none; color: inherit;
  padding: 0.4rem 0.5rem; border-radius: 0.5rem; transition: background 0.15s;
}
.db-user-row:hover { background: rgba(217,119,6,0.08); }
.db-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #D97706, #FCD34D);
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 0.85rem; color: #0a0805;
  flex-shrink: 0;
}
.db-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.db-user-text { min-width: 0; }
.db-user-name { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.db-user-email { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.db-signout {
  display: flex; align-items: center; gap: 0.5rem;
  width: 100%; padding: 0.5rem 0.75rem;
  background: none; border: none; cursor: pointer;
  color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem;
  transition: all 0.18s; font-family: inherit; text-align: left;
  min-height: 44px;
}
.db-signout:hover { background: rgba(255,59,48,0.1); color: #ff3b30; }

/* Main */
.db-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

/* Topbar (mobile only visible) */
.db-topbar {
  display: none;
  position: sticky; top: 0; z-index: 50;
  background: rgba(14,11,6,0.98);
  border-bottom: 1px solid rgba(217,119,6,0.12);
  padding: 0 1rem;
  height: 56px;
  align-items: center; justify-content: space-between;
}
.db-topbar-logo {
  display: flex; align-items: center; gap: 0.5rem;
  text-decoration: none; color: #e5e7eb;
  font-size: 1.05rem; font-weight: 700;
}
.db-menu-btn {
  display: flex; flex-direction: column; justify-content: space-between;
  width: 24px; height: 18px;
  background: none; border: none; cursor: pointer; padding: 0;
}
.db-menu-btn span {
  display: block; width: 100%; height: 2px;
  background: #FCD34D; border-radius: 2px;
  transition: all 0.2s;
}
.db-topbar-right { display: flex; align-items: center; }

/* Content */
.db-content { flex: 1; padding: 2rem; max-width: 900px; width: 100%; }

.db-welcome { margin-bottom: 2rem; }
.db-welcome-title { font-size: 1.75rem; font-weight: 700; line-height: 1.2; margin-bottom: 0.4rem; }
.db-welcome-sub { color: #6b7280; font-size: 0.95rem; }
.db-highlight { background: linear-gradient(135deg, #FCD34D, #D97706); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

/* Stats */
.db-stat-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}
.db-stat-card {
  background: rgba(28,24,16,0.7);
  border: 1px solid rgba(217,119,6,0.12);
  border-radius: 1rem;
  padding: 1.25rem;
  text-align: center;
  transition: border-color 0.2s, transform 0.2s;
}
.db-stat-card:hover { border-color: rgba(217,119,6,0.3); transform: translateY(-2px); }
.db-stat-icon { font-size: 1.4rem; margin-bottom: 0.6rem; opacity: 0.7; }
.db-stat-value { font-size: 2rem; font-weight: 700; color: #FCD34D; line-height: 1; margin-bottom: 0.3rem; }
.db-stat-label { font-size: 0.8rem; color: #6b7280; }

/* Sections */
.db-section { margin-bottom: 2rem; }
.db-section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.db-section-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; }
.db-section-link { font-size: 0.82rem; color: #D97706; text-decoration: none; }
.db-section-link:hover { text-decoration: underline; }

/* Actions */
.db-action-grid { display: flex; flex-direction: column; gap: 0.75rem; }
.db-action-card {
  display: flex; align-items: center; gap: 1rem;
  background: rgba(28,24,16,0.7);
  border: 1px solid rgba(217,119,6,0.12);
  border-radius: 1rem;
  padding: 1rem 1.25rem;
  text-decoration: none;
  color: inherit;
  transition: border-color 0.2s, transform 0.2s, background 0.2s;
  min-height: 72px;
}
.db-action-card:hover { border-color: rgba(217,119,6,0.35); background: rgba(217,119,6,0.05); transform: translateY(-1px); }
.db-action-icon { font-size: 1.4rem; width: 40px; text-align: center; flex-shrink: 0; opacity: 0.8; }
.db-action-text { flex: 1; min-width: 0; }
.db-action-title { font-size: 0.925rem; font-weight: 600; color: #e5e7eb; margin-bottom: 0.2rem; }
.db-action-desc { font-size: 0.8rem; color: #6b7280; }
.db-action-arrow { color: #D97706; font-size: 1rem; flex-shrink: 0; transition: transform 0.2s; }
.db-action-card:hover .db-action-arrow { transform: translateX(4px); }

/* Steps */
.db-steps { display: flex; flex-direction: column; gap: 0.75rem; }
.db-step {
  display: flex; align-items: center; gap: 1rem;
  background: rgba(28,24,16,0.7);
  border: 1px solid rgba(217,119,6,0.1);
  border-radius: 1rem;
  padding: 1rem 1.25rem;
  min-height: 72px;
}
.db-step.done { opacity: 0.6; }
.db-step-check {
  width: 32px; height: 32px; border-radius: 50%;
  background: rgba(217,119,6,0.15);
  border: 1.5px solid rgba(217,119,6,0.3);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.8rem; font-weight: 700; color: #FCD34D;
  flex-shrink: 0;
}
.db-step.done .db-step-check { background: rgba(0,217,126,0.12); border-color: rgba(0,217,126,0.3); color: #00d97e; }
.db-step-body { flex: 1; min-width: 0; }
.db-step-title { font-size: 0.9rem; font-weight: 600; color: #e5e7eb; margin-bottom: 0.2rem; }
.db-step-desc { font-size: 0.8rem; color: #6b7280; }
.db-step-cta {
  font-size: 0.82rem; font-weight: 600; color: #D97706;
  text-decoration: none; flex-shrink: 0;
  padding: 0.4rem 0.75rem; border-radius: 0.5rem;
  border: 1px solid rgba(217,119,6,0.25);
  transition: all 0.18s;
  white-space: nowrap;
}
.db-step-cta:hover { background: rgba(217,119,6,0.12); border-color: rgba(217,119,6,0.5); }

/* Mobile */
@media (max-width: 768px) {
  .db-sidebar {
    transform: translateX(-100%);
  }
  .sidebar-open .db-sidebar {
    transform: translateX(0);
  }
  .db-sidebar-close { display: block; }
  .db-main { margin-left: 0; }
  .db-topbar { display: flex; }
  .db-content { padding: 1.25rem; }
  .db-welcome-title { font-size: 1.4rem; }
  .db-stat-grid { grid-template-columns: repeat(3, 1fr); gap: 0.625rem; }
  .db-stat-card { padding: 0.875rem 0.625rem; }
  .db-stat-value { font-size: 1.5rem; }
  .db-stat-label { font-size: 0.72rem; }
}

@media (max-width: 480px) {
  .db-stat-grid { grid-template-columns: repeat(3, 1fr); }
  .db-stat-card { padding: 0.75rem 0.375rem; }
  .db-stat-icon { font-size: 1.1rem; margin-bottom: 0.4rem; }
  .db-stat-value { font-size: 1.3rem; }
}
</style>
