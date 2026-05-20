<template>
  <div class="ua-shell">

    <!-- Sidebar (same as dashboard) -->
    <div class="ua-overlay" :class="{ open: sidebarOpen }" @click="sidebarOpen = false"></div>
    <aside class="ua-sidebar" :class="{ open: sidebarOpen }">
      <div class="ua-sidebar-header">
        <a href="/" class="ua-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="ua-logo-icon">
            <defs>
              <linearGradient id="ulg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#ulg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#ulg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="ua-sidebar-close" @click="sidebarOpen = false">✕</button>
      </div>

      <nav class="ua-nav">
        <span class="ua-nav-label">Workspace</span>
        <a href="/dashboard" class="ua-nav-link active">
          <span class="ua-nav-icon">⬡</span> Overview
        </a>
        <a href="/agents" class="ua-nav-link">
          <span class="ua-nav-icon">◈</span> Browse Agents
        </a>
        <a href="/training" class="ua-nav-link">
          <span class="ua-nav-icon">◷</span> Training
        </a>
        <span class="ua-nav-label">Account</span>
        <a href="/contact" class="ua-nav-link">
          <span class="ua-nav-icon">◉</span> Support
        </a>
        <template v-if="user.is_admin">
          <span class="ua-nav-label">Administration</span>
          <a href="/admin" class="ua-nav-link ua-nav-admin">
            <span class="ua-nav-icon">⬡</span> Admin Panel
          </a>
        </template>
      </nav>

      <div class="ua-sidebar-footer">
        <div class="ua-user-row">
          <div class="ua-avatar">{{ initial }}</div>
          <div class="ua-user-text">
            <div class="ua-user-name">{{ user.name }}</div>
            <div class="ua-user-email">{{ user.email }}</div>
          </div>
        </div>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="ua-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="ua-main">
      <header class="ua-topbar">
        <button class="ua-menu-btn" @click="sidebarOpen = true">
          <span></span><span></span><span></span>
        </button>
        <a href="/" class="ua-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="ua-logo-icon">
            <defs>
              <linearGradient id="ulg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#ulg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#ulg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="ua-topbar-right">
          <div class="ua-avatar ua-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <main class="ua-content">
        <!-- Breadcrumb -->
        <div class="ua-breadcrumb">
          <a href="/dashboard" class="ua-back">← My Agents</a>
        </div>

        <!-- Agent header -->
        <div class="ua-agent-header">
          <div class="ua-agent-icon-wrap">◈</div>
          <div class="ua-agent-header-body">
            <div class="ua-agent-header-top">
              <h1 class="ua-agent-title">{{ agent.name }}</h1>
              <span v-if="agent.badge" class="ua-badge" :class="agent.badge.toLowerCase()">{{ agent.badge }}</span>
            </div>
            <div class="ua-agent-category">{{ agent.category }}</div>
          </div>
          <span class="ua-status-badge" :class="subscription.status">{{ subscription.status }}</span>
        </div>

        <!-- Description -->
        <section class="ua-section" v-if="agent.description">
          <h2 class="ua-section-title">About this Agent</h2>
          <div class="ua-card ua-description">{{ agent.description }}</div>
        </section>

        <!-- Subscription details -->
        <section class="ua-section">
          <h2 class="ua-section-title">Subscription Details</h2>
          <div class="ua-info-card">
            <div class="ua-info-row">
              <span class="ua-info-label">Status</span>
              <span class="ua-status-badge" :class="subscription.status">{{ subscription.status }}</span>
            </div>
            <div class="ua-info-row">
              <span class="ua-info-label">Price</span>
              <span class="ua-info-val ua-price">{{ agent.price ?? '—' }}</span>
            </div>
            <div class="ua-info-row">
              <span class="ua-info-label">Started</span>
              <span class="ua-info-val">{{ formatDate(subscription.started_at) }}</span>
            </div>
            <div class="ua-info-row">
              <span class="ua-info-label">Expires</span>
              <span class="ua-info-val">{{ subscription.expires_at ? formatDate(subscription.expires_at) : 'Ongoing' }}</span>
            </div>
          </div>
        </section>

        <!-- Actions -->
        <section class="ua-section">
          <div class="ua-actions">
            <a :href="`/agents/${agent.slug}`" class="ua-btn-secondary">View on Marketplace →</a>
            <a href="/contact" class="ua-btn-ghost">Get Support</a>
          </div>
        </section>
      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user:         { type: Object, default: () => ({}) },
  csrfToken:    { type: String, default: '' },
  subscription: { type: Object, default: () => ({}) },
});

const sidebarOpen = ref(false);
const agent = computed(() => props.subscription.agent ?? {});

const initial = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.ua-shell {
  display: flex; min-height: 100vh;
  background: #0a0805; color: #e5e7eb;
  font-family: 'Instrument Sans', system-ui, sans-serif;
}

/* Overlay */
.ua-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.65); z-index: 90;
}
.ua-overlay.open { display: block; }

