<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="trainings">

    <div class="page-header">
      <div>
        <h1 class="page-title">Training Catalog</h1>
        <p class="page-sub">{{ filtered.length }} of {{ trainings.length }} courses</p>
      </div>
      <a href="/admin/trainings/create" class="btn-primary">+ New Training</a>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>

    <div class="toolbar">
      <div class="search-wrap">
        <span class="search-icon">◎</span>
        <input v-model="search" type="text" placeholder="Search courses…" class="search-input">
      </div>
      <div class="filter-tabs">
        <button v-for="f in filterOptions" :key="f" :class="['tab', { active: filter === f }]" @click="filter = f">
          {{ f }}
        </button>
      </div>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Course</th>
            <th>Format</th>
            <th>Level</th>
            <th>Duration</th>
            <th>Price</th>
            <th>Badge</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="t in filtered" :key="t.id">
            <td>
              <a :href="`/admin/trainings/${t.id}/edit`" class="course-cell">
                <div class="course-icon">◷</div>
                <div>
                  <div class="course-name">{{ t.title }}</div>
                  <div class="course-slug">{{ t.slug }}</div>
                </div>
              </a>
            </td>
            <td class="muted small">{{ t.format || '—' }}</td>
            <td class="muted small">{{ t.level || '—' }}</td>
            <td class="muted small">{{ t.duration || '—' }}</td>
            <td class="price">{{ t.price || '—' }}</td>
            <td>
              <span v-if="t.badge" class="badge" :class="t.badge.toLowerCase()">{{ t.badge }}</span>
              <span v-else class="muted">—</span>
            </td>
            <td>
              <span class="status-badge" :class="t.is_active ? 'active' : 'inactive'">
                {{ t.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="actions">
              <a :href="`/admin/trainings/${t.id}/edit`" class="btn-ghost">Edit</a>
              <form method="POST" :action="`/admin/trainings/${t.id}`" style="display:inline;"
                    @submit.prevent="confirmDelete($event, t.title)">
                <input type="hidden" name="_token" :value="csrfToken">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn-danger">Delete</button>
              </form>
            </td>
          </tr>
          <tr v-if="filtered.length === 0">
            <td colspan="8" style="text-align:center; padding:2.5rem; color:#4b5563;">No courses found</td>
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
  trainings:    { type: Array,  default: () => [] },
  flashSuccess: { type: String, default: '' },
  flashError:   { type: String, default: '' },
});

const search = ref('');
const filter = ref('All');
const filterOptions = ['All', 'Active', 'Inactive', 'Featured'];

const filtered = computed(() => {
  return props.trainings.filter(t => {
    const q = search.value.toLowerCase();
    const matchesSearch = !q || t.title.toLowerCase().includes(q) || t.slug.includes(q) || (t.category || '').toLowerCase().includes(q);
    const matchesFilter =
      filter.value === 'All' ? true :
      filter.value === 'Active'   ? t.is_active :
      filter.value === 'Inactive' ? !t.is_active :
      filter.value === 'Featured' ? t.is_featured : true;
    return matchesSearch && matchesFilter;
  });
});

function confirmDelete(e, name) {
  if (confirm(`Delete "${name}"? This cannot be undone.`)) e.target.submit();
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.page-sub { color: #6b7280; font-size: 0.875rem; }
.btn-primary {
  padding: 0.6rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem;
  background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3);
  color: #fca5a5; font-weight: 600; text-decoration: none;
  transition: all 0.18s; white-space: nowrap; min-height: 44px;
  display: inline-flex; align-items: center;
}
.btn-primary:hover { background: rgba(239,68,68,0.2); }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.toolbar { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem; pointer-events: none; }
.search-input {
  width: 100%; padding: 0.625rem 0.875rem 0.625rem 2.25rem;
  background: rgba(24,10,10,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.625rem; color: #e5e7eb; font-size: 1rem;
  font-family: inherit; transition: border-color 0.18s;
}
.search-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.search-input::placeholder { color: #4b5563; }
.filter-tabs { display: flex; gap: 0.375rem; flex-shrink: 0; }
.tab {
  padding: 0.5rem 0.875rem; border-radius: 0.5rem; font-size: 0.82rem; font-weight: 600; cursor: pointer;
  background: none; border: 1px solid rgba(239,68,68,0.12); color: #6b7280; font-family: inherit;
  transition: all 0.18s; min-height: 44px;
}
.tab:hover { border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.tab.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; }

.table-wrap {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; overflow: hidden; overflow-x: auto;
}
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 720px; }
.data-table th {
  padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600;
  text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280;
  border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03);
}
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.course-cell { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: inherit; }
.course-cell:hover .course-name { color: #fca5a5; }
.course-icon { width: 32px; height: 32px; border-radius: 8px; background: rgba(239,68,68,0.1); display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; color: #fca5a5; }
.course-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.course-slug { font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem; }

.muted { color: #6b7280; }
.small { font-size: 0.8rem; }
.price { color: #fcd34d; font-weight: 600; font-size: 0.875rem; }

.badge { display: inline-block; padding: 0.18rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.badge.popular       { background: rgba(217,119,6,0.2); color: #fcd34d; }
.badge.new           { background: rgba(0,217,126,0.1); color: #00d97e; }
.badge.certification { background: rgba(59,130,246,0.12); color: #60a5fa; }

.status-badge { display: inline-block; padding: 0.18rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.status-badge.active   { background: rgba(0,217,126,0.1); color: #00d97e; }
.status-badge.inactive { background: rgba(107,114,128,0.1); color: #9ca3af; }

.actions { text-align: right; white-space: nowrap; }
.btn-ghost {
  display: inline-block; padding: 0.35rem 0.7rem; border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.4rem; color: #9ca3af; font-size: 0.78rem; text-decoration: none;
  transition: all 0.18s; margin-right: 0.4rem;
}
.btn-ghost:hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.btn-danger {
  display: inline-block; padding: 0.35rem 0.7rem; border-radius: 0.4rem; font-size: 0.78rem;
  font-weight: 600; cursor: pointer; background: rgba(239,68,68,0.08);
  border: 1px solid rgba(239,68,68,0.2); color: #ef4444; font-family: inherit; transition: all 0.18s;
}
.btn-danger:hover { background: rgba(239,68,68,0.18); }

@media (max-width: 640px) {
  .toolbar { flex-direction: column; align-items: stretch; }
  .page-header { flex-direction: column; }
  .page-title { font-size: 1.3rem; }
}
</style>
