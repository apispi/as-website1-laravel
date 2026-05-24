<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="users">

    <div class="page-header">
      <a href="/admin/users" class="back-link">← Users</a>
      <div class="header-row">
        <div class="big-avatar">{{ subject.name.charAt(0).toUpperCase() }}</div>
        <div>
          <h1 class="page-title">{{ subject.name }}</h1>
          <div class="subject-email">{{ subject.email }}</div>
        </div>
        <span class="role-badge" :class="subject.is_admin ? 'admin' : 'user'">
          {{ subject.is_admin ? 'Admin' : 'User' }}
        </span>
      </div>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>

    <!-- Top-level tabs -->
    <div class="top-tab-bar">
      <button :class="['top-tab', { active: tab === 'details' }]"    @click="tab = 'details'">Details</button>
      <button :class="['top-tab', { active: tab === 'agents' }]"     @click="tab = 'agents'">
        Agents <span class="top-tab-count">{{ subscriptions.length }}</span>
      </button>
      <button :class="['top-tab', { active: tab === 'connectors' }]" @click="tab = 'connectors'">
        Connectors <span class="top-tab-count">{{ userConnectors.length }}</span>
      </button>
    </div>

    <!-- ── DETAILS TAB ── -->
    <div v-show="tab === 'details'" class="tab-content">
      <div class="detail-grid">

        <div class="card">
          <div class="card-header">Account Details</div>
          <div class="info-rows">
            <div class="info-row">
              <span class="info-label">Member since</span>
              <span class="info-val">{{ formatDate(subject.created_at) }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Email verified</span>
              <span class="info-val">{{ subject.email_verified_at ? formatDate(subject.email_verified_at) : 'Not verified' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">User ID</span>
              <span class="info-val mono">{{ subject.id }}</span>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">Admin Access</div>
          <div class="action-body">
            <div class="action-desc">{{ subject.is_admin ? 'This user has administrator access.' : 'This user is a regular member.' }}</div>
            <form v-if="subject.id !== currentUser.id" method="POST" :action="`/admin/users/${subject.id}/toggle-admin`">
              <input type="hidden" name="_token" :value="csrfToken">
              <button type="submit" class="btn-action" :class="subject.is_admin ? 'danger' : 'promote'">
                {{ subject.is_admin ? 'Remove Admin' : 'Grant Admin' }}
              </button>
            </form>
            <span v-else class="muted-note">This is your account</span>
          </div>
        </div>

      </div>
    </div>

    <!-- ── AGENTS TAB ── -->
    <div v-show="tab === 'agents'" class="tab-content">

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
            <input type="date" name="expires_at">
          </div>
          <button type="submit" class="btn-assign" :disabled="availableAgents.length === 0">Assign Agent</button>
        </form>
      </div>

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
                <div class="item-cell">
                  <div class="item-icon">◈</div>
                  <div>
                    <div class="item-name">{{ sub.agent?.name ?? '—' }}</div>
                    <span v-if="sub.agent?.badge" class="badge-inline" :class="sub.agent.badge.toLowerCase()">{{ sub.agent.badge }}</span>
                  </div>
                </div>
              </td>
              <td class="muted">{{ sub.agent?.category ?? '—' }}</td>
              <td class="price">{{ sub.agent?.price ?? '—' }}</td>
              <td><span class="status-badge" :class="sub.status">{{ sub.status }}</span></td>
              <td class="muted small">{{ formatDate(sub.started_at) }}</td>
              <td class="muted small">{{ sub.expires_at ? formatDate(sub.expires_at) : 'Ongoing' }}</td>
              <td class="actions">
                <form method="POST" :action="`/admin/users/${subject.id}/agents/${sub.id}`"
                      @submit.prevent="confirmRevoke($event, sub.agent?.name, 'agent')">
                  <input type="hidden" name="_token" :value="csrfToken">
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn-revoke">Revoke</button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── CONNECTORS TAB ── -->
    <div v-show="tab === 'connectors'" class="tab-content">

      <div class="assign-card">
        <div class="assign-title">Assign Connector</div>
        <form method="POST" :action="`/admin/users/${subject.id}/connectors`" class="assign-form">
          <input type="hidden" name="_token" :value="csrfToken">
          <div class="assign-field">
            <label>Connector</label>
            <select name="connector_id" required :disabled="availableConnectors.length === 0">
              <option value="" disabled selected>{{ availableConnectors.length ? 'Select a connector…' : 'All connectors already assigned' }}</option>
              <option v-for="c in availableConnectors" :key="c.id" :value="c.id">
                {{ c.icon ? c.icon + ' ' : '' }}{{ c.name }}{{ c.category ? ' — ' + c.category : '' }}
              </option>
            </select>
          </div>
          <div class="assign-field">
            <label>Status</label>
            <select name="status">
              <option value="active">Active</option>
              <option value="disconnected">Disconnected</option>
            </select>
          </div>
          <button type="submit" class="btn-assign" :disabled="availableConnectors.length === 0">Assign Connector</button>
        </form>
      </div>

      <div v-if="userConnectors.length === 0" class="empty-state">
        <div class="empty-icon">⬡</div>
        <div class="empty-title">No connectors assigned</div>
        <div class="empty-desc">Use the form above to assign a connector to this user.</div>
      </div>

      <div v-else class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Connector</th>
              <th>Category</th>
              <th>Type</th>
              <th>Status</th>
              <th>Connected</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="uc in userConnectors" :key="uc.id" class="clickable-row"
                @click="navigate(`/admin/users/${subject.id}/connectors/${uc.id}/edit`)">
              <td>
                <div class="item-cell">
                  <div class="item-icon">{{ uc.connector?.icon || '⬡' }}</div>
                  <div class="item-name">{{ uc.connector?.name ?? '—' }}</div>
                </div>
              </td>
              <td class="muted">{{ uc.connector?.category ?? '—' }}</td>
              <td>
                <span v-if="uc.connector?.is_oauth" class="type-badge oauth">OAuth</span>
                <span v-else class="type-badge api">API</span>
              </td>
              <td><span class="status-badge" :class="uc.status">{{ uc.status }}</span></td>
              <td class="muted small">{{ uc.connected_at ? formatDate(uc.connected_at) : '—' }}</td>
              <td class="actions" @click.stop>
                <a :href="`/admin/users/${subject.id}/connectors/${uc.id}/edit`" class="btn-ghost">Edit</a>
                <a v-if="uc.connector?.has_config_schema"
                   :href="`/admin/users/${subject.id}/connectors/${uc.id}/config`"
                   class="btn-ghost">Configure</a>
                <form method="POST" :action="`/admin/users/${subject.id}/connectors/${uc.id}`"
                      @submit.prevent="confirmRevoke($event, uc.connector?.name, 'connector')">
                  <input type="hidden" name="_token" :value="csrfToken">
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn-revoke">Remove</button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </AdminShell>
</template>

<script setup>
import { ref } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:          { type: Object, default: () => ({}) },
  csrfToken:            { type: String, default: '' },
  subject:              { type: Object, default: () => ({}) },
  subscriptions:        { type: Array,  default: () => [] },
  availableAgents:      { type: Array,  default: () => [] },
  userConnectors:       { type: Array,  default: () => [] },
  availableConnectors:  { type: Array,  default: () => [] },
  initialTab:           { type: String, default: 'details' },
  flashSuccess:         { type: String, default: '' },
  flashError:           { type: String, default: '' },
});

const VALID_TABS = ['details', 'agents', 'connectors'];
const urlTab = new URLSearchParams(window.location.search).get('tab');
const tab = ref(VALID_TABS.includes(urlTab) ? urlTab : (VALID_TABS.includes(props.initialTab) ? props.initialTab : 'details'));

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function navigate(url) {
  window.location.href = url;
}

function confirmRevoke(event, name, type) {
  const label = type === 'agent' ? `agent "${name ?? 'this agent'}"` : `connector "${name ?? 'this connector'}"`;
  if (confirm(`Remove ${label} from ${props.subject.name}?`)) {
    event.target.submit();
  }
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.75rem; }
.back-link:hover { color: #fca5a5; }

.header-row { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.big-avatar {
  width: 48px; height: 48px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #ef4444, #fca5a5);
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 1.15rem; color: #fff;
}
.page-title    { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.15rem; }
.subject-email { font-size: 0.82rem; color: #6b7280; }

.role-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; margin-left: auto; }
.role-badge.admin { background: rgba(239,68,68,0.12); color: #fca5a5; }
.role-badge.user  { background: rgba(107,114,128,0.12); color: #9ca3af; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

/* Top tabs */
.top-tab-bar {
  display: flex; gap: 0.25rem;
  border-bottom: 1px solid rgba(239,68,68,0.12);
  margin-bottom: 1.75rem;
}
.top-tab {
  padding: 0.6rem 1.1rem; border-radius: 0.5rem 0.5rem 0 0;
  font-size: 0.875rem; font-weight: 600; cursor: pointer;
  background: none; border: 1px solid transparent; border-bottom: none;
  color: #6b7280; font-family: inherit; transition: all 0.18s;
  display: flex; align-items: center; gap: 0.5rem;
}
.top-tab:hover { color: #fca5a5; background: rgba(239,68,68,0.05); }
.top-tab.active {
  color: #fca5a5; background: rgba(239,68,68,0.08);
  border-color: rgba(239,68,68,0.2); border-bottom-color: transparent;
  position: relative; bottom: -1px;
}
.top-tab-count {
  font-size: 0.7rem; font-weight: 700;
  background: rgba(239,68,68,0.12); color: #fca5a5;
  border-radius: 99px; padding: 0.1rem 0.45rem;
}

.tab-content { max-width: 900px; }

/* Details */
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; }
.card { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; }
.card-header { padding: 0.875rem 1.25rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.info-rows {}
.info-row { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1.25rem; border-bottom: 1px solid rgba(239,68,68,0.06); gap: 1rem; }
.info-row:last-child { border-bottom: none; }
.info-label { font-size: 0.82rem; color: #6b7280; flex-shrink: 0; }
.info-val { font-size: 0.875rem; color: #e5e7eb; text-align: right; }
.mono { font-family: monospace; font-size: 0.8rem; color: #9ca3af; }
.action-body { padding: 1.25rem; display: flex; flex-direction: column; gap: 0.875rem; }
.action-desc { font-size: 0.875rem; color: #6b7280; }
.muted-note  { font-size: 0.78rem; color: #4b5563; }
.btn-action { padding: 0.45rem 0.9rem; border-radius: 0.5rem; font-size: 0.82rem; font-weight: 600; cursor: pointer; border: none; font-family: inherit; transition: all 0.18s; min-height: 36px; }
.btn-action.promote { background: rgba(239,68,68,0.12); color: #fca5a5; }
.btn-action.promote:hover { background: rgba(239,68,68,0.22); }
.btn-action.danger  { background: rgba(107,114,128,0.12); color: #9ca3af; }
.btn-action.danger:hover  { background: rgba(107,114,128,0.22); }

/* Assign card */
.assign-card { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.12); border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.5rem; }
.assign-title { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #6b7280; margin-bottom: 1rem; }
.assign-form { display: flex; gap: 0.875rem; align-items: flex-end; flex-wrap: wrap; }
.assign-field { display: flex; flex-direction: column; gap: 0.3rem; flex: 1; min-width: 160px; }
.assign-field label { font-size: 0.78rem; font-weight: 600; color: #d1d5db; }
select, input[type="date"] {
  padding: 0.6rem 0.75rem; background: rgba(12,4,4,0.8);
  border: 1px solid rgba(239,68,68,0.15); border-radius: 0.5rem;
  color: #e5e7eb; font-size: 1rem; font-family: inherit; width: 100%;
}
select:focus, input[type="date"]:focus { outline: none; border-color: rgba(239,68,68,0.4); }
select option { background: #140606; }
select:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-assign { padding: 0.65rem 1.25rem; border-radius: 0.625rem; min-height: 44px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35); color: #fca5a5; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.18s; white-space: nowrap; flex-shrink: 0; }
.btn-assign:hover:not(:disabled) { background: rgba(239,68,68,0.25); }
.btn-assign:disabled { opacity: 0.45; cursor: not-allowed; }

/* Empty */
.empty-state { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px; }
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; }

/* Table */
.table-wrap { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 560px; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }
.clickable-row { cursor: pointer; }

.item-cell { display: flex; align-items: center; gap: 0.65rem; }
.item-icon { width: 30px; height: 30px; border-radius: 0.4rem; flex-shrink: 0; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; color: #fca5a5; }
.item-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }

.badge-inline { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; margin-top: 0.2rem; }
.badge-inline.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.badge-inline.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.badge-inline.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; }
.status-badge.active       { background: rgba(0,217,126,0.1);   color: #00d97e; }
.status-badge.cancelled    { background: rgba(239,68,68,0.1);   color: #fca5a5; }
.status-badge.expired      { background: rgba(107,114,128,0.12); color: #9ca3af; }
.status-badge.disconnected { background: rgba(107,114,128,0.12); color: #9ca3af; }

.type-badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 99px; font-size: 0.68rem; font-weight: 700; }
.type-badge.oauth { background: rgba(99,102,241,0.12); color: #a5b4fc; }
.type-badge.api   { background: rgba(217,119,6,0.12);  color: #FCD34D; }

.price { color: #FCD34D; font-weight: 600; white-space: nowrap; }
.muted { color: #6b7280; }
.small { font-size: 0.8rem; white-space: nowrap; }

.actions { text-align: right; white-space: nowrap; display: flex; align-items: center; justify-content: flex-end; gap: 0.375rem; }
.btn-ghost  { display: inline-block; padding: 0.3rem 0.55rem; border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem; color: #9ca3af; font-size: 0.78rem; text-decoration: none; transition: all 0.18s; }
.btn-ghost:hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.btn-revoke { padding: 0.3rem 0.55rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 600; cursor: pointer; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; font-family: inherit; transition: all 0.18s; }
.btn-revoke:hover { background: rgba(239,68,68,0.2); }

@media (max-width: 640px) {
  .detail-grid { grid-template-columns: 1fr; }
  .assign-form { flex-direction: column; }
  .assign-field { min-width: unset; }
  .page-title { font-size: 1.25rem; }
  .top-tab { padding: 0.5rem 0.75rem; font-size: 0.8rem; }
}
</style>
