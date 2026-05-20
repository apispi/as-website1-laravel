<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="dashboard">

    <div class="page-header">
      <h1 class="page-title">Admin</h1>
      <p class="page-sub">Platform overview and recent activity</p>
    </div>

    <!-- Stats -->
    <div class="stat-grid">
      <div v-for="stat in stats" :key="stat.label" class="stat-card">
        <div class="stat-icon">{{ stat.icon }}</div>
        <div class="stat-value">{{ stat.value }}</div>
        <div class="stat-label">{{ stat.label }}</div>
      </div>
    </div>

    <!-- Recent Users -->
    <section class="section">
      <div class="section-head">
        <h2 class="section-title">Recent Users</h2>
        <a href="/admin/users" class="view-all">View all →</a>
      </div>
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Joined</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="u in recentUsers" :key="u.id">
              <td>
                <div class="user-cell">
                  <div class="mini-avatar">{{ u.name.charAt(0).toUpperCase() }}</div>
                  <a :href="`/admin/users/${u.id}`" class="user-link">{{ u.name }}</a>
                </div>
              </td>
              <td class="muted">{{ u.email }}</td>
              <td>
                <span class="role-badge" :class="u.is_admin ? 'admin' : 'user'">
                  {{ u.is_admin ? 'Admin' : 'User' }}
                </span>
              </td>
              <td class="muted small">{{ formatDate(u.created_at) }}</td>
            </tr>
            <tr v-if="recentUsers.length === 0">
              <td colspan="4" style="text-align:center; padding:2rem; color:#4b5563;">No users yet</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </AdminShell>
</template>

<script setup>
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user: { type: Object, default: () => ({}) },
  csrfToken: { type: String, default: '' },
  stats: { type: Object, default: () => ({}) },
  recentUsers: { type: Array, default: () => [] },
});

const statCards = [
  { icon: '◈', label: 'Total Users', key: 'total_users' },
  { icon: '◎', label: 'New Today', key: 'new_today' },
  { icon: '◷', label: 'This Week', key: 'new_this_week' },
  { icon: '⬡', label: 'Admins', key: 'admin_users' },
];
const stats = statCards.map(s => ({ ...s, value: props.stats[s.key] ?? 0 }));

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 2rem; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.page-sub { color: #6b7280; font-size: 0.9rem; }

.stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
.stat-card {
  background: rgba(24,10,10,0.7);
  border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; padding: 1.25rem;
  text-align: center; transition: border-color 0.2s, transform 0.2s;
}
.stat-card:hover { border-color: rgba(239,68,68,0.3); transform: translateY(-2px); }
.stat-icon { font-size: 1.2rem; margin-bottom: 0.5rem; opacity: 0.6; }
.stat-value { font-size: 2rem; font-weight: 700; color: #fca5a5; line-height: 1; margin-bottom: 0.3rem; }
.stat-label { font-size: 0.78rem; color: #6b7280; }

.section { margin-bottom: 2rem; }
.section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.section-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; }
.view-all { font-size: 0.82rem; color: #ef4444; text-decoration: none; }
.view-all:hover { text-decoration: underline; }

.table-wrap {
  background: rgba(24,10,10,0.6);
  border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; overflow: hidden; overflow-x: auto;
}
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 480px; }
.data-table th {
  padding: 0.75rem 1rem; text-align: left;
  font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.06em; color: #6b7280;
  border-bottom: 1px solid rgba(239,68,68,0.08);
  background: rgba(239,68,68,0.03);
}
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.user-cell { display: flex; align-items: center; gap: 0.625rem; }
.mini-avatar {
  width: 28px; height: 28px; border-radius: 50%;
  background: linear-gradient(135deg, #ef4444, #fca5a5);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.72rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.user-link { color: #e5e7eb; text-decoration: none; font-weight: 500; }
.user-link:hover { color: #fca5a5; }
.muted { color: #6b7280; }
.small { font-size: 0.8rem; }

.role-badge {
  display: inline-block; padding: 0.18rem 0.55rem;
  border-radius: 99px; font-size: 0.72rem; font-weight: 600;
}
.role-badge.admin { background: rgba(239,68,68,0.12); color: #fca5a5; }
.role-badge.user  { background: rgba(107,114,128,0.12); color: #9ca3af; }

@media (max-width: 768px) {
  .stat-grid { grid-template-columns: repeat(2, 1fr); }
  .page-title { font-size: 1.3rem; }
}
@media (max-width: 400px) {
  .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 0.625rem; }
  .stat-value { font-size: 1.6rem; }
}
</style>
