<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="users">

    <div class="page-header">
      <h1 class="page-title">Users</h1>
      <p class="page-sub">{{ filtered.length }} of {{ users.length }} users</p>
    </div>

    <!-- Flash messages -->
    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError" class="flash error">{{ flashError }}</div>

    <!-- Search + filter -->
    <div class="toolbar">
      <div class="search-wrap">
        <span class="search-icon">◎</span>
        <input
          v-model="search"
          type="text"
          placeholder="Search by name or email…"
          class="search-input"
        >
      </div>
      <div class="filter-tabs">
        <button :class="['tab', { active: filter === 'all' }]" @click="filter = 'all'">All</button>
        <button :class="['tab', { active: filter === 'admin' }]" @click="filter = 'admin'">Admins</button>
        <button :class="['tab', { active: filter === 'user' }]" @click="filter = 'user'">Users</button>
      </div>
    </div>

    <!-- Table -->
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Role</th>
            <th>Joined</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in filtered" :key="u.id">
            <td>
              <div class="user-cell">
                <div class="mini-avatar">{{ u.name.charAt(0).toUpperCase() }}</div>
                <div>
                  <div class="user-name">{{ u.name }}</div>
                  <div class="user-email">{{ u.email }}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="role-badge" :class="u.is_admin ? 'admin' : 'user'">
                {{ u.is_admin ? 'Admin' : 'User' }}
              </span>
            </td>
            <td class="muted small">{{ formatDate(u.created_at) }}</td>
            <td class="actions">
              <form v-if="u.id !== user.id" method="POST" :action="`/admin/users/${u.id}/toggle-admin`" style="display:inline;">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" class="btn-toggle" :class="u.is_admin ? 'demote' : 'promote'">
                  {{ u.is_admin ? 'Remove Admin' : 'Make Admin' }}
                </button>
              </form>
            </td>
          </tr>
          <tr v-if="filtered.length === 0">
            <td colspan="4" style="text-align:center; padding:2.5rem; color:#4b5563;">No users found</td>
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
  user: { type: Object, default: () => ({}) },
  csrfToken: { type: String, default: '' },
  users: { type: Array, default: () => [] },
  flashSuccess: { type: String, default: '' },
  flashError: { type: String, default: '' },
});

const search = ref('');
const filter = ref('all');

const filtered = computed(() => {
  return props.users.filter(u => {
    const matchesSearch = !search.value ||
      u.name.toLowerCase().includes(search.value.toLowerCase()) ||
      u.email.toLowerCase().includes(search.value.toLowerCase());
    const matchesFilter = filter.value === 'all' ||
      (filter.value === 'admin' && u.is_admin) ||
      (filter.value === 'user' && !u.is_admin);
    return matchesSearch && matchesFilter;
  });
});

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.page-sub { color: #6b7280; font-size: 0.875rem; }

.flash {
  padding: 0.75rem 1rem; border-radius: 0.625rem;
  font-size: 0.875rem; margin-bottom: 1.25rem;
}
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.toolbar {
  display: flex; align-items: center; gap: 1rem;
  margin-bottom: 1.25rem; flex-wrap: wrap;
}
.search-wrap { position: relative; flex: 1; min-width: 200px; }
.search-icon {
  position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);
  color: #6b7280; font-size: 0.875rem; pointer-events: none;
}
.search-input {
  width: 100%; padding: 0.625rem 0.875rem 0.625rem 2.25rem;
  background: rgba(24,10,10,0.8);
  border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.625rem; color: #e5e7eb; font-size: 1rem;
  font-family: inherit; transition: border-color 0.18s;
}
.search-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.search-input::placeholder { color: #4b5563; }

.filter-tabs { display: flex; gap: 0.375rem; flex-shrink: 0; }
.tab {
  padding: 0.5rem 0.875rem; border-radius: 0.5rem;
  font-size: 0.82rem; font-weight: 600; cursor: pointer;
  background: none; border: 1px solid rgba(239,68,68,0.12);
  color: #6b7280; font-family: inherit; transition: all 0.18s;
  min-height: 44px;
}
.tab:hover { border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.tab.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; }

.table-wrap {
  background: rgba(24,10,10,0.6);
  border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; overflow: hidden; overflow-x: auto;
}
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 520px; }
.data-table th {
  padding: 0.75rem 1rem; text-align: left;
  font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.06em; color: #6b7280;
  border-bottom: 1px solid rgba(239,68,68,0.08);
  background: rgba(239,68,68,0.03);
}
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.user-cell { display: flex; align-items: center; gap: 0.75rem; }
.mini-avatar {
  width: 32px; height: 32px; border-radius: 50%;
  background: linear-gradient(135deg, #ef4444, #fca5a5);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.75rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.user-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.user-email { font-size: 0.775rem; color: #6b7280; margin-top: 0.1rem; }
.muted { color: #6b7280; }
.small { font-size: 0.8rem; white-space: nowrap; }

.role-badge {
  display: inline-block; padding: 0.18rem 0.55rem;
  border-radius: 99px; font-size: 0.72rem; font-weight: 600;
}
.role-badge.admin { background: rgba(239,68,68,0.12); color: #fca5a5; }
.role-badge.user  { background: rgba(107,114,128,0.12); color: #9ca3af; }

.actions { text-align: right; white-space: nowrap; }
.btn-ghost {
  display: inline-block; padding: 0.35rem 0.7rem;
  border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem;
  color: #9ca3af; font-size: 0.78rem; text-decoration: none;
  transition: all 0.18s; margin-right: 0.4rem;
}
.btn-ghost:hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.btn-toggle {
  display: inline-block; padding: 0.35rem 0.7rem;
  border-radius: 0.4rem; font-size: 0.78rem; font-weight: 600;
  cursor: pointer; border: none; font-family: inherit; transition: all 0.18s;
}
.btn-toggle.promote { background: rgba(239,68,68,0.1); color: #fca5a5; }
.btn-toggle.promote:hover { background: rgba(239,68,68,0.2); }
.btn-toggle.demote { background: rgba(107,114,128,0.1); color: #9ca3af; }
.btn-toggle.demote:hover { background: rgba(107,114,128,0.2); }

@media (max-width: 640px) {
  .toolbar { flex-direction: column; align-items: stretch; }
  .search-wrap { min-width: unset; }
  .page-title { font-size: 1.3rem; }
}
</style>
