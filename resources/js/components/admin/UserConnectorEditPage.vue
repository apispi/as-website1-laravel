<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="users">

    <div class="page-header">
      <a :href="`/admin/users/${subject.id}/connectors`" class="back-link">← {{ subject.name }}'s Connectors</a>
      <h1 class="page-title">Edit Connector</h1>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>
    <div v-if="errors.length" class="flash error">
      <div v-for="e in errors" :key="e">{{ e }}</div>
    </div>

    <div class="edit-layout">

      <!-- Sidebar: Connector + User info -->
      <div class="info-card">
        <div class="info-label">Connector</div>
        <div class="connector-row">
          <div class="connector-icon">{{ connector.icon || '⬡' }}</div>
          <div>
            <div class="connector-name">{{ connector.name }}</div>
            <div class="connector-meta">{{ connector.category }}</div>
          </div>
          <span v-if="connector.is_oauth" class="type-badge oauth">OAuth</span>
          <span v-else class="type-badge api">API</span>
        </div>

        <div class="info-label" style="margin-top:1.25rem;">User</div>
        <div class="user-row">
          <div class="user-avatar">{{ subject.name?.charAt(0)?.toUpperCase() }}</div>
          <div>
            <div class="user-name">{{ subject.name }}</div>
            <div class="user-email">{{ subject.email }}</div>
          </div>
        </div>

        <div style="margin-top:1.25rem;">
          <div class="status-pill" :class="userConnector.status === 'active' ? 'status-active' : 'status-disc'">
            {{ userConnector.status === 'active' ? 'Active' : 'Disconnected' }}
          </div>
        </div>
      </div>

      <!-- Tabbed main area -->
      <div class="tabbed-area">
        <div class="tab-bar">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            :class="['tab-btn', { active: activeTab === tab.id }]"
            type="button"
            @click="activeTab = tab.id"
          >{{ tab.label }}</button>
        </div>

        <div class="tab-card">

          <!-- Details tab -->
          <form
            v-show="activeTab === 'details'"
            method="POST"
            :action="`/admin/users/${subject.id}/connectors/${userConnector.id}`"
          >
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

          <!-- Metadata tab -->
          <div v-show="activeTab === 'metadata'">
            <div v-if="!hasMetadata" class="empty-state">
              <div class="empty-icon">⬡</div>
              <div class="empty-title">No metadata available</div>
              <div class="empty-desc">This connector has no additional metadata configured.</div>
            </div>
            <div v-else>
              <p v-if="connector.description" class="meta-description">{{ connector.description }}</p>
              <div class="meta-grid">
                <template v-if="connector.version">
                  <div class="meta-label">Version</div>
                  <div class="meta-value">{{ connector.version }}</div>
                </template>
                <template v-if="connector.vendor">
                  <div class="meta-label">Vendor</div>
                  <div class="meta-value">{{ connector.vendor }}</div>
                </template>
                <template v-if="connector.owner">
                  <div class="meta-label">Owner</div>
                  <div class="meta-value">{{ connector.owner }}</div>
                </template>
                <template v-if="connector.status">
                  <div class="meta-label">Catalog Status</div>
                  <div class="meta-value">{{ connector.status }}</div>
                </template>
                <template v-if="connector.sla_tier">
                  <div class="meta-label">SLA Tier</div>
                  <div class="meta-value">{{ connector.sla_tier }}</div>
                </template>
                <template v-if="connector.website_url">
                  <div class="meta-label">Website</div>
                  <div class="meta-value">
                    <a :href="connector.website_url" target="_blank" rel="noopener" class="meta-link">
                      {{ connector.website_url }}
                    </a>
                  </div>
                </template>
                <template v-if="envList.length">
                  <div class="meta-label">Environments</div>
                  <div class="meta-value badges-row">
                    <span v-for="env in envList" :key="env" class="env-badge">{{ env }}</span>
                  </div>
                </template>
                <template v-if="tagList.length">
                  <div class="meta-label">Tags</div>
                  <div class="meta-value badges-row">
                    <span v-for="tag in tagList" :key="tag" class="tag-chip">{{ tag }}</span>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <!-- Configuration tab -->
          <div v-show="activeTab === 'configuration'">
            <div v-if="!schema.length" class="empty-state">
              <div class="empty-icon">⬡</div>
              <div class="empty-title">No config fields defined</div>
              <div class="empty-desc">
                Add config fields to the
                <a :href="`/admin/connectors/${connector.id}/edit`" class="empty-link">
                  {{ connector.name }} connector
                </a>
                to enable per-user configuration.
              </div>
            </div>
            <form
              v-else
              method="POST"
              :action="`/admin/users/${subject.id}/connectors/${userConnector.id}/config`"
            >
              <input type="hidden" name="_token" :value="csrfToken">
              <input type="hidden" name="_method" value="PUT">

              <div class="form-grid">
                <div
                  v-for="field in schema"
                  :key="field.key"
                  :class="['form-group', field.type === 'textarea' ? 'full' : '']"
                >
                  <label>
                    {{ field.label }}
                    <span v-if="field.required" class="req">*</span>
                  </label>

                  <textarea
                    v-if="field.type === 'textarea'"
                    :name="`config[${field.key}]`"
                    rows="4"
                    :placeholder="field.placeholder || ''"
                    :required="field.required"
                  >{{ currentValue(field.key) }}</textarea>

                  <input
                    v-else
                    :type="field.type || 'text'"
                    :name="`config[${field.key}]`"
                    :value="field.type === 'password' ? '' : currentValue(field.key)"
                    :placeholder="field.type === 'password' && hasValue(field.key) ? '••••••••' : (field.placeholder || '')"
                    :required="field.required && !hasValue(field.key)"
                    :autocomplete="field.type === 'password' ? 'new-password' : 'off'"
                  >

                  <p v-if="field.type === 'password' && hasValue(field.key)" class="hint">
                    Leave blank to keep the existing value.
                  </p>
                  <p v-if="field.hint" class="hint">{{ field.hint }}</p>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-submit">Save Configuration</button>
                <a :href="`/admin/users/${subject.id}/connectors`" class="btn-cancel">Cancel</a>
              </div>
            </form>
          </div>

        </div>
      </div>

    </div>

  </AdminShell>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
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

