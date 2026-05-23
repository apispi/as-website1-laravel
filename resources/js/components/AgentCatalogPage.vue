<template>
  <div class="ac-shell">

    <div class="ac-overlay" :class="{ open: sidebarOpen }" @click="sidebarOpen = false"></div>
    <aside class="ac-sidebar" :class="{ open: sidebarOpen }">
      <div class="ac-sidebar-header">
        <a href="/" class="ac-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="ac-logo-icon">
            <defs>
              <linearGradient id="aclg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#aclg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#aclg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="ac-sidebar-close" @click="sidebarOpen = false">✕</button>
      </div>

      <nav class="ac-nav">
        <span class="ac-nav-label">Workspace</span>
        <a href="/dashboard" class="ac-nav-link">
          <span class="ac-nav-icon">⬡</span> Overview
        </a>
        <a href="/dashboard/agents" class="ac-nav-link">
          <span class="ac-nav-icon">◈</span> My Agents
        </a>
        <a href="/dashboard/catalog" class="ac-nav-link active">
          <span class="ac-nav-icon">◎</span> Catalog
        </a>
        <a href="/training" class="ac-nav-link">
          <span class="ac-nav-icon">◷</span> Training
        </a>
        <span class="ac-nav-label">Account</span>
        <a href="/contact" class="ac-nav-link">
          <span class="ac-nav-icon">◉</span> Support
        </a>
        <template v-if="user.is_admin">
          <span class="ac-nav-label">Administration</span>
          <a href="/admin" class="ac-nav-link ac-nav-admin">
            <span class="ac-nav-icon">⬡</span> Admin Panel
          </a>
        </template>
      </nav>

      <div class="ac-sidebar-footer">
        <a href="/dashboard/profile" class="ac-user-row">
          <div class="ac-avatar">{{ initial }}</div>
          <div class="ac-user-text">
            <div class="ac-user-name">{{ user.name }}</div>
            <div class="ac-user-email">{{ user.email }}</div>
          </div>
        </a>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="ac-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="ac-main">
      <header class="ac-topbar">
        <button class="ac-menu-btn" @click="sidebarOpen = true">
          <span></span><span></span><span></span>
        </button>
        <a href="/" class="ac-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="ac-logo-icon">
            <defs>
              <linearGradient id="aclg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#aclg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#aclg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="ac-topbar-right">
          <div class="ac-avatar ac-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <main class="ac-content">
        <div class="ac-page-header">
          <div>
            <h1 class="ac-page-title">Catalog</h1>
            <p class="ac-page-sub">
              <template v-if="activeTab === 'agents'">{{ filteredAgents.length }} of {{ agents.length }} available agents</template>
              <template v-else>{{ userConnectors.length }} connected · {{ available.length }} available</template>
            </p>
          </div>
        </div>

        <!-- Tab bar -->
        <div class="ac-tabs">
          <button :class="['ac-tab-btn', { active: activeTab === 'agents' }]" @click="setTab('agents')">
            Agents
            <span class="ac-tab-count">{{ agents.length }}</span>
          </button>
          <button :class="['ac-tab-btn', { active: activeTab === 'connectors' }]" @click="setTab('connectors')">
            Connectors
            <span class="ac-tab-count">{{ userConnectors.length + available.length }}</span>
          </button>
        </div>

        <!-- ── AGENTS TAB ── -->
        <template v-if="activeTab === 'agents'">
          <div class="ac-toolbar">
            <div class="ac-search-wrap">
              <span class="ac-search-icon">◎</span>
              <input v-model="agentSearch" type="text" placeholder="Search agents…" class="ac-search-input">
            </div>
            <div class="ac-filter-tabs">
              <button v-for="f in agentFilters" :key="f"
                      :class="['ac-filter-tab', { active: agentFilter === f }]"
                      @click="agentFilter = f">
                {{ f }}
              </button>
            </div>
          </div>

          <div v-if="filteredAgents.length === 0" class="ac-empty">
            <div class="ac-empty-icon">◎</div>
            <div class="ac-empty-title">No agents found</div>
            <div class="ac-empty-desc">Try a different search or filter.</div>
          </div>

          <div v-else class="ac-table-wrap">
            <table class="ac-table">
              <thead>
                <tr>
                  <th>Agent</th>
                  <th>Badge</th>
                  <th>Rating</th>
                  <th>Users</th>
                  <th>Price</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="agent in filteredAgents" :key="agent.id">
                  <td>
                    <a :href="`/agents/${agent.slug}`" class="ac-agent-cell">
                      <div class="ac-agent-icon">◈</div>
                      <div>
                        <div class="ac-agent-name">{{ agent.name }}</div>
                        <div class="ac-agent-category">{{ agent.category }}</div>
                      </div>
                    </a>
                  </td>
                  <td>
                    <span v-if="agent.badge" class="ac-badge" :class="agent.badge.toLowerCase()">{{ agent.badge }}</span>
                    <span v-else class="ac-muted">—</span>
                  </td>
                  <td class="ac-rating">{{ agent.rating ? '⭐ ' + agent.rating : '—' }}</td>
                  <td class="ac-muted">{{ agent.users_count || '—' }}</td>
                  <td class="ac-price">{{ agent.price || '—' }}</td>
                  <td class="ac-actions">
                    <a :href="`/agents/${agent.slug}`" class="ac-btn-view">View →</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>

        <!-- ── CONNECTORS TAB ── -->
        <template v-else>
          <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
          <div v-if="flashError"   class="flash error">{{ flashError }}</div>

          <!-- Connected -->
          <div v-if="userConnectors.length > 0" class="cn-section">
            <div class="cn-section-label">Connected</div>
            <div class="cn-list">
              <div v-for="uc in userConnectors" :key="uc.id" class="cn-row">
                <div class="cn-row-icon">{{ uc.connector?.icon || '⬡' }}</div>
                <div class="cn-row-body">
                  <div class="cn-row-top">
                    <span class="cn-row-name">{{ uc.connector?.name ?? '—' }}</span>
                    <span v-if="uc.connector?.is_oauth" class="cn-type-badge oauth">OAuth</span>
                    <span v-else class="cn-type-badge api">API</span>
                  </div>
                  <div class="cn-row-meta">
                    <span v-if="uc.connector?.category">{{ uc.connector.category }}</span>
                    <span v-if="uc.connected_at" class="cn-dot">·</span>
                    <span v-if="uc.connected_at">Connected {{ formatDate(uc.connected_at) }}</span>
                  </div>
                </div>
                <div class="cn-row-right">
                  <span class="cn-status" :class="uc.status">{{ uc.status }}</span>
                  <form v-if="uc.connector?.is_oauth && uc.status === 'active'"
                        method="POST" :action="`/connectors/${uc.connector.slug}/disconnect`"
                        class="cn-inline-form">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit" class="cn-btn-disconnect"
                            @click.prevent="confirmDisconnect($event, uc.connector.name)">
                      Disconnect
                    </button>
                  </form>
                  <a v-if="uc.connector?.is_oauth && uc.status === 'disconnected'"
                     :href="`/connectors/${uc.connector.slug}/authorize`"
                     class="cn-btn-connect">Reconnect →</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Available -->
          <div v-if="available.length > 0" class="cn-section">
            <div class="cn-section-label">Available</div>
            <div class="cn-list">
              <div v-for="c in available" :key="c.id" class="cn-row cn-row-available">
                <div class="cn-row-icon muted">{{ c.icon || '⬡' }}</div>
                <div class="cn-row-body">
                  <div class="cn-row-top">
                    <span class="cn-row-name">{{ c.name }}</span>
                    <span v-if="c.is_oauth" class="cn-type-badge oauth">OAuth</span>
                    <span v-else class="cn-type-badge api">API</span>
                  </div>
                  <div class="cn-row-meta">{{ c.category }}</div>
                </div>
                <div class="cn-row-right">
                  <a v-if="c.is_oauth" :href="`/connectors/${c.slug}/authorize`" class="cn-btn-connect">Connect →</a>
                  <span v-else class="cn-not-available">Contact support</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty -->
          <div v-if="userConnectors.length === 0 && available.length === 0" class="ac-empty">
            <div class="ac-empty-icon">⬡</div>
            <div class="ac-empty-title">No connectors available</div>
            <div class="ac-empty-desc">Check back soon for integrations.</div>
          </div>
        </template>

      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user:           { type: Object, default: () => ({}) },
  csrfToken:      { type: String, default: '' },
  agents:         { type: Array,  default: () => [] },
  userConnectors: { type: Array,  default: () => [] },
  available:      { type: Array,  default: () => [] },
  flashSuccess:   { type: String, default: '' },
  flashError:     { type: String, default: '' },
});

