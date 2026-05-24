<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="all-agents">

    <!-- Back + breadcrumb -->
    <div class="back-row">
      <a href="/admin/subscriptions" class="back-link">← Active Agents</a>
    </div>

    <!-- Header -->
    <div class="page-header">
      <div class="header-main">
        <div class="agent-avatar">◈</div>
        <div class="header-text">
          <h1 class="page-title">{{ agent?.name ?? '—' }}</h1>
          <div class="header-sub">
            <a :href="`/admin/users/${subUser?.id}`" class="user-link">{{ subUser?.name ?? '—' }}</a>
            <span class="sep">·</span>
            <span class="muted">{{ subUser?.email ?? '' }}</span>
          </div>
        </div>
        <span class="status-badge" :class="subscription.status">{{ subscription.status }}</span>
      </div>
    </div>

    <!-- Tabs -->
    <div class="tab-bar">
      <button v-for="t in tabs" :key="t.key"
        class="tab-btn" :class="{ active: tab === t.key }"
        @click="tab = t.key">
        {{ t.label }}
        <span v-if="t.count != null" class="tab-count">{{ t.count }}</span>
      </button>
    </div>

    <!-- Overview tab -->
    <div v-show="tab === 'overview'">
      <div class="cards-grid">

        <!-- Subscription card -->
        <div class="card">
          <div class="card-title">Subscription</div>
          <div class="field-row">
            <span class="field-label">Status</span>
            <span class="status-badge" :class="subscription.status">{{ subscription.status }}</span>
          </div>
          <div class="field-row">
            <span class="field-label">Started</span>
            <span class="field-val">{{ formatDate(subscription.started_at) }}</span>
          </div>
          <div class="field-row">
            <span class="field-label">Expires</span>
            <span class="field-val">{{ subscription.expires_at ? formatDate(subscription.expires_at) : 'Ongoing' }}</span>
          </div>

          <!-- Edit form -->
          <div class="edit-section">
            <div class="edit-title">Edit Subscription</div>
            <form :action="`/admin/subscriptions/${subscription.id}`" method="POST" class="edit-form">
              <input type="hidden" name="_token" :value="csrfToken">
              <input type="hidden" name="_method" value="PUT">
              <div class="form-row">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" v-model="editStatus">
                  <option value="active">active</option>
                  <option value="cancelled">cancelled</option>
                  <option value="expired">expired</option>
                </select>
              </div>
              <div class="form-row">
                <label class="form-label">Expires At</label>
                <input type="date" name="expires_at" class="form-input" v-model="editExpires">
              </div>
              <button type="submit" class="btn-save">Save Changes</button>
            </form>
          </div>
        </div>

        <!-- User card -->
        <div class="card">
          <div class="card-title">User</div>
          <div class="user-card-body">
            <div class="user-avatar-lg">{{ userInitial }}</div>
            <div>
              <div class="user-name-lg">{{ subUser?.name ?? '—' }}</div>
              <div class="user-email-lg">{{ subUser?.email ?? '' }}</div>
              <span v-if="subUser?.is_admin" class="role-badge admin">Admin</span>
            </div>
          </div>
          <div class="card-actions">
            <a :href="`/admin/users/${subUser?.id}`" class="btn-link">User Profile →</a>
            <a :href="`/admin/users/${subUser?.id}?tab=agents`" class="btn-link">Manage Agents →</a>
            <a :href="`/admin/users/${subUser?.id}?tab=connectors`" class="btn-link">Manage Connectors →</a>
          </div>
        </div>

        <!-- Agent card -->
        <div class="card">
          <div class="card-title">Agent</div>
          <div class="agent-card-body">
            <div class="agent-icon-lg">◈</div>
            <div>
              <div class="agent-name-lg">{{ agent?.name ?? '—' }}</div>
              <div class="agent-meta">
                <span v-if="agent?.badge" class="badge-inline" :class="agent.badge.toLowerCase()">{{ agent.badge }}</span>
                <span v-if="agent?.category" class="muted small">{{ agent.category }}</span>
              </div>
              <div v-if="agent?.price" class="agent-price">{{ agent.price }}</div>
            </div>
          </div>
          <p v-if="agent?.description" class="agent-desc">{{ agent.description }}</p>
          <div class="card-actions">
            <a :href="`/admin/agents/${agent?.id}`" class="btn-link">Agent Detail →</a>
            <a :href="`/admin/agents/${agent?.id}/edit`" class="btn-link">Edit Agent →</a>
          </div>
        </div>

      </div>
    </div>

    <!-- Connectors tab -->
    <div v-show="tab === 'connectors'">
      <div v-if="agentConnectors.length === 0" class="empty-state">
        <div class="empty-icon">◈</div>
        <div class="empty-title">No connectors required</div>
        <div class="empty-desc">This agent has no associated connectors.</div>
      </div>
      <div v-else class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Connector</th>
              <th>Category</th>
              <th>Type</th>
              <th>User Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in agentConnectors" :key="c.id">
              <td>
                <div class="connector-cell">
                  <div class="connector-icon">{{ c.icon || '⚡' }}</div>
                  <span class="connector-name">{{ c.name }}</span>
                </div>
              </td>
              <td class="muted small">{{ c.category || '—' }}</td>
              <td>
                <span class="type-badge" :class="c.is_oauth ? 'oauth' : 'api'">
                  {{ c.is_oauth ? 'OAuth' : 'API Key' }}
                </span>
              </td>
              <td>
                <span v-if="userConnectorStatus(c.id)" class="status-badge" :class="userConnectorStatus(c.id)">
                  {{ userConnectorStatus(c.id) }}
                </span>
                <span v-else class="status-badge not-connected">not connected</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Skills tab -->
    <div v-show="tab === 'skills'">
      <div v-if="agentSkills.length === 0" class="empty-state">
        <div class="empty-icon">◈</div>
        <div class="empty-title">No skills assigned</div>
        <div class="empty-desc">This agent has no associated skills.</div>
      </div>
      <div v-else class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Skill</th>
              <th>Category</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="s in agentSkills" :key="s.id" class="clickable-row" @click="goSkill(s.id)">
              <td><a :href="`/admin/agents/${agent.id}/skills/${s.id}`" class="skill-link" @click.stop>{{ s.name }}</a></td>
              <td class="muted small">{{ s.category || '—' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </AdminShell>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:     { type: Object, default: () => ({}) },
  csrfToken:       { type: String, default: '' },
  subscription:    { type: Object, default: () => ({}) },
  subUser:         { type: Object, default: null },
  agent:           { type: Object, default: null },
  agentConnectors: { type: Array,  default: () => [] },
  agentSkills:     { type: Array,  default: () => [] },
  userConnectors:  { type: Array,  default: () => [] },
});

