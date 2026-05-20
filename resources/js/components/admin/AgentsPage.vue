<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="agents">

    <div class="page-header">
      <h1 class="page-title">Agents</h1>
      <p class="page-sub">{{ filtered.length }} of {{ agents.length }} agents</p>
    </div>

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
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="agent in filtered" :key="agent.slug">
            <td>
              <div class="agent-cell">
                <div class="agent-icon">◈</div>
                <div>
                  <div class="agent-name">{{ agent.name }}</div>
                  <div class="agent-desc">{{ agent.description }}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="badge" :class="agent.badge.toLowerCase()">{{ agent.badge }}</span>
            </td>
            <td class="rating">⭐ {{ agent.rating }}</td>
            <td class="muted">{{ agent.users }}</td>
            <td class="price">{{ agent.price }}</td>
            <td class="actions">
              <a :href="`/agents/${agent.slug}`" target="_blank" class="btn-ghost">View →</a>
            </td>
          </tr>
          <tr v-if="filtered.length === 0">
            <td colspan="6" style="text-align:center; padding:2.5rem; color:#4b5563;">No agents found</td>
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
  user:      { type: Object, default: () => ({}) },
  csrfToken: { type: String, default: '' },
  agents:    { type: Array, default: () => [] },
});

const search = ref('');
const filter = ref('All');
const filters = ['All', 'Popular', 'Premium', 'New'];

const filtered = computed(() => {
  return props.agents.filter(a => {
    const matchesSearch = !search.value ||
      a.name.toLowerCase().includes(search.value.toLowerCase()) ||
      a.description.toLowerCase().includes(search.value.toLowerCase());
    const matchesFilter = filter.value === 'All' || a.badge === filter.value;
    return matchesSearch && matchesFilter;
  });
});
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.page-title  { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.page-sub    { color: #6b7280; font-size: 0.875rem; }

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

.filter-tabs { display: flex; gap: 0.375rem; flex-shrink: 0; }
.tab {
  padding: 0.5rem 0.875rem; border-radius: 0.5rem; font-size: 0.82rem; font-weight: 600;
  cursor: pointer; background: none; border: 1px solid rgba(239,68,68,0.12);
  color: #6b7280; font-family: inherit; transition: all 0.18s; min-height: 44px;
}
.tab:hover { border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.tab.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; }

.table-wrap {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; overflow: hidden; overflow-x: auto;
}
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 620px; }
.data-table th {
  padding: 0.75rem 1rem; text-align: left;
  font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em;
  color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03);
}
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.agent-cell { display: flex; align-items: flex-start; gap: 0.75rem; }
.agent-icon {
  width: 34px; height: 34px; border-radius: 0.5rem; flex-shrink: 0;
  background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; color: #fca5a5; margin-top: 0.1rem;
}
.agent-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; margin-bottom: 0.2rem; }
.agent-desc { font-size: 0.775rem; color: #6b7280; line-height: 1.4; max-width: 340px; }

.badge {
  display: inline-block; padding: 0.2rem 0.55rem;
  border-radius: 99px; font-size: 0.72rem; font-weight: 600;
}
.badge.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.badge.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.badge.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.rating { color: #e5e7eb; font-size: 0.82rem; white-space: nowrap; }
.muted  { color: #6b7280; font-size: 0.82rem; white-space: nowrap; }
.price  { color: #FCD34D; font-weight: 600; font-size: 0.875rem; white-space: nowrap; }

.actions { text-align: right; white-space: nowrap; }
.btn-ghost {
  display: inline-block; padding: 0.35rem 0.7rem;
  border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem;
  color: #9ca3af; font-size: 0.78rem; text-decoration: none; transition: all 0.18s;
}
.btn-ghost:hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }

@media (max-width: 640px) {
  .toolbar { flex-direction: column; align-items: stretch; }
  .page-title { font-size: 1.3rem; }
}
</style>
