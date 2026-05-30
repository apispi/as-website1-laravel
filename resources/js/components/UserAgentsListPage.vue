<template>
  <div class="ual-shell">

    <div class="ual-overlay" :class="{ open: sidebarOpen }" @click="sidebarOpen = false"></div>
    <aside class="ual-sidebar" :class="{ open: sidebarOpen }">
      <div class="ual-sidebar-header">
        <a href="/" class="ual-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="ual-logo-icon">
            <defs>
              <linearGradient id="ullg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#ullg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#ullg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="ual-sidebar-close" @click="sidebarOpen = false">✕</button>
      </div>

      <nav class="ual-nav">
        <span class="ual-nav-label">Workspace</span>
        <a href="/dashboard" class="ual-nav-link">
          <span class="ual-nav-icon">⬡</span> Home
        </a>
        <a href="/dashboard/agents" class="ual-nav-link active">
          <span class="ual-nav-icon">◈</span> My Agents
        </a>
        <a href="/dashboard/connectors" class="ual-nav-link">
          <span class="ual-nav-icon">⬡</span> My Connectors
        </a>
        <a href="/dashboard/catalog" class="ual-nav-link">
          <span class="ual-nav-icon">◎</span> Catalog
        </a>
        <a href="/dashboard/aria"   class="ual-nav-link"><span class="ual-nav-icon">◇</span> Aria</a>
        <a href="/dashboard/training" class="ual-nav-link">
          <span class="ual-nav-icon">◷</span> Training
        </a>
        <span class="ual-nav-label">Account</span>
        <template v-if="user.is_admin">
          <span class="ual-nav-label">Administration</span>
          <a href="/admin" class="ual-nav-link ual-nav-admin">
            <span class="ual-nav-icon">⬡</span> Admin Panel
          </a>
        </template>
      </nav>

      <div class="ual-sidebar-footer">
        <a href="/dashboard/profile" class="ual-user-row">
          <div class="ual-avatar">{{ initial }}</div>
          <div class="ual-user-text">
            <div class="ual-user-name">{{ user.name }}</div>
            <div class="ual-user-email">{{ user.email }}</div>
          </div>
        </a>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="ual-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="ual-main">
      <header class="ual-topbar">
        <button class="ual-menu-btn" @click="sidebarOpen = true">
          <span></span><span></span><span></span>
        </button>
        <a href="/" class="ual-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="ual-logo-icon">
            <defs>
              <linearGradient id="ullg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#ullg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#ullg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="ual-topbar-right">
          <div class="ual-avatar ual-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <main class="ual-content">
        <div class="ual-page-header">
          <div>
            <a href="/dashboard" class="ual-back">← Dashboard</a>
            <h1 class="ual-page-title">My Agents</h1>
            <p class="ual-page-sub">{{ subscriptions.length }} agent{{ subscriptions.length !== 1 ? 's' : '' }} acquired</p>
          </div>
          <a href="/dashboard/catalog" class="ual-btn-browse">Agent Catalog →</a>
        </div>

        <!-- Empty -->
        <div v-if="subscriptions.length === 0" class="ual-empty">
          <div class="ual-empty-icon">◈</div>
          <div class="ual-empty-title">No agents yet</div>
          <div class="ual-empty-desc">Visit the marketplace to find your first AI agent.</div>
          <a href="/dashboard/catalog" class="ual-btn-browse ual-mt">Browse Catalog →</a>
        </div>

        <!-- List -->
        <div v-else class="ual-list">
          <a v-for="sub in subscriptions" :key="sub.id"
             :href="`/dashboard/agents/${sub.id}`"
             class="ual-row">
            <div class="ual-row-icon">◈</div>
            <div class="ual-row-body">
              <div class="ual-row-top">
                <span class="ual-row-name">{{ sub.agent?.name ?? '—' }}</span>
                <span v-if="sub.agent?.badge" class="ual-badge" :class="sub.agent.badge.toLowerCase()">{{ sub.agent.badge }}</span>
              </div>
              <div class="ual-row-meta">
                <span v-if="sub.agent?.category">{{ sub.agent.category }}</span>
                <span v-if="sub.agent?.price" class="ual-row-price">{{ sub.agent.price }}</span>
              </div>
            </div>
            <div class="ual-row-right">
              <span class="ual-status" :class="sub.status">{{ sub.status }}</span>
              <div class="ual-row-dates">
                <span>Since {{ formatDate(sub.started_at) }}</span>
                <span v-if="sub.expires_at">· Expires {{ formatDate(sub.expires_at) }}</span>
                <span v-else>· Ongoing</span>
              </div>
            </div>
            <span class="ual-arrow">→</span>
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
  subscriptions: { type: Array,  default: () => [] },
});

const sidebarOpen = ref(false);
const initial = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.ual-shell { display: flex; min-height: 100vh; background: #0a0805; color: #e5e7eb; font-family: 'Instrument Sans', system-ui, sans-serif; }

.ual-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 90; }
.ual-overlay.open { display: block; }

