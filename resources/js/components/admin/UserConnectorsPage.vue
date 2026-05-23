<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="user-connectors">

    <div class="page-header">
      <h1 class="page-title">User Connectors</h1>
      <p class="page-sub">All connector instances across all users</p>
    </div>

    <!-- Summary -->
    <div class="stat-row">
      <div class="stat-pill"><span class="stat-num">{{ userConnectors.length }}</span> total</div>
      <div class="stat-pill"><span class="stat-num">{{ activeCount }}</span> active</div>
      <div class="stat-pill"><span class="stat-num">{{ uniqueUsers }}</span> users</div>
      <div class="stat-pill"><span class="stat-num">{{ uniqueConnectors }}</span> connectors</div>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>

    <!-- Toolbar -->
    <div class="toolbar">
      <div class="search-wrap">
        <span class="search-icon">◎</span>
        <input v-model="search" type="text" placeholder="Search by user or connector…" class="search-input">
      </div>
      <div class="filter-tabs">
        <button v-for="f in ['All', 'Active', 'Disconnected']" :key="f"
                :class="['tab', { active: filter === f }]" @click="filter = f">{{ f }}</button>
      </div>
    </div>

    <div v-if="filtered.length === 0" class="empty-state">
      <div class="empty-icon">⬡</div>
      <div class="empty-title">No user connectors yet</div>
      <div class="empty-desc">Users connect their accounts via the dashboard.</div>
    </div>

    <div v-else class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Connector</th>
            <th>User</th>
            <th>Status</th>
            <th>Connected</th>
            <th>Disconnected</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="uc in filtered" :key="uc.id" class="clickable-row"
              @click="navigate(`/admin/users/${uc.user?.id}/connectors/${uc.id}/edit`)">
            <td>
              <div class="connector-cell">
                <div class="connector-icon">{{ uc.connector?.icon || '⬡' }}</div>
                <div>
                  <div class="connector-name">{{ uc.connector?.name ?? '—' }}</div>
                  <div class="connector-cat">{{ uc.connector?.category ?? '' }}</div>
                </div>
              </div>
            </td>
            <td>
              <a :href="`/admin/users/${uc.user?.id}`" class="user-link" @click.stop>
                <div class="user-name">{{ uc.user?.name ?? '—' }}</div>
                <div class="user-email">{{ uc.user?.email ?? '' }}</div>
              </a>
            </td>
            <td>
              <span class="status-badge" :class="uc.status">{{ uc.status }}</span>
            </td>
            <td class="muted small">{{ uc.connected_at ? formatDate(uc.connected_at) : '—' }}</td>
            <td class="muted small">{{ uc.disconnected_at ? formatDate(uc.disconnected_at) : '—' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </AdminShell>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:           { type: Object, default: () => ({}) },
  csrfToken:      { type: String, default: '' },
  userConnectors: { type: Array,  default: () => [] },
  flashSuccess:   { type: String, default: '' },
  flashError:     { type: String, default: '' },
});

const search = ref('');
const filter = ref('All');

const activeCount      = computed(() => props.userConnectors.filter(u => u.status === 'active').length);
const uniqueUsers      = computed(() => new Set(props.userConnectors.map(u => u.user?.id).filter(Boolean)).size);
const uniqueConnectors = computed(() => new Set(props.userConnectors.map(u => u.connector?.id).filter(Boolean)).size);

const filtered = computed(() => {
  const q = search.value.toLowerCase();
  return props.userConnectors.filter(uc => {
    const matchesSearch = !q ||
      (uc.user?.name || '').toLowerCase().includes(q) ||
      (uc.user?.email || '').toLowerCase().includes(q) ||
      (uc.connector?.name || '').toLowerCase().includes(q);
    const matchesFilter =
      filter.value === 'All' ||
      (filter.value === 'Active'       && uc.status === 'active') ||
      (filter.value === 'Disconnected' && uc.status === 'disconnected');
    return matchesSearch && matchesFilter;
  });
});

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function navigate(url) {
  window.location.href = url;
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.page-title  { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.page-sub    { color: #6b7280; font-size: 0.875rem; }

.stat-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
.stat-pill { padding: 0.45rem 0.9rem; border-radius: 99px; background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.15); font-size: 0.82rem; color: #9ca3af; }
.stat-num  { font-weight: 700; color: #fca5a5; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.toolbar { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem; pointer-events: none; }
.search-input { width: 100%; padding: 0.625rem 0.875rem 0.625rem 2.25rem; background: rgba(24,10,10,0.8); border: 1px solid rgba(239,68,68,0.15); border-radius: 0.625rem; color: #e5e7eb; font-size: 1rem; font-family: inherit; transition: border-color 0.18s; }
.search-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.search-input::placeholder { color: #4b5563; }
.filter-tabs { display: flex; gap: 0.375rem; flex-shrink: 0; }
.tab { padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; background: none; border: 1px solid rgba(239,68,68,0.12); color: #6b7280; font-family: inherit; transition: all 0.18s; min-height: 44px; }
.tab:hover { border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.tab.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; }

.empty-state { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px; }
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; }

.table-wrap  { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.data-table  { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 650px; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }
.clickable-row { cursor: pointer; }

.connector-cell { display: flex; align-items: center; gap: 0.75rem; }
.connector-icon { width: 32px; height: 32px; border-radius: 0.4rem; flex-shrink: 0; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.18); display: flex; align-items: center; justify-content: center; font-size: 1rem; }
.connector-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.connector-cat  { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; }

.user-link  { text-decoration: none; }
.user-name  { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.user-email { font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem; }
.user-link:hover .user-name { color: #fca5a5; }

.status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; }
.status-badge.active       { background: rgba(0,217,126,0.1);   color: #00d97e; }
.status-badge.disconnected { background: rgba(107,114,128,0.12); color: #9ca3af; }

.muted { color: #6b7280; }
.small { font-size: 0.8rem; white-space: nowrap; }
</style>
