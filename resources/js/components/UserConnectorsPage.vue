<template>
  <div class="uc-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <div class="uc-overlay" @click="sidebarOpen = false"></div>

    <aside class="uc-sidebar">
      <div class="uc-sidebar-header">
        <a href="/" class="uc-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="uc-logo-icon">
            <defs>
              <linearGradient id="uclg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#uclg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#uclg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="uc-sidebar-close" @click="sidebarOpen = false">✕</button>
      </div>

      <nav class="uc-nav">
        <span class="uc-nav-label">Workspace</span>
        <a href="/dashboard"            class="uc-nav-link"><span class="uc-nav-icon">⬡</span> Home</a>
        <a href="/dashboard/subscriptions"     class="uc-nav-link"><span class="uc-nav-icon">◈</span> My Agents</a>
        <a href="/dashboard/connectors" class="uc-nav-link active"><span class="uc-nav-icon">⬡</span> My Connectors</a>
        <a href="/dashboard/catalog"    class="uc-nav-link"><span class="uc-nav-icon">◎</span> Catalog</a>
        <a href="/dashboard/training"   class="uc-nav-link"><span class="uc-nav-icon">◷</span> Training</a>
        <span class="uc-nav-label">Account</span>
        <template v-if="user.is_admin">
          <span class="uc-nav-label">Administration</span>
          <a href="/admin" class="uc-nav-link uc-nav-admin"><span class="uc-nav-icon">⬡</span> Admin Panel</a>
        </template>
      </nav>

      <div class="uc-sidebar-footer">
        <a href="/dashboard/profile" class="uc-user-row">
          <div class="uc-avatar">{{ initial }}</div>
          <div class="uc-user-text">
            <div class="uc-user-name">{{ user.name }}</div>
            <div class="uc-user-email">{{ user.email }}</div>
          </div>
        </a>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="uc-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="uc-main">
      <header class="uc-topbar">
        <button class="uc-menu-btn" @click="sidebarOpen = true"><span></span><span></span><span></span></button>
        <a href="/" class="uc-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" style="width:20px;height:20px;">
            <defs><linearGradient id="uclg2" x1=".5" y1="0" x2=".5" y2="1"><stop offset="0%" stop-color="#FCD34D"/><stop offset="100%" stop-color="#D97706"/></linearGradient></defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#uclg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#uclg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="uc-avatar uc-avatar-sm">{{ initial }}</div>
      </header>

      <main class="uc-content">

        <div class="uc-page-header">
          <div>
            <a href="/dashboard" class="uc-back">← Dashboard</a>
            <h1 class="uc-page-title">My Connectors</h1>
            <p class="uc-page-sub">{{ userConnectors.length }} integration{{ userConnectors.length !== 1 ? 's' : '' }} connected</p>
          </div>
          <a href="/dashboard/catalog?tab=connectors" class="uc-catalog-link">Connector Catalog →</a>
        </div>

        <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
        <div v-if="flashError"   class="flash error">{{ flashError }}</div>

        <div v-if="userConnectors.length > 0" class="uc-search-wrap">
          <span class="uc-search-icon">⊕</span>
          <input
            v-model="search"
            type="text"
            class="uc-search"
            placeholder="Search connectors…"
            autocomplete="off"
          />
          <button v-if="search" class="uc-search-clear" @click="search = ''" aria-label="Clear">✕</button>
        </div>

        <!-- Connected -->
        <div v-if="filteredConnectors.length > 0" class="section">
          <div class="section-label">Connected</div>
          <div class="uc-list">
            <a v-for="uc in filteredConnectors" :key="uc.id"
               :href="`/dashboard/connectors/${uc.id}/edit`"
               class="uc-row uc-row-link">
              <div class="uc-row-icon">{{ uc.connector?.icon || '⬡' }}</div>
              <div class="uc-row-body">
                <div class="uc-row-top">
                  <span class="uc-row-name">{{ uc.connector?.name ?? '—' }}</span>
                  <span v-if="uc.connector?.is_oauth" class="uc-type-badge oauth">OAuth</span>
                  <span v-else class="uc-type-badge api">API</span>
                </div>
                <div class="uc-row-meta">
                  <span v-if="uc.connector?.category">{{ uc.connector.category }}</span>
                  <span v-if="uc.connected_at" class="uc-dot">·</span>
                  <span v-if="uc.connected_at">Connected {{ formatDate(uc.connected_at) }}</span>
                </div>
              </div>
              <div class="uc-row-right">
                <span class="uc-status" :class="uc.status">{{ uc.status }}</span>
                <form v-if="uc.connector?.is_oauth && uc.status === 'active'"
                      method="POST" :action="`/connectors/${uc.connector.slug}/disconnect`"
                      class="uc-disconnect-form"
                      @click.stop>
                  <input type="hidden" name="_token" :value="csrfToken">
                  <button type="submit" class="uc-btn-disconnect"
                          @click.prevent="confirmDisconnect($event, uc.connector.name)">
                    Disconnect
                  </button>
                </form>
                <a v-if="uc.connector?.is_oauth && uc.status === 'disconnected'"
                   :href="`/connectors/${uc.connector.slug}/authorize`"
                   class="uc-btn-connect"
                   @click.stop>Reconnect →</a>
              </div>
            </a>
          </div>
        </div>

        <!-- No search results -->
        <div v-else-if="userConnectors.length > 0 && filteredConnectors.length === 0" class="uc-empty">
          <div class="uc-empty-icon">⬡</div>
          <div class="uc-empty-title">No connectors match your search</div>
          <div class="uc-empty-desc">Try a different keyword.</div>
        </div>

        <!-- Empty state -->
        <div v-if="userConnectors.length === 0" class="uc-empty">
          <div class="uc-empty-icon">⬡</div>
          <div class="uc-empty-title">No connectors connected yet</div>
          <div class="uc-empty-desc"><a href="/dashboard/catalog?tab=connectors" class="uc-empty-link">Browse the Connector Catalog →</a></div>
        </div>

      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user:           { type: Object, default: () => ({}) },
  csrfToken:      { type: String, default: '' },
  userConnectors: { type: Array,  default: () => [] },
  available:      { type: Array,  default: () => [] },
  flashSuccess:   { type: String, default: '' },
  flashError:     { type: String, default: '' },
});