/* Sidebar */
.ua-sidebar {
  width: 240px; background: rgba(14,11,6,0.98);
  border-right: 1px solid rgba(217,119,6,0.15);
  position: fixed; top: 0; left: 0; height: 100vh;
  display: flex; flex-direction: column; z-index: 100;
  overflow-y: auto;
  transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
}
.ua-sidebar-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.25rem 1rem 1rem;
  border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0;
}
.ua-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.15rem; font-weight: 700; }
.ua-logo-icon { width: 26px; height: 26px; }
.ua-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.1rem; padding: 0.25rem; }

.ua-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.ua-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.ua-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.ua-nav-link:hover { background: rgba(217,119,6,0.1); color: #FCD34D; }
.ua-nav-link.active { background: rgba(217,119,6,0.15); color: #FCD34D; font-weight: 600; }
.ua-nav-admin { color: #fca5a5 !important; }
.ua-nav-admin:hover { background: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
.ua-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.ua-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.ua-user-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
.ua-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0a0805; flex-shrink: 0; }
.ua-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.ua-user-text { min-width: 0; }
.ua-user-name { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ua-user-email { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ua-signout { display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.5rem 0.75rem; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem; transition: all 0.18s; font-family: inherit; text-align: left; min-height: 44px; }
.ua-signout:hover { background: rgba(255,59,48,0.1); color: #ff3b30; }

/* Main */
.ua-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

/* Topbar */
.ua-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(14,11,6,0.98); border-bottom: 1px solid rgba(217,119,6,0.12); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.ua-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.05rem; font-weight: 700; }
.ua-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.ua-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }
.ua-topbar-right { display: flex; align-items: center; }

/* Content */
.ua-content { flex: 1; padding: 2rem; max-width: 720px; width: 100%; }

.ua-breadcrumb { margin-bottom: 1.5rem; }
.ua-back { font-size: 0.82rem; color: #6b7280; text-decoration: none; }
.ua-back:hover { color: #FCD34D; }

/* Agent header */
.ua-agent-header {
  display: flex; align-items: flex-start; gap: 1rem;
  background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.15);
  border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem;
}
.ua-agent-icon-wrap {
  width: 52px; height: 52px; border-radius: 0.75rem; flex-shrink: 0;
  background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.25);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; color: #FCD34D;
}
.ua-agent-header-body { flex: 1; min-width: 0; }
.ua-agent-header-top { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.3rem; }
.ua-agent-title { font-size: 1.4rem; font-weight: 700; color: #f1f5f9; }
.ua-agent-category { font-size: 0.85rem; color: #6b7280; }

.ua-badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; }
.ua-badge.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.ua-badge.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.ua-badge.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.ua-status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; flex-shrink: 0; }
.ua-status-badge.active    { background: rgba(0,217,126,0.1);    color: #00d97e; }
.ua-status-badge.cancelled { background: rgba(239,68,68,0.1);    color: #fca5a5; }
.ua-status-badge.expired   { background: rgba(107,114,128,0.12); color: #9ca3af; }

/* Sections */
.ua-section { margin-bottom: 1.5rem; }
.ua-section-title { font-size: 0.9rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.75rem; }

.ua-card { background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.1); border-radius: 1rem; padding: 1.25rem; }
.ua-description { font-size: 0.9rem; color: #d1d5db; line-height: 1.65; }

.ua-info-card { background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.12); border-radius: 1rem; overflow: hidden; }
.ua-info-row { display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1.25rem; border-bottom: 1px solid rgba(217,119,6,0.07); gap: 1rem; }
.ua-info-row:last-child { border-bottom: none; }
.ua-info-label { font-size: 0.82rem; color: #6b7280; flex-shrink: 0; }
.ua-info-val { font-size: 0.875rem; color: #e5e7eb; }
.ua-price { color: #FCD34D; font-weight: 600; }

/* Actions */
.ua-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.ua-btn-secondary {
  padding: 0.65rem 1.25rem; border-radius: 0.625rem;
  background: rgba(217,119,6,0.15); border: 1px solid rgba(217,119,6,0.35);
  color: #FCD34D; font-size: 0.875rem; font-weight: 600;
  text-decoration: none; transition: all 0.18s; white-space: nowrap;
}
.ua-btn-secondary:hover { background: rgba(217,119,6,0.25); }
.ua-btn-ghost {
  padding: 0.65rem 1.25rem; border-radius: 0.625rem;
  background: transparent; border: 1px solid rgba(217,119,6,0.2);
  color: #9ca3af; font-size: 0.875rem; font-weight: 600;
  text-decoration: none; transition: all 0.18s; white-space: nowrap;
}
.ua-btn-ghost:hover { border-color: rgba(217,119,6,0.4); color: #e5e7eb; }

/* Mobile */
@media (max-width: 768px) {
  .ua-sidebar { transform: translateX(-100%); }
  .ua-sidebar.open { transform: translateX(0); }
  .ua-sidebar-close { display: block; }
  .ua-main { margin-left: 0; }
  .ua-topbar { display: flex; }
  .ua-content { padding: 1.25rem; }
  .ua-agent-title { font-size: 1.15rem; }
  .ua-agent-header { flex-wrap: wrap; }
}
</style>
