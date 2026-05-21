<template>
  <div class="adm-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <div class="adm-overlay" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside class="adm-sidebar">
      <div class="adm-sidebar-header">
        <a href="/" class="adm-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="adm-logo-icon">
            <defs>
              <linearGradient id="alg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCA5A5"/>
                <stop offset="100%" stop-color="#EF4444"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#alg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#alg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <span class="adm-badge-label">Admin</span>
        <button class="adm-sidebar-close" @click="sidebarOpen = false" aria-label="Close">✕</button>
      </div>

      <nav class="adm-nav">
        <span class="adm-nav-label">Overview</span>
        <a href="/admin" class="adm-nav-link" :class="{ active: page === 'dashboard' }">
          <span class="adm-nav-icon">⬡</span> Admin
        </a>

        <span class="adm-nav-label">Manage</span>
        <a href="/admin/users" class="adm-nav-link" :class="{ active: page === 'users' }">
          <span class="adm-nav-icon">◈</span> Users
        </a>
        <a href="/admin/agents" class="adm-nav-link" :class="{ active: page === 'agents' }">
          <span class="adm-nav-icon">◎</span> Agent Catalog
        </a>
        <a href="/admin/subscriptions" class="adm-nav-link" :class="{ active: page === 'all-agents' }">
          <span class="adm-nav-icon">◈</span> Active Agents
        </a>
        <a href="/blog" class="adm-nav-link">
          <span class="adm-nav-icon">◷</span> Blog
        </a>

        <span class="adm-nav-label">Site</span>
        <a href="/dashboard" class="adm-nav-link">
          <span class="adm-nav-icon">⬡</span> User Dashboard
        </a>
      </nav>

      <div class="adm-sidebar-footer">
        <div class="adm-user-row">
          <div class="adm-avatar">{{ initial }}</div>
          <div class="adm-user-text">
            <div class="adm-user-name">{{ user.name }}</div>
            <div class="adm-user-role">Administrator</div>
          </div>
        </div>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="adm-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="adm-main">
      <header class="adm-topbar">
        <button class="adm-menu-btn" @click="sidebarOpen = true" aria-label="Open menu">
          <span></span><span></span><span></span>
        </button>
        <div class="adm-topbar-center">
          <a href="/" class="adm-topbar-logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" style="width:20px;height:20px;">
              <defs><linearGradient id="alg2" x1=".5" y1="0" x2=".5" y2="1"><stop offset="0%" stop-color="#FCA5A5"/><stop offset="100%" stop-color="#EF4444"/></linearGradient></defs>
              <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#alg2)"/>
              <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#alg2)"/>
            </svg>
            <span>ApiSpi</span>
          </a>
          <span class="adm-topbar-badge">Admin</span>
        </div>
        <div class="adm-avatar adm-avatar-sm">{{ initial }}</div>
      </header>

      <main class="adm-content">
        <slot />
      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user: { type: Object, default: () => ({}) },
  csrfToken: { type: String, default: '' },
  page: { type: String, default: 'dashboard' },
});

const sidebarOpen = ref(false);
const initial = computed(() => (props.user.name || 'A').charAt(0).toUpperCase());
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.adm-shell {
  display: flex; min-height: 100vh;
  background: #080604;
  color: #e5e7eb;
  font-family: 'Instrument Sans', system-ui, sans-serif;
}

.adm-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.65); z-index: 90;
}
.sidebar-open .adm-overlay { display: block; }

