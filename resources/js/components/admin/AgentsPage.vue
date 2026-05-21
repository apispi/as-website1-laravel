<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="agents">

    <div class="page-header">
      <div>
        <h1 class="page-title">Agent Catalog</h1>
        <p class="page-sub">{{ filtered.length }} of {{ agents.length }} agents</p>
      </div>
      <a href="/admin/agents/create" class="btn-primary">+ New Agent</a>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>

    <!-- Search + filter -->
    <div class="toolbar">
      <div class="search-wrap">
        <span class="search-icon">◎</span>
        <input v-model="search" type="text" placeholder="Search agents…" class="search-input">
      </div>
      <div class="filter-tabs">
        <button v-for="f in filters" :key="f" :class="['tab', { active: filter === f }]" @click="filter = f">
          {{ f }}
        </button>
      </div>
    </div>

    <!-- Table -->
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
          <tr v-for="agent in filtered" :key="agent.id">
            <td>
              <a :href="`/admin/agents/${agent.id}/edit`" class="agent-cell">
                <div class="agent-icon">◈</div>
                <div>
                  <div class="agent-name">{{ agent.name }}</div>
                  <div class="agent-slug">{{ agent.slug }}</div>
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
          <tr v-if="filtered.length === 0">
            <td colspan="7" style="text-align:center; padding:2.5rem; color:#4b5563;">No agents found</td>
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
  user:         { type: Object, default: () => ({}) },
  csrfToken:    { type: String, default: '' },
  agents:       { type: Array, default: () => [] },
  flashSuccess: { type: String, default: '' },
  flashError:   { type: String, default: '' },
});

const search = ref('');
const filter = ref('All');
const filters = ['All', 'Popular', 'Premium', 'New', 'Active', 'Inactive'];

const filtered = computed(() => {
  return props.agents.filter(a => {
    const matchesSearch = !search.value ||
      a.name.toLowerCase().includes(search.value.toLowerCase()) ||
      (a.description || '').toLowerCase().includes(search.value.toLowerCase());
    const matchesFilter =
      filter.value === 'All' ||
      (filter.value === 'Active' && a.is_active) ||
      (filter.value === 'Inactive' && !a.is_active) ||
      a.badge === filter.value;
    return matchesSearch && matchesFilter;
  });
});

function confirmDelete(event, name) {
  if (confirm(`Delete agent "${name}"? This cannot be undone.`)) {
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
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 700px; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.agent-cell { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
.agent-cell:hover .agent-name { color: #fca5a5; }
.agent-icon { width: 34px; height: 34px; border-radius: 0.5rem; flex-shrink: 0; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fca5a5; }
.agent-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.agent-slug { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; font-family: monospace; }

.badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.badge.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.badge.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.badge.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.status-badge.active   { background: rgba(0,217,126,0.1);  color: #00d97e; }
.status-badge.inactive { background: rgba(107,114,128,0.12); color: #9ca3af; }

.rating { color: #e5e7eb; font-size: 0.82rem; white-space: nowrap; }
.muted  { color: #6b7280; font-size: 0.82rem; }
.price  { color: #FCD34D; font-weight: 600; font-size: 0.875rem; white-space: nowrap; }

.actions { text-align: right; white-space: nowrap; display: flex; align-items: center; justify-content: flex-end; gap: 0.375rem; }
.btn-ghost { display: inline-block; padding: 0.35rem 0.6rem; border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem; color: #9ca3af; font-size: 0.78rem; text-decoration: none; transition: all 0.18s; }
.btn-ghost:hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.btn-danger { display: inline-block; padding: 0.35rem 0.6rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 600; cursor: pointer; border: none; font-family: inherit; background: rgba(239,68,68,0.1); color: #fca5a5; transition: all 0.18s; }
.btn-danger:hover { background: rgba(239,68,68,0.25); }

@media (max-width: 640px) {
  .toolbar { flex-direction: column; align-items: stretch; }
  .page-title { font-size: 1.3rem; }
}
</style>