const sidebarOpen = ref(false);
const search      = ref('');
const initial     = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

const filteredConnectors = computed(() => {
  const q = search.value.trim().toLowerCase();
  if (!q) return props.userConnectors;
  return props.userConnectors.filter(uc =>
    (uc.connector?.name     || '').toLowerCase().includes(q) ||
    (uc.connector?.category || '').toLowerCase().includes(q)
  );
});

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}

function confirmDisconnect(event, name) {
  if (confirm(`Disconnect ${name}? You can reconnect at any time.`)) {
    event.target.closest('form').submit();
  }
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.uc-shell { display: flex; min-height: 100vh; background: #0D0B08; color: #e5e7eb; font-family: 'Instrument Sans', system-ui, sans-serif; }

.uc-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 90; }
.sidebar-open .uc-overlay { display: block; }

.uc-sidebar {
  width: 240px; background: rgba(15,11,5,0.99); border-right: 1px solid rgba(217,119,6,0.12);
  position: fixed; top: 0; left: 0; height: 100vh;
  display: flex; flex-direction: column; z-index: 100; overflow-y: auto;
  transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
}
.uc-sidebar-header { display: flex; align-items: center; gap: 0.5rem; padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.uc-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.1rem; font-weight: 700; flex: 1; }
.uc-logo-icon { width: 24px; height: 24px; }
.uc-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1rem; padding: 0.25rem; }

