<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="activity-log">

    <div class="page-header">
      <h1 class="page-title">Activity Log</h1>
      <p class="page-sub">{{ pagination.total }} total events</p>
    </div>

    <!-- Search + filter -->
    <form method="GET" action="/admin/activity" class="toolbar">
      <div class="search-wrap">
        <span class="search-icon">◎</span>
        <input
          type="text"
          name="search"
          :value="filters.search"
          placeholder="Search user or description…"
          class="search-input"
        >
        <input type="hidden" name="filter" :value="filters.filter">
      </div>
      <div class="filter-tabs">
        <a :href="filterUrl('all')"   :class="['tab', { active: !filters.filter || filters.filter === 'all' }]">All</a>
        <a :href="filterUrl('auth')"  :class="['tab', { active: filters.filter === 'auth' }]">Auth</a>
        <a :href="filterUrl('profile')" :class="['tab', { active: filters.filter === 'profile' }]">Profile</a>
        <a :href="filterUrl('subscription')" :class="['tab', { active: filters.filter === 'subscription' }]">Subscriptions</a>
        <a :href="filterUrl('admin')" :class="['tab', { active: filters.filter === 'admin' }]">Admin</a>
      </div>
    </form>

    <!-- Table -->
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Time</th>
            <th>User</th>
            <th>Action</th>
            <th>Description</th>
            <th>IP</th>
            <th>By</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in logs" :key="log.id">
            <td class="muted small nowrap">{{ formatDate(log.created_at) }}</td>
            <td>
              <template v-if="log.user">
                <a :href="`/admin/users/${log.user.id}`" class="user-link">{{ log.user.name }}</a>
                <div class="user-email">{{ log.user.email }}</div>
              </template>
              <span v-else class="muted">—</span>
            </td>
            <td>
              <span class="action-badge" :class="actionClass(log.action)">{{ actionLabel(log.action) }}</span>
            </td>
            <td class="desc">{{ log.description }}</td>
            <td class="muted small nowrap">{{ log.ip_address ?? '—' }}</td>
            <td class="muted small">
              <template v-if="log.actor && log.actor.id !== log.user?.id">
                {{ log.actor.name }}
              </template>
              <span v-else>—</span>
            </td>
          </tr>
          <tr v-if="logs.length === 0">
            <td colspan="6" class="empty-row">No activity found</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.last_page > 1" class="pagination">
      <a v-if="pagination.prev_url" :href="pagination.prev_url" class="page-btn">← Prev</a>
      <span v-else class="page-btn disabled">← Prev</span>
      <span class="page-info">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
      <a v-if="pagination.next_url" :href="pagination.next_url" class="page-btn">Next →</a>
      <span v-else class="page-btn disabled">Next →</span>
    </div>

  </AdminShell>
</template>

<script setup>
import { computed } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:       { type: Object, default: () => ({}) },
  csrfToken:  { type: String, default: '' },
  logs:       { type: Array,  default: () => [] },
  pagination: { type: Object, default: () => ({}) },
  filters:    { type: Object, default: () => ({}) },
});

function filterUrl(filter) {
  const params = new URLSearchParams();
  if (props.filters.search) params.set('search', props.filters.search);
  if (filter && filter !== 'all') params.set('filter', filter);
  const qs = params.toString();
  return '/admin/activity' + (qs ? '?' + qs : '');
}

function formatDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso);
  return d.toLocaleDateString('en-AU', { day: 'numeric', month: 'short' })
    + ' ' + d.toLocaleTimeString('en-AU', { hour: '2-digit', minute: '2-digit', hour12: false });
}

function actionLabel(action) {
  const labels = {
    'login':               'Login',
    'logout':              'Logout',
    'register':            'Register',
    'profile.update':      'Profile',
    'password.change':     'Password',
    'admin.toggle':        'Admin',
    'subscription.assign': 'Assign',
    'subscription.revoke': 'Revoke',
  };
  return labels[action] ?? action;
}

