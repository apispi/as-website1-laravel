<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="connectors">

    <div class="page-header">
      <div>
        <h1 class="page-title">Connector Catalog</h1>
        <p class="page-sub">{{ filtered.length }} of {{ connectors.length }} connectors</p>
      </div>
      <a href="/admin/connectors/create" class="btn-primary">+ New Connector</a>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>

    <div class="toolbar">
      <div class="search-wrap">
        <span class="search-icon">◎</span>
        <input v-model="search" type="text" placeholder="Search connectors…" class="search-input">
      </div>
      <div class="filter-tabs">
        <button v-for="f in filters" :key="f" :class="['tab', { active: filter === f }]" @click="filter = f">
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
          <tr v-for="c in filtered" :key="c.id">
            <td>
              <a :href="`/admin/connectors/${c.id}/edit`" class="connector-cell">
                <div class="connector-icon">{{ c.icon || '⬡' }}</div>
                <div>
                  <div class="connector-name">{{ c.name }}</div>
                  <div class="connector-slug">{{ c.slug }}</div>
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
                    @submit.prevent="confirmDelete($event, c.name)">
                <input type="hidden" name="_token" :value="csrfToken">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn-danger">Delete</button>
              </form>
            </td>
          </tr>
          <tr v-if="filtered.length === 0">
            <td colspan="5" style="text-align:center; padding:2.5rem; color:#4b5563;">No connectors found</td>
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
  connectors:   { type: Array,  default: () => [] },
  flashSuccess: { type: String, default: '' },
  flashError:   { type: String, default: '' },
});

const search = ref('');
const filter = ref('All');

const filters = computed(() => {
  const cats = [...new Set(props.connectors.map(c => c.category).filter(Boolean))].sort();
  return ['All', ...cats, 'Active', 'Inactive'];
});

const filtered = computed(() => {
  return props.connectors.filter(c => {
    const q = search.value.toLowerCase();
    const matchesSearch = !q ||
      c.name.toLowerCase().includes(q) ||
      (c.description || '').toLowerCase().includes(q) ||
      (c.category || '').toLowerCase().includes(q);
    const matchesFilter =
      filter.value === 'All' ||
      (filter.value === 'Active'   && c.is_active) ||
      (filter.value === 'Inactive' && !c.is_active) ||
      c.category === filter.value;
    return matchesSearch && matchesFilter;
  });
});

function confirmDelete(event, name) {
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

.connector-cell { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
.connector-cell:hover .connector-name { color: #fca5a5; }
.connector-icon { width: 34px; height: 34px; border-radius: 0.5rem; flex-shrink: 0; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.connector-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.connector-slug { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; font-family: monospace; }

.url-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; }
.url-link:hover { color: #fca5a5; }

.status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.status-badge.active   { background: rgba(0,217,126,0.1);   color: #00d97e; }
.status-badge.inactive { background: rgba(107,114,128,0.12); color: #9ca3af; }

.muted { color: #6b7280; font-size: 0.875rem; }

.actions { text-align: right; white-space: nowrap; display: flex; align-items: center; justify-content: flex-end; gap: 0.375rem; }
.btn-ghost  { display: inline-block; padding: 0.35rem 0.6rem; border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem; color: #9ca3af; font-size: 0.78rem; text-decoration: none; transition: all 0.18s; }
.btn-ghost:hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.btn-danger { display: inline-block; padding: 0.35rem 0.6rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 600; cursor: pointer; border: none; font-family: inherit; background: rgba(239,68,68,0.1); color: #fca5a5; transition: all 0.18s; }
.btn-danger:hover { background: rgba(239,68,68,0.25); }

@media (max-width: 640px) {
  .toolbar { flex-direction: column; align-items: stretch; }
  .page-title { font-size: 1.3rem; }
}
</style>