.uc-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.uc-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.uc-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.uc-nav-link:hover { background: rgba(217,119,6,0.08); color: #FCD34D; }
.uc-nav-link.active { background: rgba(217,119,6,0.12); color: #FCD34D; font-weight: 600; }
.uc-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
.uc-nav-admin { color: #ef4444 !important; }
.uc-nav-admin:hover { background: rgba(239,68,68,0.08) !important; color: #ef4444 !important; }

.uc-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.uc-user-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; text-decoration: none; }
.uc-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0D0B08; flex-shrink: 0; }
.uc-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.uc-user-name  { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; }
.uc-user-email { font-size: 0.72rem; color: #6b7280; }
.uc-signout { display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.5rem 0.75rem; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem; transition: all 0.18s; font-family: inherit; min-height: 44px; }
.uc-signout:hover { background: rgba(217,119,6,0.08); color: #D97706; }

.uc-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

.uc-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(13,11,8,0.98); border-bottom: 1px solid rgba(217,119,6,0.1); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.uc-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1rem; font-weight: 700; }
.uc-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.uc-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }

.uc-content { flex: 1; padding: 2rem; max-width: 860px; width: 100%; }

.uc-page-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.uc-back       { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.35rem; }
.uc-back:hover { color: #FCD34D; }
.uc-page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.uc-page-sub   { color: #6b7280; font-size: 0.875rem; }
.uc-catalog-link { font-size: 0.85rem; font-weight: 600; color: #FCD34D; text-decoration: none; padding: 0.6rem 1.1rem; background: rgba(217,119,6,0.12); border: 1px solid rgba(217,119,6,0.3); border-radius: 0.625rem; transition: all 0.18s; white-space: nowrap; }
.uc-catalog-link:hover { background: rgba(217,119,6,0.22); }

.uc-search-wrap { position: relative; display: flex; align-items: center; margin-bottom: 1.25rem; }
.uc-search-icon { position: absolute; left: 0.75rem; font-size: 1rem; color: #4b5563; pointer-events: none; }
.uc-search { background: rgba(28,24,16,0.8); border: 1px solid rgba(217,119,6,0.2); border-radius: 0.625rem; padding: 0.55rem 2.25rem 0.55rem 2.25rem; color: #e5e7eb; font-size: 0.875rem; width: 100%; outline: none; transition: border-color 0.18s; font-family: inherit; }
.uc-search::placeholder { color: #4b5563; }
.uc-search:focus { border-color: rgba(217,119,6,0.5); }
.uc-search-clear { position: absolute; right: 0.6rem; background: none; border: none; cursor: pointer; color: #4b5563; font-size: 0.75rem; padding: 0.25rem; line-height: 1; }
.uc-search-clear:hover { color: #9ca3af; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.section { margin-bottom: 2rem; }
.section-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 0.75rem; }

.uc-list { display: flex; flex-direction: column; gap: 0.5rem; }

.uc-row {
  display: flex; align-items: center; gap: 1rem;
  background: rgba(20,14,4,0.8); border: 1px solid rgba(217,119,6,0.12);
  border-radius: 0.875rem; padding: 1rem 1.25rem;
  transition: border-color 0.18s;
}
.uc-row-available { opacity: 0.7; }
.uc-row-available:hover { opacity: 1; border-color: rgba(217,119,6,0.25); }

.uc-row-icon { width: 40px; height: 40px; border-radius: 0.625rem; flex-shrink: 0; background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
.uc-row-icon.muted { opacity: 0.45; }

.uc-row-body  { flex: 1; min-width: 0; }
.uc-row-top   { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem; flex-wrap: wrap; }
.uc-row-name  { font-size: 0.95rem; font-weight: 600; color: #e5e7eb; }
.uc-row-meta  { font-size: 0.78rem; color: #6b7280; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
.uc-dot       { color: #4b5563; }

.uc-type-badge { display: inline-block; padding: 0.1rem 0.4rem; border-radius: 99px; font-size: 0.65rem; font-weight: 700; }
.uc-type-badge.oauth { background: rgba(99,102,241,0.12); color: #a5b4fc; }
.uc-type-badge.api   { background: rgba(217,119,6,0.12);  color: #FCD34D; }

.uc-row-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; }

.uc-status { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; }
.uc-status.active       { background: rgba(0,217,126,0.1);   color: #00d97e; }
.uc-status.disconnected { background: rgba(107,114,128,0.12); color: #9ca3af; }

.uc-disconnect-form { display: inline; }
.uc-btn-disconnect { background: none; border: 1px solid rgba(239,68,68,0.2); border-radius: 0.4rem; color: #9ca3af; font-size: 0.78rem; font-weight: 600; padding: 0.3rem 0.65rem; cursor: pointer; font-family: inherit; transition: all 0.18s; }
.uc-btn-disconnect:hover { border-color: rgba(239,68,68,0.5); color: #fca5a5; }
.uc-btn-connect { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 0.5rem; background: rgba(217,119,6,0.12); border: 1px solid rgba(217,119,6,0.3); color: #FCD34D; font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: all 0.18s; white-space: nowrap; cursor: pointer; font-family: inherit; }
.uc-btn-connect:hover { background: rgba(217,119,6,0.22); }
.uc-connect-form { display: inline; margin: 0; padding: 0; }
.uc-row-link { text-decoration: none; color: inherit; cursor: pointer; }
.uc-row-link:hover { border-color: rgba(217,119,6,0.3); background: rgba(28,20,8,0.9); }
.uc-not-available { font-size: 0.78rem; color: #4b5563; }

.uc-empty { text-align: center; padding: 4rem 2rem; }
.uc-empty-icon  { font-size: 2.5rem; opacity: 0.15; margin-bottom: 1rem; }
.uc-empty-title { font-size: 1.1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.5rem; }
.uc-empty-desc  { font-size: 0.875rem; color: #6b7280; }
.uc-empty-link  { color: #D97706; text-decoration: none; font-weight: 600; }
.uc-empty-link:hover { text-decoration: underline; }

@media (max-width: 768px) {
  .uc-sidebar { transform: translateX(-100%); }
  .sidebar-open .uc-sidebar { transform: translateX(0); }
  .uc-sidebar-close { display: block; }
  .uc-main { margin-left: 0; }
  .uc-topbar { display: flex; }
  .uc-content { padding: 1.25rem; }
  .uc-row { flex-wrap: wrap; }
  .uc-row-right { flex-direction: row; align-items: center; }
}
</style>
