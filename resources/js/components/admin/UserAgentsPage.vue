<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="users">

    <div class="page-header">
      <a :href="`/admin/users/${subject.id}`" class="back-link">← {{ subject.name }}</a>
      <h1 class="page-title">Purchased Agents</h1>
      <p class="page-sub">{{ subject.name }}'s active agent subscriptions</p>
    </div>

    <div v-if="subscriptions.length === 0" class="empty-state">
      <div class="empty-icon">◈</div>
      <div class="empty-title">No agents purchased</div>
      <div class="empty-desc">This user has not subscribed to any agents yet.</div>
      <a href="/agents" target="_blank" class="empty-link">Browse Agent Marketplace →</a>
    </div>

    <div v-else class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Agent</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Started</th>
            <th>Expires</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sub in subscriptions" :key="sub.id">
            <td>
              <div class="agent-cell">
                <div class="agent-icon">◈</div>
                <div>
                  <div class="agent-name">{{ sub.agent?.name ?? '—' }}</div>
                  <div v-if="sub.agent?.badge" class="badge-inline" :class="sub.agent.badge.toLowerCase()">{{ sub.agent.badge }}</div>
                </div>
              </div>
            </td>
            <td class="muted">{{ sub.agent?.category ?? '—' }}</td>
            <td class="price">{{ sub.agent?.price ?? '—' }}</td>
            <td>
              <span class="status-badge" :class="sub.status">{{ sub.status }}</span>
            </td>
            <td class="muted small">{{ formatDate(sub.started_at) }}</td>
            <td class="muted small">{{ sub.expires_at ? formatDate(sub.expires_at) : 'Ongoing' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </AdminShell>
</template>

<script setup>
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:   { type: Object, default: () => ({}) },
  csrfToken:     { type: String, default: '' },
  subject:       { type: Object, default: () => ({}) },
  subscriptions: { type: Array,  default: () => [] },
});

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.4rem; }
.back-link:hover { color: #fca5a5; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.page-sub   { color: #6b7280; font-size: 0.875rem; }

.empty-state {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px;
}
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; margin-bottom: 1.25rem; }
.empty-link  { font-size: 0.85rem; color: #ef4444; text-decoration: none; }
.empty-link:hover { text-decoration: underline; }

.table-wrap { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 600px; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.agent-cell { display: flex; align-items: center; gap: 0.75rem; }
.agent-icon { width: 32px; height: 32px; border-radius: 0.5rem; flex-shrink: 0; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; color: #fca5a5; }
.agent-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; margin-bottom: 0.2rem; }

.badge-inline { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; }
.badge-inline.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.badge-inline.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.badge-inline.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; }
.status-badge.active    { background: rgba(0,217,126,0.1);   color: #00d97e; }
.status-badge.cancelled { background: rgba(239,68,68,0.1);   color: #fca5a5; }
.status-badge.expired   { background: rgba(107,114,128,0.12); color: #9ca3af; }

.price { color: #FCD34D; font-weight: 600; white-space: nowrap; }
.muted { color: #6b7280; }
.small { font-size: 0.8rem; white-space: nowrap; }

@media (max-width: 640px) { .page-title { font-size: 1.3rem; } }
</style>
