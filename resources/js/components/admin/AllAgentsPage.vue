<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="all-agents">

    <div class="page-header">
      <h1 class="page-title">All Agents</h1>
      <p class="page-sub">All agent subscriptions across all users</p>
    </div>

    <!-- Summary -->
    <div class="stat-row">
      <div class="stat-pill">
        <span class="stat-num">{{ subscriptions.length }}</span> total
      </div>
      <div class="stat-pill">
        <span class="stat-num">{{ activeCount }}</span> active
      </div>
      <div class="stat-pill">
        <span class="stat-num">{{ uniqueUsers }}</span> users
      </div>
      <div class="stat-pill">
        <span class="stat-num">{{ uniqueAgents }}</span> agents
      </div>
    </div>

    <div v-if="subscriptions.length === 0" class="empty-state">
      <div class="empty-icon">◈</div>
      <div class="empty-title">No subscriptions yet</div>
      <div class="empty-desc">Assign agents to users from the Users section.</div>
    </div>

    <div v-else class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Agent</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Started</th>
            <th>Expires</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sub in subscriptions" :key="sub.id">
            <td>
              <a :href="`/admin/users/${sub.user?.id}`" class="user-link">
                <div class="user-name">{{ sub.user?.name ?? '—' }}</div>
                <div class="user-email">{{ sub.user?.email ?? '' }}</div>
              </a>
            </td>
            <td>
              <div class="agent-cell">
                <div class="agent-icon">◈</div>
                <div>
                  <div class="agent-name">{{ sub.agent?.name ?? '—' }}</div>
                  <span v-if="sub.agent?.badge" class="badge-inline" :class="sub.agent.badge.toLowerCase()">{{ sub.agent.badge }}</span>
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
            <td class="actions">
              <a :href="`/admin/users/${sub.user?.id}/agents`" class="btn-view">Manage →</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </AdminShell>
</template>

<script setup>
import { computed } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:   { type: Object, default: () => ({}) },
  csrfToken:     { type: String, default: '' },
  subscriptions: { type: Array,  default: () => [] },
});

const activeCount  = computed(() => props.subscriptions.filter(s => s.status === 'active').length);
const uniqueUsers  = computed(() => new Set(props.subscriptions.map(s => s.user?.id).filter(Boolean)).size);
const uniqueAgents = computed(() => new Set(props.subscriptions.map(s => s.agent?.id).filter(Boolean)).size);

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.page-sub   { color: #6b7280; font-size: 0.875rem; }

.stat-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
.stat-pill {
  padding: 0.45rem 0.9rem; border-radius: 99px;
  background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.15);
  font-size: 0.82rem; color: #9ca3af;
}
.stat-num { font-weight: 700; color: #fca5a5; }

.empty-state {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px;
}
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; }

.table-wrap { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 750px; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.user-link { text-decoration: none; }
.user-name  { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.user-email { font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem; }
.user-link:hover .user-name { color: #fca5a5; }

.agent-cell { display: flex; align-items: center; gap: 0.75rem; }
.agent-icon { width: 30px; height: 30px; border-radius: 0.4rem; flex-shrink: 0; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.18); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: #fca5a5; }
.agent-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; margin-bottom: 0.15rem; }

.badge-inline { display: inline-block; padding: 0.12rem 0.4rem; border-radius: 99px; font-size: 0.65rem; font-weight: 600; }
.badge-inline.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.badge-inline.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.badge-inline.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; }
.status-badge.active    { background: rgba(0,217,126,0.1);   color: #00d97e; }
.status-badge.cancelled { background: rgba(239,68,68,0.1);   color: #fca5a5; }
.status-badge.expired   { background: rgba(107,114,128,0.12); color: #9ca3af; }

.price   { color: #FCD34D; font-weight: 600; white-space: nowrap; }
.muted   { color: #6b7280; }
.small   { font-size: 0.8rem; white-space: nowrap; }
.actions { text-align: right; }

.btn-view { font-size: 0.78rem; font-weight: 600; color: #fca5a5; text-decoration: none; padding: 0.35rem 0.65rem; border-radius: 0.4rem; border: 1px solid rgba(239,68,68,0.2); background: rgba(239,68,68,0.06); transition: all 0.18s; white-space: nowrap; }
.btn-view:hover { background: rgba(239,68,68,0.15); }

@media (max-width: 640px) {
  .page-title { font-size: 1.3rem; }
}
</style>
