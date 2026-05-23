<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="users">

    <div class="page-header">
      <a :href="`/admin/users/${subject.id}/connectors`" class="back-link">← {{ subject.name }}'s Connectors</a>
      <h1 class="page-title">Edit Connector</h1>
    </div>

    <div v-if="errors.length" class="flash error">
      <div v-for="e in errors" :key="e">{{ e }}</div>
    </div>

    <div class="edit-layout">

      <!-- Connector info card (read-only) -->
      <div class="info-card">
        <div class="info-label">Connector</div>
        <div class="connector-row">
          <div class="connector-icon">{{ userConnector.connector?.icon || '⬡' }}</div>
          <div>
            <div class="connector-name">{{ userConnector.connector?.name }}</div>
            <div class="connector-meta">{{ userConnector.connector?.category }}</div>
          </div>
          <span v-if="userConnector.connector?.is_oauth" class="type-badge oauth">OAuth</span>
          <span v-else class="type-badge api">API</span>
        </div>

        <div class="info-label" style="margin-top: 1.25rem;">User</div>
        <div class="user-row">
          <div class="user-avatar">{{ subject.name?.charAt(0)?.toUpperCase() }}</div>
          <div>
            <div class="user-name">{{ subject.name }}</div>
            <div class="user-email">{{ subject.email }}</div>
          </div>
        </div>

        <div v-if="userConnector.connector?.has_config_schema" style="margin-top: 1.25rem;">
          <a :href="`/admin/users/${subject.id}/connectors/${userConnector.id}/config`" class="btn-configure">
            Configure API Keys →
          </a>
        </div>
      </div>

      <!-- Edit form -->
      <div class="form-card">
        <form method="POST" :action="`/admin/users/${subject.id}/connectors/${userConnector.id}`">
          <input type="hidden" name="_token" :value="csrfToken">
          <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
            <label>Status</label>
            <select name="status" v-model="form.status">
              <option value="active">Active</option>
              <option value="disconnected">Disconnected</option>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Connected At</label>
              <input type="datetime-local" name="connected_at" v-model="form.connected_at">
            </div>
            <div class="form-group">
              <label>Disconnected At</label>
              <input type="datetime-local" name="disconnected_at" v-model="form.disconnected_at">
              <p class="hint">Leave blank if still active.</p>
            </div>
          </div>

          <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" v-model="form.notes" rows="3" placeholder="Internal notes about this connection…"></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-submit">Save Changes</button>
            <a :href="`/admin/users/${subject.id}/connectors`" class="btn-cancel">Cancel</a>
          </div>
        </form>
      </div>

    </div>

  </AdminShell>
</template>

<script setup>
import { reactive } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:    { type: Object, default: () => ({}) },
  csrfToken:      { type: String, default: '' },
  subject:        { type: Object, default: () => ({}) },
  userConnector:  { type: Object, default: () => ({}) },
  errors:         { type: Array,  default: () => [] },
  flashSuccess:   { type: String, default: '' },
  flashError:     { type: String, default: '' },
});

const form = reactive({
  status:          props.userConnector.status ?? 'active',
  connected_at:    props.userConnector.connected_at ?? '',
  disconnected_at: props.userConnector.disconnected_at ?? '',
  notes:           props.userConnector.notes ?? '',
});
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link   { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.4rem; }
.back-link:hover { color: #fca5a5; }
.page-title  { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.edit-layout { display: grid; grid-template-columns: 280px 1fr; gap: 1.5rem; align-items: start; max-width: 860px; }

.info-card {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; padding: 1.25rem;
}
.info-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 0.75rem; }

.connector-row { display: flex; align-items: center; gap: 0.75rem; }
.connector-icon { width: 36px; height: 36px; border-radius: 0.5rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.18); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.connector-name { font-size: 0.9rem; font-weight: 600; color: #e5e7eb; }
.connector-meta { font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem; }

.type-badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 99px; font-size: 0.68rem; font-weight: 700; margin-left: auto; flex-shrink: 0; }
.type-badge.oauth { background: rgba(99,102,241,0.12); color: #a5b4fc; }
.type-badge.api   { background: rgba(217,119,6,0.12); color: #FCD34D; }

.user-row { display: flex; align-items: center; gap: 0.75rem; }
.user-avatar { width: 32px; height: 32px; border-radius: 50%; background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: #fca5a5; flex-shrink: 0; }
.user-name  { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.user-email { font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem; }

.btn-configure { display: inline-block; padding: 0.5rem 0.875rem; border-radius: 0.5rem; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.25); color: #a5b4fc; font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: all 0.18s; }
.btn-configure:hover { background: rgba(99,102,241,0.18); }

.form-card {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; padding: 1.5rem;
}

.form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1.25rem; }
.form-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

label { font-size: 0.82rem; font-weight: 600; color: #d1d5db; }
.hint { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; }

input[type="datetime-local"],
select,
textarea {
  padding: 0.65rem 0.875rem;
  background: rgba(12,4,4,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.5rem; color: #e5e7eb; font-size: 1rem; font-family: inherit;
  transition: border-color 0.18s; width: 100%;
}
input:focus, select:focus, textarea:focus { outline: none; border-color: rgba(239,68,68,0.45); }
textarea { resize: vertical; min-height: 80px; }
select option { background: #140606; }
input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(0.5); }

.form-actions { display: flex; gap: 0.75rem; align-items: center; margin-top: 0.5rem; }
.btn-submit {
  padding: 0.65rem 1.5rem; border-radius: 0.625rem;
  background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35);
  color: #fca5a5; font-size: 0.9rem; font-weight: 600; cursor: pointer;
  font-family: inherit; transition: all 0.18s; min-height: 44px;
}
.btn-submit:hover { background: rgba(239,68,68,0.25); }
.btn-cancel {
  padding: 0.65rem 1.25rem; border-radius: 0.625rem;
  border: 1px solid rgba(239,68,68,0.12); color: #6b7280;
  font-size: 0.875rem; text-decoration: none; transition: all 0.18s;
  min-height: 44px; display: inline-flex; align-items: center;
}
.btn-cancel:hover { border-color: rgba(239,68,68,0.25); color: #9ca3af; }

@media (max-width: 700px) {
  .edit-layout { grid-template-columns: 1fr; }
  .form-row    { grid-template-columns: 1fr; }
}
</style>