/* Sidebar */
.adm-sidebar {
  width: 240px;
  background: rgba(12, 6, 6, 0.99);
  border-right: 1px solid rgba(239,68,68,0.12);
  position: fixed; top: 0; left: 0;
  height: 100vh;
  display: flex; flex-direction: column;
  z-index: 100; overflow-y: auto;
  transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
}
.adm-sidebar-header {
  display: flex; align-items: center; gap: 0.5rem;
  padding: 1.25rem 1rem 1rem;
  border-bottom: 1px solid rgba(239,68,68,0.1);
  flex-shrink: 0;
}
.adm-logo {
  display: flex; align-items: center; gap: 0.5rem;
  text-decoration: none; color: #e5e7eb;
  font-size: 1.1rem; font-weight: 700; flex: 1;
}
.adm-logo-icon { width: 24px; height: 24px; }
.adm-badge-label {
  font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.08em; color: #ef4444;
  background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25);
  padding: 0.15rem 0.45rem; border-radius: 99px;
  flex-shrink: 0;
}
.adm-sidebar-close {
  display: none; background: none; border: none; cursor: pointer;
  color: #6b7280; font-size: 1rem; padding: 0.25rem; flex-shrink: 0;
}

.adm-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.adm-nav-label {
  font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.1em; color: #4b5563;
  padding: 0.75rem 0.5rem 0.25rem;
}
.adm-nav-link {
  display: flex; align-items: center; gap: 0.75rem;
  padding: 0.625rem 0.75rem; border-radius: 0.5rem;
  text-decoration: none; color: #9ca3af; font-size: 0.875rem;
  transition: all 0.18s; min-height: 44px;
}
.adm-nav-link:hover { background: rgba(239,68,68,0.08); color: #fca5a5; }
.adm-nav-link.active { background: rgba(239,68,68,0.12); color: #fca5a5; font-weight: 600; }
.adm-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.adm-sidebar-footer {
  padding: 1rem 0.75rem;
  border-top: 1px solid rgba(239,68,68,0.1);
  flex-shrink: 0;
}
.adm-user-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
.adm-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #ef4444, #fca5a5);
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 0.85rem; color: #fff; flex-shrink: 0;
}
.adm-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.adm-user-text { min-width: 0; }
.adm-user-name { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.adm-user-role { font-size: 0.72rem; color: #ef4444; font-weight: 600; }
.adm-signout {
  display: flex; align-items: center; gap: 0.5rem;
  width: 100%; padding: 0.5rem 0.75rem;
  background: none; border: none; cursor: pointer;
  color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem;
  transition: all 0.18s; font-family: inherit; text-align: left; min-height: 44px;
}
.adm-signout:hover { background: rgba(239,68,68,0.1); color: #ef4444; }

/* Main */
.adm-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

.adm-topbar {
  display: none; position: sticky; top: 0; z-index: 50;
  background: rgba(12,6,6,0.98);
  border-bottom: 1px solid rgba(239,68,68,0.1);
  padding: 0 1rem; height: 56px;
  align-items: center; justify-content: space-between;
}
.adm-topbar-center { display: flex; align-items: center; gap: 0.5rem; }
.adm-topbar-logo {
  display: flex; align-items: center; gap: 0.4rem;
  text-decoration: none; color: #e5e7eb;
  font-size: 1rem; font-weight: 700;
}
.adm-topbar-badge {
  font-size: 0.62rem; font-weight: 700; text-transform: uppercase;
  color: #ef4444; background: rgba(239,68,68,0.12);
  border: 1px solid rgba(239,68,68,0.25);
  padding: 0.12rem 0.4rem; border-radius: 99px;
}
.adm-menu-btn {
  display: flex; flex-direction: column; justify-content: space-between;
  width: 24px; height: 18px; background: none; border: none;
  cursor: pointer; padding: 0;
}
.adm-menu-btn span {
  display: block; width: 100%; height: 2px;
  background: #fca5a5; border-radius: 2px;
}

.adm-content { flex: 1; padding: 2rem; max-width: 1100px; width: 100%; }

@media (max-width: 768px) {
  .adm-sidebar { transform: translateX(-100%); }
  .sidebar-open .adm-sidebar { transform: translateX(0); }
  .adm-sidebar-close { display: block; }
  .adm-main { margin-left: 0; }
  .adm-topbar { display: flex; }
  .adm-content { padding: 1.25rem; }
}
</style>
