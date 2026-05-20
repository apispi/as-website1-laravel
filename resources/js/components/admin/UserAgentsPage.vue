<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="users">

    <div class="page-header">
      <a :href="`/admin/users/${subject.id}`" class="back-link">← {{ subject.name }}</a>
      <h1 class="page-title">Purchased Agents</h1>
      <p class="page-sub">{{ subject.name }}'s agent subscriptions</p>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>

    <!-- Assign form -->
    <div class="assign-card">
      <div class="assign-title">Assign Agent</div>
      <form method="POST" :action="`/admin/users/${subject.id}/agents`" class="assign-form">
        <input type="hidden" name="_token" :value="csrfToken">

        <div class="assign-field">
          <label>Agent</label>
          <select name="agent_id" required :disabled="availableAgents.length === 0">
            <option value="" disabled selected>{{ availableAgents.length ? 'Select an agent…' : 'All agents already assigned' }}</option>
            <option v-for="a in availableAgents" :key="a.id" :value="a.id">
              {{ a.name }}{{ a.price ? ' — ' + a.price : '' }}
            </option>
          </select>
        </div>

        <div class="assign-field">
          <label>Status</label>
          <select name="status">
            <option value="active">Active</option>
            <option value="cancelled">Cancelled</option>
            <option value="expired">Expired</option>
          </select>
        </div>

        <div class="assign-field">
          <label>Expires (optional)</label>
          <input type="date" name="expires_at" placeholder="Leave blank for ongoing">
        </div>

        <button type="submit" class="btn-assign" :disabled="availableAgents.length === 0">Assign Agent</button>
      </form>
    </div>

    <!-- Subscriptions table -->
    <div v-if="subscriptions.length === 0" class="empty-state">
      <div class="empty-icon">◈</div>
      <div class="empty-title">No agents assigned</div>
      <div class="empty-desc">Use the form above to assign an agent to this user.</div>
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
            <th></th>
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
            <td class="actions">
              <form method="POST" :action="`/admin/users/${subject.id}/agents/${sub.id}`"
                    @submit.prevent="confirmRevoke($event, sub.agent?.name)">
                <input type="hidden" name="_token" :value="csrfToken">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn-revoke">Revoke</button>
              </form>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </AdminShell>
</template>

<script setup>
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:     { type: Object, default: () => ({}) },
  csrfToken:       { type: String, default: '' },
  subject:         { type: Object, default: () => ({}) },
  subscriptions:   { type: Array,  default: () => [] },
  availableAgents: { type: Array,  default: () => [] },
  flashSuccess:    { type: String, default: '' },
  flashError:      { type: String, default: '' },
});

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function confirmRevoke(event, name) {
  if (confirm(`Remove "${name ?? 'this agent'}" from ${props.subject.name}?`)) {
    event.target.submit();
  }
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.4rem; }
.back-link:hover { color: #fca5a5; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.page-sub   { color: #6b7280; font-size: 0.875rem; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

/* Assign card */
.assign-card {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.12);
  border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.5rem; max-width: 860px;
}
.assign-title { font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #6b7280; margin-bottom: 1rem; }
.assign-form { display: flex; gap: 0.875rem; align-items: flex-end; flex-wrap: wrap; }
.assign-field { display: flex; flex-direction: column; gap: 0.3rem; flex: 1; min-width: 160px; }
.assign-field label { font-size: 0.78rem; font-weight: 600; color: #d1d5db; }
select, input[type="date"] {
  padding: 0.6rem 0.75rem; background: rgba(12,4,4,0.8);
  border: 1px solid rgba(239,68,68,0.15); border-radius: 0.5rem;
  color: #e5e7eb; font-size: 1rem; font-family: inherit; width: 100%;
  transition: border-color 0.18s;
}
select:focus, input[type="date"]:focus { outline: none; border-color: rgba(239,68,68,0.4); }
select option { background: #140606; }
select:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-assign {
  padding: 0.65rem 1.25rem; border-radius: 0.625rem; min-height: 44px;
  background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35);
  color: #fca5a5; font-size: 0.875rem; font-weight: 600; cursor: pointer;
  font-family: inherit; transition: all 0.18s; white-space: nowrap; flex-shrink: 0;
}
.btn-assign:hover:not(:disabled) { background: rgba(239,68,68,0.25); }
.btn-assign:disabled { opacity: 0.45; cursor: not-allowed; }

/* Empty state */
.empty-state {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px;
}
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; }

/* Table */
.table-wrap { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; max-width: 860px; }
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
.actions { text-align: right; }

.btn-revoke { padding: 0.35rem 0.65rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 600; cursor: pointer; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; font-family: inherit; transition: all 0.18s; }
.btn-revoke:hover { background: rgba(239,68,68,0.2); }

@media (max-width: 640px) {
  .assign-form { flex-direction: column; }
  .assign-field { min-width: unset; }
  .page-title { font-size: 1.3rem; }
}
</style>