function actionClass(action) {
  if (action === 'login' || action === 'register') return 'green';
  if (action === 'logout') return 'grey';
  if (action.startsWith('profile') || action.startsWith('password')) return 'blue';
  if (action === 'admin.toggle') return 'red';
  if (action === 'subscription.assign') return 'amber';
  if (action === 'subscription.revoke') return 'grey';
  return 'grey';
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.page-sub { color: #6b7280; font-size: 0.875rem; }

.toolbar {
  display: flex; align-items: center; gap: 1rem;
  margin-bottom: 1.25rem; flex-wrap: wrap;
}
.search-wrap { position: relative; flex: 1; min-width: 220px; }
.search-icon {
  position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);
  color: #6b7280; font-size: 0.875rem; pointer-events: none;
}
.search-input {
  width: 100%; padding: 0.625rem 0.875rem 0.625rem 2.25rem;
  background: rgba(24,10,10,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.625rem; color: #e5e7eb; font-size: 1rem;
  font-family: inherit; transition: border-color 0.18s;
}
.search-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.search-input::placeholder { color: #4b5563; }

.filter-tabs { display: flex; gap: 0.375rem; flex-wrap: wrap; flex-shrink: 0; }
.tab {
  padding: 0.5rem 0.75rem; border-radius: 0.5rem;
  font-size: 0.82rem; font-weight: 600; cursor: pointer;
  background: none; border: 1px solid rgba(239,68,68,0.12);
  color: #6b7280; font-family: inherit; transition: all 0.18s;
  text-decoration: none; min-height: 44px; display: inline-flex; align-items: center;
}
.tab:hover { border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.tab.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; }

.table-wrap {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; overflow: hidden; overflow-x: auto;
}
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 680px; }
.data-table th {
  padding: 0.75rem 1rem; text-align: left;
  font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.06em; color: #6b7280;
  border-bottom: 1px solid rgba(239,68,68,0.08);
  background: rgba(239,68,68,0.03);
}
.data-table td { padding: 0.75rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.05); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.1); }
.data-table tbody tr:hover .user-link { color: #fca5a5; }
.data-table tbody tr:hover .muted { color: #9ca3af; }

.muted { color: #6b7280; }
.small { font-size: 0.8rem; }
.nowrap { white-space: nowrap; }
.desc { max-width: 280px; }
.empty-row { text-align: center; padding: 2.5rem; color: #4b5563; }

.user-link { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; text-decoration: none; }
.user-link:hover { color: #fca5a5; }
.user-email { font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem; }

.action-badge {
  display: inline-block; padding: 0.2rem 0.55rem;
  border-radius: 99px; font-size: 0.7rem; font-weight: 700; white-space: nowrap;
}
.action-badge.green  { background: rgba(0,217,126,0.1);  color: #00d97e; }
.action-badge.grey   { background: rgba(107,114,128,0.1); color: #9ca3af; }
.action-badge.blue   { background: rgba(59,130,246,0.1);  color: #60a5fa; }
.action-badge.red    { background: rgba(239,68,68,0.12);  color: #fca5a5; }
.action-badge.amber  { background: rgba(217,119,6,0.12);  color: #fcd34d; }

.pagination {
  display: flex; align-items: center; gap: 1rem;
  margin-top: 1.25rem; justify-content: center;
}
.page-btn {
  padding: 0.5rem 1rem; border-radius: 0.5rem;
  border: 1px solid rgba(239,68,68,0.15); color: #9ca3af;
  font-size: 0.85rem; text-decoration: none; transition: all 0.18s;
}
.page-btn:not(.disabled):hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.page-btn.disabled { opacity: 0.35; cursor: not-allowed; }
.page-info { font-size: 0.82rem; color: #6b7280; }

@media (max-width: 640px) {
  .toolbar { flex-direction: column; align-items: stretch; }
  .page-title { font-size: 1.3rem; }
}
</style>