.ual-sidebar { width: 240px; background: rgba(14,11,6,0.98); border-right: 1px solid rgba(217,119,6,0.15); position: fixed; top: 0; left: 0; height: 100vh; display: flex; flex-direction: column; z-index: 100; overflow-y: auto; transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); }
.ual-sidebar-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.ual-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.15rem; font-weight: 700; }
.ual-logo-icon { width: 26px; height: 26px; }
.ual-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.1rem; padding: 0.25rem; }

.ual-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.ual-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.ual-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.ual-nav-link:hover { background: rgba(217,119,6,0.1); color: #FCD34D; }
.ual-nav-link.active { background: rgba(217,119,6,0.15); color: #FCD34D; font-weight: 600; }
.ual-nav-admin { color: #fca5a5 !important; }
.ual-nav-admin:hover { background: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
.ual-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.ual-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.ual-user-row {
  display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;
  text-decoration: none; color: inherit;
  padding: 0.4rem 0.5rem; border-radius: 0.5rem; transition: background 0.15s;
}
.ual-user-row:hover { background: rgba(217,119,6,0.08); }
.ual-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0a0805; flex-shrink: 0; }
.ual-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.ual-user-text { min-width: 0; }
.ual-user-name { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ual-user-email { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ual-signout { display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.5rem 0.75rem; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem; transition: all 0.18s; font-family: inherit; text-align: left; min-height: 44px; }
.ual-signout:hover { background: rgba(255,59,48,0.1); color: #ff3b30; }

.ual-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

.ual-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(14,11,6,0.98); border-bottom: 1px solid rgba(217,119,6,0.12); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.ual-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.05rem; font-weight: 700; }
.ual-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.ual-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }
.ual-topbar-right { display: flex; align-items: center; }

.ual-content { flex: 1; padding: 2rem; max-width: 800px; width: 100%; }

.ual-page-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.75rem; flex-wrap: wrap; }
.ual-back { font-size: 0.82rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.35rem; }
.ual-back:hover { color: #FCD34D; }
.ual-page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.ual-page-sub { color: #6b7280; font-size: 0.875rem; }

.ual-btn-browse { padding: 0.6rem 1.1rem; border-radius: 0.625rem; background: rgba(217,119,6,0.12); border: 1px solid rgba(217,119,6,0.3); color: #FCD34D; font-size: 0.85rem; font-weight: 600; text-decoration: none; white-space: nowrap; transition: all 0.18s; }
.ual-btn-browse:hover { background: rgba(217,119,6,0.22); }
.ual-mt { margin-top: 1.25rem; display: inline-block; }

/* Empty */
.ual-empty { background: rgba(28,24,16,0.7); border: 1px dashed rgba(217,119,6,0.2); border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px; }
.ual-empty-icon { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.ual-empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.ual-empty-desc { font-size: 0.875rem; color: #6b7280; }

/* List */
.ual-list { display: flex; flex-direction: column; gap: 0.5rem; }
.ual-row {
  display: flex; align-items: center; gap: 1rem;
  background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.12);
  border-radius: 0.875rem; padding: 1rem 1.1rem;
  text-decoration: none; color: inherit;
  transition: border-color 0.2s, background 0.2s;
}
.ual-row:hover { border-color: rgba(217,119,6,0.35); background: rgba(217,119,6,0.04); }
.ual-row-icon { width: 40px; height: 40px; border-radius: 0.625rem; flex-shrink: 0; background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.2); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #FCD34D; }
.ual-row-body { flex: 1; min-width: 0; }
.ual-row-top { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem; flex-wrap: wrap; }
.ual-row-name { font-size: 0.925rem; font-weight: 600; color: #e5e7eb; }
.ual-row-meta { display: flex; gap: 0.75rem; font-size: 0.78rem; color: #6b7280; }
.ual-row-price { color: #FCD34D; font-weight: 600; }
.ual-badge { display: inline-block; padding: 0.12rem 0.4rem; border-radius: 99px; font-size: 0.65rem; font-weight: 600; }
.ual-badge.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.ual-badge.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.ual-badge.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.ual-row-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.3rem; flex-shrink: 0; }
.ual-status { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; text-transform: capitalize; }
.ual-status.active    { background: rgba(0,217,126,0.1);    color: #00d97e; }
.ual-status.cancelled { background: rgba(239,68,68,0.1);    color: #fca5a5; }
.ual-status.expired   { background: rgba(107,114,128,0.12); color: #9ca3af; }
.ual-row-dates { font-size: 0.75rem; color: #6b7280; white-space: nowrap; }

.ual-arrow { color: #D97706; font-size: 1rem; flex-shrink: 0; transition: transform 0.2s; }
.ual-row:hover .ual-arrow { transform: translateX(3px); }

@media (max-width: 768px) {
  .ual-sidebar { transform: translateX(-100%); }
  .ual-sidebar.open { transform: translateX(0); }
  .ual-sidebar-close { display: block; }
  .ual-main { margin-left: 0; }
  .ual-topbar { display: flex; }
  .ual-content { padding: 1.25rem; }
  .ual-page-title { font-size: 1.3rem; }
  .ual-row-right { display: none; }
}
</style>
