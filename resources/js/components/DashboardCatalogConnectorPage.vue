<template>
  <div class="dcc-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <div class="dcc-overlay" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside class="dcc-sidebar">
      <div class="dcc-sidebar-header">
        <a href="/" class="dcc-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="dcc-logo-icon">
            <defs>
              <linearGradient id="dcclg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dcclg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dcclg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="dcc-sidebar-close" @click="sidebarOpen = false" aria-label="Close">✕</button>
      </div>

      <nav class="dcc-nav">
        <span class="dcc-nav-label">Workspace</span>
        <a href="/dashboard"            class="dcc-nav-link"><span class="dcc-nav-icon">⬡</span> Home</a>
        <a href="/dashboard/subscriptions" class="dcc-nav-link"><span class="dcc-nav-icon">◈</span> My Agents</a>
        <a href="/dashboard/connectors" class="dcc-nav-link"><span class="dcc-nav-icon">⬡</span> My Connectors</a>
        <a href="/dashboard/catalog"    class="dcc-nav-link active"><span class="dcc-nav-icon">◎</span> Catalog</a>
        <a href="/dashboard/training"   class="dcc-nav-link"><span class="dcc-nav-icon">◷</span> Training</a>
        <a href="/dashboard/aria"       class="dcc-nav-link"><span class="dcc-nav-icon">◇</span> Aria</a>

        <span class="dcc-nav-label">Account</span>

        <template v-if="user.is_admin">
          <span class="dcc-nav-label">Administration</span>
          <a href="/admin" class="dcc-nav-link dcc-nav-admin"><span class="dcc-nav-icon">⬡</span> Admin Panel</a>
        </template>
      </nav>

      <div class="dcc-sidebar-footer">
        <div class="dcc-user-row">
          <div class="dcc-avatar">{{ initial }}</div>
          <div class="dcc-user-text">
            <div class="dcc-user-name">{{ user.name }}</div>
            <div class="dcc-user-email">{{ user.email }}</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <div class="dcc-main">
      <header class="dcc-topbar">
        <button class="dcc-menu-btn" @click="sidebarOpen = true" aria-label="Open menu">
          <span></span><span></span><span></span>
        </button>
        <a href="/" class="dcc-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="dcc-logo-icon">
            <defs>
              <linearGradient id="dcclg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dcclg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dcclg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="dcc-topbar-right">
          <div class="dcc-avatar dcc-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <main class="dcc-content">

        <div class="dcc-breadcrumb">
          <a href="/dashboard/catalog?tab=connectors" class="dcc-back">← Connector Catalog</a>
        </div>

        <!-- Hero -->
        <div class="dcc-hero">
          <div class="dcc-hero-icon">
            <img v-if="connector.icon" :src="connector.icon" :alt="connector.name" class="dcc-icon-img">
            <span v-else>⬡</span>
          </div>
          <div class="dcc-hero-body">
            <div class="dcc-hero-top">
              <h1 class="dcc-hero-title">{{ connector.name }}</h1>
              <span v-if="connector.is_oauth" class="dcc-type-badge oauth">OAuth</span>
              <span v-else class="dcc-type-badge api">API Key</span>
              <span v-if="connector.sla_tier" class="dcc-sla-badge">{{ connector.sla_tier }}</span>
            </div>
            <div class="dcc-hero-category">{{ connector.category }}</div>
          </div>
          <div class="dcc-hero-action">
            <!-- Already connected -->
            <template v-if="userConnector?.status === 'active'">
              <span class="dcc-connected-badge">✓ Connected</span>
              <a :href="`/dashboard/connectors/${userConnector.id}/edit`" class="dcc-btn-manage">Manage →</a>
            </template>
            <!-- Disconnected -->
            <template v-else-if="userConnector?.status === 'disconnected'">
              <span class="dcc-disconnected-badge">● Disconnected</span>
              <a v-if="connector.is_oauth" :href="`/connectors/${connector.slug}/authorize`" class="dcc-btn-connect">Reconnect →</a>
              <a v-else :href="`/dashboard/connectors/${userConnector.id}/edit`" class="dcc-btn-connect">Manage →</a>
            </template>
            <!-- Not connected -->
            <template v-else>
              <a v-if="connector.is_oauth" :href="`/connectors/${connector.slug}/authorize`" class="dcc-btn-connect">Connect via OAuth →</a>
              <form v-else :action="`/dashboard/connectors/${connector.id}/connect`" method="POST" class="dcc-connect-form">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" class="dcc-btn-connect">Connect with API Key →</button>
              </form>
            </template>
          </div>
        </div>

        <!-- Description -->
        <div v-if="connector.description" class="dcc-card">
          <h2 class="dcc-section-title">About this Connector</h2>
          <p class="dcc-description">{{ connector.description }}</p>
        </div>

        <!-- Details -->
        <div class="dcc-card">
          <h2 class="dcc-section-title">Details</h2>
          <div class="dcc-info-grid">
            <div v-if="connector.vendor" class="dcc-info-item">
              <span class="dcc-info-label">Vendor</span>
              <span class="dcc-info-val">{{ connector.vendor }}</span>
            </div>
            <div v-if="connector.version" class="dcc-info-item">
              <span class="dcc-info-label">Version</span>
              <span class="dcc-info-val">{{ connector.version }}</span>
            </div>
            <div class="dcc-info-item">
              <span class="dcc-info-label">Auth Type</span>
              <span class="dcc-info-val">{{ connector.is_oauth ? 'OAuth 2.0' : 'API Key' }}</span>
            </div>
            <div v-if="connector.category" class="dcc-info-item">
              <span class="dcc-info-label">Category</span>
              <span class="dcc-info-val">{{ connector.category }}</span>
            </div>
            <div v-if="userConnector?.connected_at" class="dcc-info-item">
              <span class="dcc-info-label">Connected</span>
              <span class="dcc-info-val">{{ formatDate(userConnector.connected_at) }}</span>
            </div>
          </div>
        </div>

        <!-- Tags -->
        <div v-if="connector.tags?.length" class="dcc-card">
          <h2 class="dcc-section-title">Tags</h2>
          <div class="dcc-tags">
            <span v-for="tag in connector.tags" :key="tag" class="dcc-tag">{{ tag }}</span>
          </div>
        </div>

        <!-- Website -->
        <div v-if="connector.website_url" class="dcc-card dcc-website-card">
          <a :href="connector.website_url" target="_blank" rel="noopener" class="dcc-website-link">
            Visit {{ connector.name }} website →
          </a>
        </div>

      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user:          { type: Object, default: () => ({}) },
  csrfToken:     { type: String, default: '' },
  connector:     { type: Object, default: () => ({}) },
  userConnector: { type: Object, default: null },
});