const tabs = [
  { id: 'details',       label: 'Details' },
  { id: 'metadata',      label: 'Metadata' },
  { id: 'configuration', label: 'Configuration' },
];

const activeTab = ref('details');

const form = reactive({
  status:          props.userConnector.status ?? 'active',
  connected_at:    props.userConnector.connected_at ?? '',
  disconnected_at: props.userConnector.disconnected_at ?? '',
  notes:           props.userConnector.notes ?? '',
});

const connector = computed(() => props.userConnector.connector ?? {});
const schema    = computed(() => connector.value.config_schema || []);
const config    = computed(() => props.userConnector.config || {});

const envList = computed(() => {
  const e = connector.value.environment;
  return Array.isArray(e) ? e : (e ? [e] : []);
});
const tagList = computed(() => {
  const t = connector.value.tags;
  return Array.isArray(t) ? t : (t ? [t] : []);
});

const hasMetadata = computed(() => {
  const c = connector.value;
  return !!(c.description || c.version || c.vendor || c.owner || c.status || c.sla_tier || c.website_url || envList.value.length || tagList.value.length);
});

function currentValue(key) { return config.value[key] ?? ''; }
function hasValue(key)      { return !!config.value[key]; }
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link   { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.4rem; }
.back-link:hover { color: #fca5a5; }
.page-title  { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.edit-layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 1.5rem;
  align-items: start;
  max-width: 900px;
}

/* Sidebar */
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

.status-pill { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 99px; font-size: 0.72rem; font-weight: 700; }
.status-active { background: rgba(0,217,126,0.1); color: #00d97e; border: 1px solid rgba(0,217,126,0.25); }
.status-disc   { background: rgba(239,68,68,0.08); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

/* Tab bar */
.tabbed-area { display: flex; flex-direction: column; }
.tab-bar { display: flex; gap: 0; }
.tab-btn {
  padding: 0.6rem 1.25rem; font-size: 0.875rem; font-weight: 600;
  background: rgba(12,4,4,0.5); border: 1px solid rgba(239,68,68,0.12);
  color: #6b7280; cursor: pointer; font-family: inherit;
  transition: all 0.18s; border-bottom: none;
  border-radius: 0.625rem 0.625rem 0 0;
  margin-right: 3px; position: relative; bottom: -1px;
}
.tab-btn:first-child { border-radius: 0.625rem 0 0 0; margin-right: 0; }
.tab-btn + .tab-btn { border-left: none; }
.tab-btn:hover { color: #fca5a5; background: rgba(239,68,68,0.06); }
.tab-btn.active {
  background: rgba(24,10,10,0.6); color: #fca5a5;
  border-color: rgba(239,68,68,0.2);
  border-bottom: 1px solid rgba(24,10,10,0.6);
  z-index: 1;
}

/* Tab content card */
.tab-card {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.2);
  border-radius: 0 0.75rem 0.75rem 0.75rem;
  padding: 1.5rem; position: relative; z-index: 0;
}

/* Form elements */
.form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1.25rem; }
.form-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-grid  { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
.form-group.full { grid-column: 1 / -1; }

label { font-size: 0.82rem; font-weight: 600; color: #d1d5db; }
.req  { color: #ef4444; }
.hint { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; }

input[type="datetime-local"],
input[type="text"],
input[type="url"],
input[type="password"],
input[type="email"],
select,
textarea {
  padding: 0.65rem 0.875rem;
  background: rgba(12,4,4,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.5rem; color: #e5e7eb; font-size: 1rem; font-family: inherit;
  transition: border-color 0.18s; width: 100%;
}
input:focus, select:focus, textarea:focus { outline: none; border-color: rgba(239,68,68,0.45); }
input::placeholder, textarea::placeholder { color: #4b5563; }
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

/* Metadata */
.meta-description { font-size: 0.875rem; color: #9ca3af; line-height: 1.6; margin-bottom: 1.25rem; }
.meta-grid { display: grid; grid-template-columns: 140px 1fr; gap: 0.75rem 1rem; align-items: start; }
.meta-label { font-size: 0.78rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.06em; padding-top: 0.1rem; }
.meta-value { font-size: 0.875rem; color: #e5e7eb; }
.meta-link  { color: #fca5a5; text-decoration: none; word-break: break-all; }
.meta-link:hover { text-decoration: underline; }

.badges-row { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.env-badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; background: rgba(239,68,68,0.1); color: #fca5a5; border: 1px solid rgba(239,68,68,0.2); }
.tag-chip  { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 99px; font-size: 0.72rem; font-weight: 500; background: rgba(107,114,128,0.12); color: #9ca3af; border: 1px solid rgba(107,114,128,0.2); }

/* Empty state */
.empty-state { padding: 2.5rem 2rem; text-align: center; }
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; }
.empty-link  { color: #fca5a5; text-decoration: none; }
.empty-link:hover { text-decoration: underline; }

@media (max-width: 700px) {
  .edit-layout       { grid-template-columns: 1fr; }
  .form-row          { grid-template-columns: 1fr; }
  .form-grid         { grid-template-columns: 1fr; }
  .form-group.full   { grid-column: 1; }
  .meta-grid         { grid-template-columns: 120px 1fr; }
  .tab-btn           { padding: 0.5rem 0.875rem; font-size: 0.8rem; }
}
</style>