const tab = ref('overview');

const tabs = computed(() => [
  { key: 'overview',    label: 'Overview',    count: null },
  { key: 'connectors',  label: 'Connectors',  count: props.agentConnectors.length },
  { key: 'skills',      label: 'Skills',      count: props.agentSkills.length },
]);

const editStatus  = ref(props.subscription.status ?? 'active');
const editExpires = ref(props.subscription.expires_at ? props.subscription.expires_at.substring(0, 10) : '');

const userInitial = computed(() => (props.subUser?.name ?? '?')[0].toUpperCase());

function userConnectorStatus(connectorId) {
  const uc = props.userConnectors.find(u => u.connector_id === connectorId);
  return uc?.status ?? null;
}

function goSkill(skillId) { window.location.href = `/admin/agents/${props.agent?.id}/skills/${skillId}`; }

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.back-row { margin-bottom: 1rem; }
.back-link { font-size: 0.82rem; color: #6b7280; text-decoration: none; }
.back-link:hover { color: #fca5a5; }

.page-header { margin-bottom: 1.5rem; }
.header-main { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.agent-avatar { width: 44px; height: 44px; border-radius: 0.6rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fca5a5; flex-shrink: 0; }
.header-text { flex: 1; min-width: 0; }
.page-title { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; }
.header-sub { font-size: 0.85rem; margin-top: 0.2rem; display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap; }
.user-link { color: #fca5a5; text-decoration: none; font-weight: 500; }
.user-link:hover { text-decoration: underline; }
.sep { color: #4b5563; }
.muted { color: #6b7280; }
.small { font-size: 0.8rem; }

/* Tabs */
.tab-bar { display: flex; gap: 0.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(239,68,68,0.1); padding-bottom: 0; }
.tab-btn { padding: 0.55rem 1rem; font-size: 0.85rem; font-weight: 600; background: none; border: none; border-bottom: 2px solid transparent; color: #6b7280; cursor: pointer; display: flex; align-items: center; gap: 0.4rem; transition: all 0.15s; margin-bottom: -1px; }
.tab-btn:hover { color: #e5e7eb; }
.tab-btn.active { color: #fca5a5; border-bottom-color: #fca5a5; }
.tab-count { font-size: 0.7rem; background: rgba(239,68,68,0.12); color: #fca5a5; padding: 0.1rem 0.4rem; border-radius: 99px; }

/* Cards grid */
.cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
.card { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; padding: 1.25rem; }
.card-title { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; margin-bottom: 1rem; }

.field-row { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid rgba(239,68,68,0.06); font-size: 0.875rem; }
.field-row:last-of-type { border-bottom: none; }
.field-label { color: #9ca3af; font-size: 0.82rem; }
.field-val { color: #e5e7eb; }

/* Edit form */
.edit-section { margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid rgba(239,68,68,0.1); }
.edit-title { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; margin-bottom: 0.75rem; }
.edit-form { display: flex; flex-direction: column; gap: 0.65rem; }
.form-row { display: flex; flex-direction: column; gap: 0.25rem; }
.form-label { font-size: 0.75rem; color: #9ca3af; }
.form-select, .form-input { background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem; padding: 0.45rem 0.65rem; color: #e5e7eb; font-size: 0.85rem; width: 100%; }
.form-select:focus, .form-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.btn-save { margin-top: 0.25rem; padding: 0.45rem 1rem; background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); border-radius: 0.4rem; color: #fca5a5; font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: all 0.15s; text-align: center; }
.btn-save:hover { background: rgba(239,68,68,0.22); }

/* User card */
.user-card-body { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
.user-avatar-lg { width: 40px; height: 40px; border-radius: 50%; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 700; color: #fca5a5; flex-shrink: 0; }
.user-name-lg { font-size: 0.95rem; font-weight: 600; color: #e5e7eb; }
.user-email-lg { font-size: 0.8rem; color: #6b7280; margin-top: 0.1rem; }
.role-badge { display: inline-block; padding: 0.12rem 0.45rem; border-radius: 99px; font-size: 0.65rem; font-weight: 600; margin-top: 0.3rem; }
.role-badge.admin { background: rgba(239,68,68,0.12); color: #fca5a5; }

/* Agent card */
.agent-card-body { display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem; }
.agent-icon-lg { width: 40px; height: 40px; border-radius: 0.5rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.18); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fca5a5; flex-shrink: 0; }
.agent-name-lg { font-size: 0.95rem; font-weight: 600; color: #e5e7eb; }
.agent-meta { display: flex; gap: 0.4rem; align-items: center; margin-top: 0.2rem; flex-wrap: wrap; }
.agent-price { font-size: 0.82rem; color: #FCD34D; font-weight: 600; margin-top: 0.25rem; }
.agent-desc { font-size: 0.82rem; color: #9ca3af; line-height: 1.5; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

/* Card actions */
.card-actions { display: flex; flex-direction: column; gap: 0.35rem; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(239,68,68,0.08); }
.btn-link { font-size: 0.8rem; color: #9ca3af; text-decoration: none; transition: color 0.15s; }
.btn-link:hover { color: #fca5a5; }

/* Badge */
.badge-inline { display: inline-block; padding: 0.12rem 0.4rem; border-radius: 99px; font-size: 0.65rem; font-weight: 600; }
.badge-inline.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.badge-inline.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.badge-inline.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

/* Status badge */
.status-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; }
.status-badge.active       { background: rgba(0,217,126,0.1);   color: #00d97e; }
.status-badge.cancelled    { background: rgba(239,68,68,0.1);   color: #fca5a5; }
.status-badge.expired      { background: rgba(107,114,128,0.12); color: #9ca3af; }
.status-badge.not-connected { background: rgba(107,114,128,0.1); color: #6b7280; }

/* Table */
.table-wrap { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.connector-cell { display: flex; align-items: center; gap: 0.6rem; }
.connector-icon { width: 28px; height: 28px; border-radius: 0.35rem; background: rgba(239,68,68,0.07); border: 1px solid rgba(239,68,68,0.15); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; flex-shrink: 0; }
.connector-name { font-size: 0.875rem; font-weight: 500; color: #e5e7eb; }

.type-badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; }
.type-badge.oauth { background: rgba(139,92,246,0.12); color: #c4b5fd; }
.type-badge.api   { background: rgba(59,130,246,0.1);  color: #93c5fd; }

.skill-link { font-weight: 500; color: #e5e7eb; text-decoration: none; }
.skill-link:hover { color: #fca5a5; text-decoration: underline; }
.clickable-row { cursor: pointer; }
.clickable-row:hover td { background: rgba(239,68,68,0.04) !important; }

/* Empty state */
.empty-state { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px; }
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; }

@media (max-width: 640px) {
  .page-title { font-size: 1.2rem; }
  .cards-grid { grid-template-columns: 1fr; }
}
</style>