const sidebarOpen = ref(false);
const initial = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-AU', { day: 'numeric', month: 'long', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.dcc-shell {
  display: flex; min-height: 100vh;
  background: #0a0805; color: #e5e7eb;
  font-family: 'Instrument Sans', system-ui, sans-serif;
}

/* Overlay */
.dcc-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.65); z-index: 90;
}
.sidebar-open .dcc-overlay { display: block; }

/* Sidebar */
.dcc-sidebar {
  width: 240px; background: rgba(14,11,6,0.98);
  border-right: 1px solid rgba(217,119,6,0.15);
  position: fixed; top: 0; left: 0; height: 100vh;
  display: flex; flex-direction: column; z-index: 100;
  overflow-y: auto;
  transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
}
.dcc-sidebar-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.25rem 1rem 1rem;
  border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0;
}
.dcc-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.15rem; font-weight: 700; }
.dcc-logo-icon { width: 26px; height: 26px; }
.dcc-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.1rem; padding: 0.25rem; }

.dcc-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.dcc-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.dcc-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.dcc-nav-link:hover { background: rgba(217,119,6,0.1); color: #FCD34D; }
.dcc-nav-link.active { background: rgba(217,119,6,0.15); color: #FCD34D; font-weight: 600; }
.dcc-nav-admin { color: #fca5a5 !important; }
.dcc-nav-admin:hover { background: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
.dcc-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.dcc-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.dcc-user-row { display: flex; align-items: center; gap: 0.75rem; }
.dcc-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0a0805; flex-shrink: 0; }
.dcc-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.dcc-user-text { min-width: 0; }
.dcc-user-name { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dcc-user-email { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Main */
.dcc-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

/* Topbar */
.dcc-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(14,11,6,0.98); border-bottom: 1px solid rgba(217,119,6,0.12); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.dcc-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.05rem; font-weight: 700; }
.dcc-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.dcc-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }
.dcc-topbar-right { display: flex; align-items: center; }

/* Content */
.dcc-content { flex: 1; padding: 2rem; max-width: 760px; width: 100%; }

.dcc-breadcrumb { margin-bottom: 1.5rem; }
.dcc-back { font-size: 0.82rem; color: #6b7280; text-decoration: none; }
.dcc-back:hover { color: #FCD34D; }

/* Hero */
.dcc-hero {
  display: flex; align-items: flex-start; gap: 1.25rem;
  background: rgba(28,18,8,0.8); border: 1px solid rgba(217,119,6,0.2);
  border-radius: 1.25rem; padding: 1.75rem; margin-bottom: 1.5rem;
  flex-wrap: wrap;
}
.dcc-hero-icon {
  width: 64px; height: 64px; border-radius: 1rem; flex-shrink: 0;
  background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.25);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.6rem; color: #FCD34D;
}
.dcc-icon-img { width: 40px; height: 40px; object-fit: contain; }
.dcc-hero-body { flex: 1; min-width: 0; }
.dcc-hero-top { display: flex; align-items: center; gap: 0.625rem; flex-wrap: wrap; margin-bottom: 0.35rem; }
.dcc-hero-title { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; }
.dcc-hero-category { font-size: 0.85rem; color: #6b7280; }

.dcc-type-badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 99px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
.dcc-type-badge.oauth { background: rgba(99,91,255,0.15); color: #a5b4fc; }
.dcc-type-badge.api   { background: rgba(217,119,6,0.12); color: #FCD34D; }
.dcc-sla-badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; background: rgba(0,217,126,0.08); color: #00d97e; text-transform: capitalize; }

.dcc-hero-action { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; }
.dcc-connected-badge { display: inline-block; padding: 0.4rem 0.9rem; border-radius: 99px; font-size: 0.8rem; font-weight: 700; background: rgba(0,217,126,0.1); border: 1px solid rgba(0,217,126,0.3); color: #00d97e; }
.dcc-disconnected-badge { display: inline-block; padding: 0.4rem 0.9rem; border-radius: 99px; font-size: 0.8rem; font-weight: 700; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; }
.dcc-btn-connect {
  display: inline-block; padding: 0.65rem 1.25rem; border-radius: 0.625rem;
  background: linear-gradient(135deg, #D97706, #B45309); color: #fff;
  font-size: 0.875rem; font-weight: 700; text-decoration: none; white-space: nowrap;
  border: none; cursor: pointer; font-family: inherit; transition: opacity 0.18s;
}
.dcc-btn-connect:hover { opacity: 0.88; }
.dcc-btn-manage {
  display: inline-block; padding: 0.5rem 1rem; border-radius: 0.5rem;
  background: rgba(217,119,6,0.12); border: 1px solid rgba(217,119,6,0.3);
  color: #FCD34D; font-size: 0.82rem; font-weight: 600; text-decoration: none;
  transition: background 0.18s;
}
.dcc-btn-manage:hover { background: rgba(217,119,6,0.22); }
.dcc-connect-form { display: inline; }

/* Cards */
.dcc-card {
  background: rgba(20,12,6,0.8); border: 1px solid rgba(217,119,6,0.12);
  border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 1.25rem;
}
.dcc-section-title { font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 1rem; }
.dcc-description { font-size: 0.9rem; color: #d1d5db; line-height: 1.7; }

/* Info grid */
.dcc-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
.dcc-info-item { background: rgba(217,119,6,0.04); border: 1px solid rgba(217,119,6,0.1); border-radius: 0.75rem; padding: 0.875rem 1rem; }
.dcc-info-label { display: block; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; margin-bottom: 0.3rem; }
.dcc-info-val { font-size: 0.9rem; color: #e5e7eb; font-weight: 500; }

/* Tags */
.dcc-tags { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.dcc-tag { padding: 0.25rem 0.7rem; border-radius: 99px; font-size: 0.78rem; font-weight: 500; background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.15); color: #D97706; }

/* Website */
.dcc-website-card { display: flex; align-items: center; }
.dcc-website-link { font-size: 0.875rem; color: #D97706; text-decoration: none; font-weight: 600; }
.dcc-website-link:hover { color: #FCD34D; }

/* Mobile */
@media (max-width: 768px) {
  .dcc-sidebar { transform: translateX(-100%); }
  .sidebar-open .dcc-sidebar { transform: translateX(0); }
  .dcc-sidebar-close { display: block; }
  .dcc-main { margin-left: 0; }
  .dcc-topbar { display: flex; }
  .dcc-content { padding: 1.25rem; }
  .dcc-hero { flex-direction: column; }
  .dcc-hero-action { align-items: flex-start; }
  .dcc-info-grid { grid-template-columns: 1fr; }
}
</style>