const sidebarOpen = ref(false);
const initial     = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

// Tab state — read initial value from URL query param
const activeTab = ref(
  new URLSearchParams(window.location.search).get('tab') === 'connectors' ? 'connectors' : 'agents'
);

function setTab(tab) {
  activeTab.value = tab;
  const url = new URL(window.location.href);
  if (tab === 'agents') {
    url.searchParams.delete('tab');
  } else {
    url.searchParams.set('tab', tab);
  }
  window.history.replaceState({}, '', url.toString());
}

// Agents tab state
const agentSearch  = ref('');
const agentFilter  = ref('All');
const agentFilters = ['All', 'Popular', 'Premium', 'New'];

const filteredAgents = computed(() =>
  props.agents.filter(a => {
    const q = agentSearch.value.toLowerCase();
    const matchSearch = !q || a.name.toLowerCase().includes(q) || (a.description || '').toLowerCase().includes(q);
    const matchFilter = agentFilter.value === 'All' || a.badge === agentFilter.value;
    return matchSearch && matchFilter;
  })
);

// Connectors tab helpers
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

.ac-shell { display: flex; min-height: 100vh; background: #0a0805; color: #e5e7eb; font-family: 'Instrument Sans', system-ui, sans-serif; }

.ac-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 90; }
.ac-overlay.open { display: block; }

