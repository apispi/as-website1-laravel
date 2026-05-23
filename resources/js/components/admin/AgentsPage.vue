<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="agents">

    <div class="page-header">
      <div>
        <h1 class="page-title">Catalog</h1>
        <p class="page-sub">{{ activeTab === 'agents' ? agentFiltered.length + ' of ' + agents.length + ' agents' : cnFiltered.length + ' of ' + connectors.length + ' connectors' }}</p>
      </div>
      <a v-if="activeTab === 'agents'" href="/admin/agents/create" class="btn-primary">+ New Agent</a>
      <a v-else href="/admin/connectors/create" class="btn-primary">+ New Connector</a>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>

    <!-- Top-level tabs -->
    <div class="top-tab-bar">
      <button :class="['top-tab', { active: activeTab === 'agents' }]" @click="setTab('agents')">
        Agents <span class="top-tab-count">{{ agents.length }}</span>
      </button>
      <button :class="['top-tab', { active: activeTab === 'connectors' }]" @click="setTab('connectors')">
        Connectors <span class="top-tab-count">{{ connectors.length }}</span>
      </button>
    </div>

    <!-- ── AGENTS TAB ── -->
    <div v-show="activeTab === 'agents'">
      <div class="toolbar">
        <div class="search-wrap">
          <span class="search-icon">◎</span>
          <input v-model="agentSearch" type="text" placeholder="Search agents…" class="search-input">
        </div>
        <div class="filter-tabs">
          <button v-for="f in agentFilters" :key="f" :class="['tab', { active: agentFilter === f }]" @click="agentFilter = f">
            {{ f }}
          </button>
        </div>
      </div>

      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Agent</th>
              <th>Badge</th>
              <th>Rating</th>
              <th>Users</th>
              <th>Price</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="agent in agentFiltered" :key="agent.id">
              <td>
                <a :href="`/admin/agents/${agent.id}/edit`" class="item-cell">
                  <div class="item-icon">◈</div>
                  <div>
                    <div class="item-name">{{ agent.name }}</div>
                    <div class="item-slug">{{ agent.slug }}</div>
                  </div>
                </a>
              </td>
              <td>
                <span v-if="agent.badge" class="badge" :class="agent.badge.toLowerCase()">{{ agent.badge }}</span>
                <span v-else class="muted">—</span>
              </td>
              <td class="rating">{{ agent.rating ? '⭐ ' + agent.rating : '—' }}</td>
              <td class="muted">{{ agent.users_count || '—' }}</td>
              <td class="price">{{ agent.price || '—' }}</td>
              <td>
                <span class="status-badge" :class="agent.is_active ? 'active' : 'inactive'">
                  {{ agent.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="actions">
                <a :href="`/agents/${agent.slug}`" target="_blank" class="btn-ghost" title="View public page">↗</a>
                <a :href="`/admin/agents/${agent.id}/edit`" class="btn-ghost">Edit</a>
                <form method="POST" :action="`/admin/agents/${agent.id}`" style="display:inline;"
                      @submit.prevent="confirmDelete($event, agent.name)">
                  <input type="hidden" name="_token" :value="csrfToken">
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            <tr v-if="agentFiltered.length === 0">
              <td colspan="7" style="text-align:center; padding:2.5rem; color:#4b5563;">No agents found</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── CONNECTORS TAB ── -->
    <div v-show="activeTab === 'connectors'">
      <div class="toolbar">
        <div class="search-wrap">
          <span class="search-icon">◎</span>
          <input v-model="cnSearch" type="text" placeholder="Search connectors…" class="search-input">
        </div>
        <div class="filter-tabs">
          <button v-for="f in cnFilters" :key="f" :class="['tab', { active: cnFilter === f }]" @click="cnFilter = f">
            {{ f }}
          </button>
        </div>
      </div>

      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Connector</th>
              <th>Category</th>
              <th>Website</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in cnPage_rows" :key="c.id">
              <td>
                <a :href="`/admin/connectors/${c.id}/edit`" class="item-cell">
                  <div class="item-icon">{{ c.icon || '⬡' }}</div>
                  <div>
                    <div class="item-name">{{ c.name }}</div>
                    <div class="item-slug">{{ c.slug }}</div>
                  </div>
                </a>
              </td>
              <td class="muted">{{ c.category || '—' }}</td>
              <td>
                <a v-if="c.website_url" :href="c.website_url" target="_blank" rel="noopener" class="url-link">↗ Visit</a>
                <span v-else class="muted">—</span>
              </td>
              <td>
                <span class="status-badge" :class="c.is_active ? 'active' : 'inactive'">
                  {{ c.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="actions">
                <a :href="`/admin/connectors/${c.id}/edit`" class="btn-ghost">Edit</a>
                <form method="POST" :action="`/admin/connectors/${c.id}`" style="display:inline;"
                      @submit.prevent="confirmDeleteCn($event, c.name)">
                  <input type="hidden" name="_token" :value="csrfToken">
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            <tr v-if="cnFiltered.length === 0">
              <td colspan="5" style="text-align:center; padding:2.5rem; color:#4b5563;">No connectors found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="cnTotalPages > 1" class="pagination">
        <button class="page-btn" :disabled="cnCurPage === 1" @click="cnCurPage--">‹</button>
        <template v-for="p in cnPageNumbers" :key="p">
          <span v-if="p === '…'" class="page-ellipsis">…</span>
          <button v-else :class="['page-btn', { active: p === cnCurPage }]" @click="cnCurPage = p">{{ p }}</button>
        </template>
        <button class="page-btn" :disabled="cnCurPage === cnTotalPages" @click="cnCurPage++">›</button>
      </div>
    </div>

  </AdminShell>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:         { type: Object, default: () => ({}) },
  csrfToken:    { type: String, default: '' },
  agents:       { type: Array,  default: () => [] },
  connectors:   { type: Array,  default: () => [] },
  flashSuccess: { type: String, default: '' },
  flashError:   { type: String, default: '' },
});

// ── Tab state ──
const urlTab = new URLSearchParams(window.location.search).get('tab');
const activeTab = ref(urlTab === 'connectors' ? 'connectors' : 'agents');

function setTab(t) {
  activeTab.value = t;
  const url = new URL(window.location.href);
  if (t === 'agents') url.searchParams.delete('tab');
  else url.searchParams.set('tab', t);
  window.history.replaceState({}, '', url.toString());
}

// ── Agents ──
const agentSearch = ref('');
const agentFilter = ref('All');
const agentFilters = ['All', 'Popular', 'Premium', 'New', 'Active', 'Inactive'];

const agentFiltered = computed(() => {
  return props.agents.filter(a => {
    const q = agentSearch.value.toLowerCase();
    const matchesSearch = !q ||
      a.name.toLowerCase().includes(q) ||
      (a.description || '').toLowerCase().includes(q);
    const matchesFilter =
      agentFilter.value === 'All' ||
      (agentFilter.value === 'Active'   && a.is_active) ||
      (agentFilter.value === 'Inactive' && !a.is_active) ||
      a.badge === agentFilter.value;
    return matchesSearch && matchesFilter;
  });
});

function confirmDelete(event, name) {
  if (confirm(`Delete agent "${name}"? This cannot be undone.`)) {
    event.target.submit();
  }
}

// ── Connectors ──
const cnSearch = ref('');
const cnFilter = ref('All');
const cnCurPage = ref(1);
const CN_PER_PAGE = 20;

const cnFilters = computed(() => {
  const cats = [...new Set(props.connectors.map(c => c.category).filter(Boolean))].sort();
  return ['All', ...cats, 'Active', 'Inactive'];
});

const cnFiltered = computed(() => {
  const q = cnSearch.value.toLowerCase();
  return props.connectors.filter(c => {
    const matchesSearch = !q ||
      c.name.toLowerCase().includes(q) ||
      (c.description || '').toLowerCase().includes(q) ||
      (c.category || '').toLowerCase().includes(q);
    const matchesFilter =
      cnFilter.value === 'All' ||
      (cnFilter.value === 'Active'   && c.is_active) ||
      (cnFilter.value === 'Inactive' && !c.is_active) ||
      c.category === cnFilter.value;
    return matchesSearch && matchesFilter;
  });
});

watch([cnSearch, cnFilter], () => { cnCurPage.value = 1; });

const cnTotalPages = computed(() => Math.max(1, Math.ceil(cnFiltered.value.length / CN_PER_PAGE)));
const cnPage_rows  = computed(() => {
  const start = (cnCurPage.value - 1) * CN_PER_PAGE;
  return cnFiltered.value.slice(start, start + CN_PER_PAGE);
});

const cnPageNumbers = computed(() => {
  const total = cnTotalPages.value;
  const cur   = cnCurPage.value;
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
  const pages = new Set([1, total, cur, cur - 1, cur + 1].filter(p => p >= 1 && p <= total));
  const sorted = [...pages].sort((a, b) => a - b);
  const result = [];
  for (let i = 0; i < sorted.length; i++) {
    if (i > 0 && sorted[i] - sorted[i - 1] > 1) result.push('…');
    result.push(sorted[i]);
  }
  return result;
});

function confirmDeleteCn(event, name) {
  if (confirm(`Delete connector "${name}"? This cannot be undone.`)) {
    event.target.submit();
  }
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.page-title  { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.page-sub    { color: #6b7280; font-size: 0.875rem; }

.btn-primary {
  display: inline-flex; align-items: center;
  padding: 0.6rem 1.1rem; border-radius: 0.625rem;
  background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35);
  color: #fca5a5; font-size: 0.875rem; font-weight: 600;
  text-decoration: none; transition: all 0.18s; white-space: nowrap;
  font-family: inherit; min-height: 44px;
}
.btn-primary:hover { background: rgba(239,68,68,0.25); }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

/* Top-level tab bar */
.top-tab-bar {
  display: flex; gap: 0.25rem;
  border-bottom: 1px solid rgba(239,68,68,0.1);
  margin-bottom: 1.5rem;
}
.top-tab {
  padding: 0.6rem 1.1rem; border-radius: 0.5rem 0.5rem 0 0;
  font-size: 0.9rem; font-weight: 600; cursor: pointer;
  background: none; border: 1px solid transparent; border-bottom: none;
  color: #6b7280; font-family: inherit; transition: all 0.18s;
  display: flex; align-items: center; gap: 0.5rem;
}
.top-tab:hover { color: #fca5a5; background: rgba(239,68,68,0.05); }
.top-tab.active {
  color: #fca5a5; background: rgba(239,68,68,0.08);
  border-color: rgba(239,68,68,0.2); border-bottom-color: transparent;
  position: relative; bottom: -1px;
}
.top-tab-count {
  font-size: 0.72rem; font-weight: 700;
  background: rgba(239,68,68,0.12); color: #fca5a5;
  border-radius: 99px; padding: 0.1rem 0.45rem;
}

.toolbar { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem; pointer-events: none; }
.search-input {
  width: 100%; padding: 0.625rem 0.875rem 0.625rem 2.25rem;
  background: rgba(24,10,10,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.625rem; color: #e5e7eb; font-size: 1rem; font-family: inherit;
  transition: border-color 0.18s;
}
.search-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.search-input::placeholder { color: #4b5563; }

.filter-tabs { display: flex; gap: 0.375rem; flex-shrink: 0; flex-wrap: wrap; }
.tab { padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; background: none; border: 1px solid rgba(239,68,68,0.12); color: #6b7280; font-family: inherit; transition: all 0.18s; min-height: 44px; }
.tab:hover { border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.tab.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; }

.table-wrap { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 560px; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.item-cell { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
.item-cell:hover .item-name { color: #fca5a5; }
.item-icon { width: 34px; height: 34px; border-radius: 0.5rem; flex-shrink: 0; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fca5a5; }
.item-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.item-slug { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; font-family: monospace; }

.badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.badge.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.badge.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.badge.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.status-badge.active   { background: rgba(0,217,126,0.1);  color: #00d97e; }
.status-badge.inactive { background: rgba(107,114,128,0.12); color: #9ca3af; }

.rating { color: #e5e7eb; font-size: 0.82rem; white-space: nowrap; }
.muted  { color: #6b7280; font-size: 0.875rem; }
.price  { color: #FCD34D; font-weight: 600; font-size: 0.875rem; white-space: nowrap; }
.url-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; }
.url-link:hover { color: #fca5a5; }

.actions { text-align: right; white-space: nowrap; display: flex; align-items: center; justify-content: flex-end; gap: 0.375rem; }
.btn-ghost  { display: inline-block; padding: 0.35rem 0.6rem; border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem; color: #9ca3af; font-size: 0.78rem; text-decoration: none; transition: all 0.18s; }
.btn-ghost:hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.btn-danger { display: inline-block; padding: 0.35rem 0.6rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 600; cursor: pointer; border: none; font-family: inherit; background: rgba(239,68,68,0.1); color: #fca5a5; transition: all 0.18s; }
.btn-danger:hover { background: rgba(239,68,68,0.25); }

.pagination { display: flex; align-items: center; justify-content: center; gap: 0.375rem; margin-top: 1.25rem; flex-wrap: wrap; }
.page-btn {
  min-width: 36px; height: 36px; border-radius: 0.4rem;
  border: 1px solid rgba(239,68,68,0.15); background: none;
  color: #9ca3af; font-size: 0.82rem; font-family: inherit;
  cursor: pointer; transition: all 0.18s; padding: 0 0.5rem;
}
.page-btn:hover:not(:disabled) { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.page-btn.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; font-weight: 700; }
.page-btn:disabled { opacity: 0.35; cursor: default; }
.page-ellipsis { color: #4b5563; font-size: 0.82rem; padding: 0 0.25rem; }

@media (max-width: 640px) {
  .toolbar { flex-direction: column; align-items: stretch; }
  .page-title { font-size: 1.3rem; }
}
</style>