.ac-sidebar { width: 240px; background: rgba(14,11,6,0.98); border-right: 1px solid rgba(217,119,6,0.15); position: fixed; top: 0; left: 0; height: 100vh; display: flex; flex-direction: column; z-index: 100; overflow-y: auto; transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); }
.ac-sidebar-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.ac-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.15rem; font-weight: 700; }
.ac-logo-icon { width: 26px; height: 26px; }
.ac-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.1rem; padding: 0.25rem; }

.ac-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.ac-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.ac-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.ac-nav-link:hover { background: rgba(217,119,6,0.1); color: #FCD34D; }
.ac-nav-link.active { background: rgba(217,119,6,0.15); color: #FCD34D; font-weight: 600; }
.ac-nav-admin { color: #fca5a5 !important; }
.ac-nav-admin:hover { background: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
.ac-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.ac-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.ac-user-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; text-decoration: none; color: inherit; padding: 0.4rem 0.5rem; border-radius: 0.5rem; transition: background 0.15s; }
.ac-user-row:hover { background: rgba(217,119,6,0.08); }
.ac-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0a0805; flex-shrink: 0; }
.ac-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.ac-user-text { min-width: 0; }
.ac-user-name { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ac-user-email { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ac-signout { display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.5rem 0.75rem; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem; transition: all 0.18s; font-family: inherit; text-align: left; min-height: 44px; }
.ac-signout:hover { background: rgba(255,59,48,0.1); color: #ff3b30; }

.ac-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
.ac-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(14,11,6,0.98); border-bottom: 1px solid rgba(217,119,6,0.12); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.ac-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.05rem; font-weight: 700; }
.ac-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.ac-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }
.ac-topbar-right { display: flex; align-items: center; }

.ac-content { flex: 1; padding: 2rem; max-width: 1000px; width: 100%; }

.ac-page-header { margin-bottom: 1.25rem; }
.ac-page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.ac-page-sub { color: #6b7280; font-size: 0.875rem; }

/* Tab bar */
.ac-tabs { display: flex; gap: 0; border-bottom: 1px solid rgba(217,119,6,0.12); margin-bottom: 1.5rem; }
.ac-tab-btn { padding: 0.625rem 1.25rem; background: none; border: none; border-bottom: 2px solid transparent; margin-bottom: -1px; cursor: pointer; font-family: inherit; font-size: 0.875rem; font-weight: 600; color: #6b7280; display: flex; align-items: center; gap: 0.5rem; transition: all 0.18s; white-space: nowrap; }
.ac-tab-btn:hover { color: #FCD34D; }
.ac-tab-btn.active { color: #FCD34D; border-bottom-color: #D97706; }
.ac-tab-count { display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 18px; padding: 0 5px; border-radius: 99px; font-size: 0.68rem; font-weight: 700; background: rgba(217,119,6,0.12); color: #D97706; }
.ac-tab-btn.active .ac-tab-count { background: rgba(217,119,6,0.2); }

/* Agents toolbar */
.ac-toolbar { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.ac-search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 360px; }
.ac-search-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; pointer-events: none; }
.ac-search-input { width: 100%; padding: 0.625rem 0.875rem 0.625rem 2.25rem; background: rgba(28,24,16,0.8); border: 1px solid rgba(217,119,6,0.15); border-radius: 0.625rem; color: #e5e7eb; font-size: 0.9rem; font-family: inherit; transition: border-color 0.18s; }
.ac-search-input:focus { outline: none; border-color: rgba(217,119,6,0.4); }
.ac-search-input::placeholder { color: #4b5563; }
.ac-filter-tabs { display: flex; gap: 0.375rem; flex-wrap: wrap; }
.ac-filter-tab { padding: 0.45rem 0.75rem; border-radius: 0.5rem; font-size: 0.78rem; font-weight: 600; cursor: pointer; background: none; border: 1px solid rgba(217,119,6,0.12); color: #6b7280; font-family: inherit; transition: all 0.18s; }
.ac-filter-tab:hover { border-color: rgba(217,119,6,0.3); color: #FCD34D; }
.ac-filter-tab.active { background: rgba(217,119,6,0.12); border-color: rgba(217,119,6,0.3); color: #FCD34D; }

/* Empty state */
.ac-empty { background: rgba(28,24,16,0.7); border: 1px dashed rgba(217,119,6,0.2); border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px; }
.ac-empty-icon { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.ac-empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.ac-empty-desc { font-size: 0.875rem; color: #6b7280; }

/* Agents table */
.ac-table-wrap { background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.12); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.ac-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 650px; }
.ac-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(217,119,6,0.1); background: rgba(217,119,6,0.03); }
.ac-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.07); vertical-align: middle; }
.ac-table tbody tr:last-child td { border-bottom: none; }
.ac-table tbody tr:hover td { background: rgba(217,119,6,0.03); }

.ac-agent-cell { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
.ac-agent-cell:hover .ac-agent-name { color: #FCD34D; }
.ac-agent-icon { width: 34px; height: 34px; border-radius: 0.5rem; flex-shrink: 0; background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.2); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #FCD34D; }
.ac-agent-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.ac-agent-category { font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem; }

.ac-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.ac-badge.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.ac-badge.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.ac-badge.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }
.ac-rating { color: #e5e7eb; font-size: 0.82rem; white-space: nowrap; }
.ac-muted  { color: #6b7280; font-size: 0.82rem; }
.ac-price  { color: #FCD34D; font-weight: 600; font-size: 0.875rem; white-space: nowrap; }
.ac-actions { text-align: right; }
.ac-btn-view { font-size: 0.78rem; font-weight: 600; color: #FCD34D; text-decoration: none; padding: 0.35rem 0.65rem; border-radius: 0.4rem; border: 1px solid rgba(217,119,6,0.25); background: rgba(217,119,6,0.08); transition: all 0.18s; white-space: nowrap; }
.ac-btn-view:hover { background: rgba(217,119,6,0.18); }

/* Flash messages */
.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

/* Connectors tab */
.cn-section { margin-bottom: 2rem; }
.cn-section-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 0.75rem; }
.cn-list { display: flex; flex-direction: column; gap: 0.5rem; }

.cn-row { display: flex; align-items: center; gap: 1rem; background: rgba(20,14,4,0.8); border: 1px solid rgba(217,119,6,0.12); border-radius: 0.875rem; padding: 1rem 1.25rem; transition: border-color 0.18s; }
.cn-row-available { opacity: 0.7; }
.cn-row-available:hover { opacity: 1; border-color: rgba(217,119,6,0.25); }

.cn-row-icon { width: 40px; height: 40px; border-radius: 0.625rem; flex-shrink: 0; background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
.cn-row-icon.muted { opacity: 0.45; }

.cn-row-body { flex: 1; min-width: 0; }
.cn-row-top  { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem; flex-wrap: wrap; }
.cn-row-name { font-size: 0.95rem; font-weight: 600; color: #e5e7eb; }
.cn-row-meta { font-size: 0.78rem; color: #6b7280; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
.cn-dot      { color: #4b5563; }

.cn-type-badge { display: inline-block; padding: 0.1rem 0.4rem; border-radius: 99px; font-size: 0.65rem; font-weight: 700; }
.cn-type-badge.oauth { background: rgba(99,102,241,0.12); color: #a5b4fc; }
.cn-type-badge.api   { background: rgba(217,119,6,0.12); color: #FCD34D; }

.cn-row-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; }
.cn-status { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; }
.cn-status.active       { background: rgba(0,217,126,0.1);   color: #00d97e; }
.cn-status.disconnected { background: rgba(107,114,128,0.12); color: #9ca3af; }

.cn-inline-form { display: inline; }
.cn-btn-disconnect { background: none; border: 1px solid rgba(239,68,68,0.2); border-radius: 0.4rem; color: #9ca3af; font-size: 0.78rem; font-weight: 600; padding: 0.3rem 0.65rem; cursor: pointer; font-family: inherit; transition: all 0.18s; }
.cn-btn-disconnect:hover { border-color: rgba(239,68,68,0.5); color: #fca5a5; }
.cn-btn-connect { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 0.5rem; background: rgba(217,119,6,0.12); border: 1px solid rgba(217,119,6,0.3); color: #FCD34D; font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: all 0.18s; white-space: nowrap; }
.cn-btn-connect:hover { background: rgba(217,119,6,0.22); }
.cn-not-available { font-size: 0.78rem; color: #4b5563; }

@media (max-width: 768px) {
  .ac-sidebar { transform: translateX(-100%); }
  .ac-sidebar.open { transform: translateX(0); }
  .ac-sidebar-close { display: block; }
  .ac-main { margin-left: 0; }
  .ac-topbar { display: flex; }
  .ac-content { padding: 1.25rem; }
  .ac-page-title { font-size: 1.3rem; }
  .ac-toolbar { flex-direction: column; align-items: stretch; }
  .ac-search-wrap { max-width: unset; }
  .cn-row { flex-wrap: wrap; }
  .cn-row-right { flex-direction: row; align-items: center; }
}
</style>
