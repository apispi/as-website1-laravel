<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="connectors">

    <div class="page-header">
      <div>
        <a href="/admin/connectors" class="back-link">← Connectors</a>
        <h1 class="page-title">{{ connector ? 'Edit Connector' : 'New Connector' }}</h1>
      </div>
    </div>

    <div v-if="errors.length" class="flash error">
      <div v-for="e in errors" :key="e">{{ e }}</div>
    </div>

    <div class="form-card">
      <form method="POST" :action="formAction">
        <input type="hidden" name="_token" :value="csrfToken">
        <input v-if="connector" type="hidden" name="_method" value="PUT">

        <!-- Basic info -->
        <div class="section-label">General</div>
        <div class="form-grid">
          <div class="form-group full">
            <label>Name <span class="req">*</span></label>
            <input type="text" name="name" :value="connector?.name" required placeholder="e.g. Slack">
          </div>

          <div class="form-group">
            <label>Slug <span class="req">*</span></label>
            <input type="text" name="slug" :value="connector?.slug" required placeholder="e.g. slack">
            <p class="hint">Unique identifier, lowercase with hyphens.</p>
          </div>

          <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" :value="connector?.category" placeholder="e.g. Communication">
          </div>

          <div class="form-group">
            <label>Icon <span class="hint-inline">(emoji)</span></label>
            <input type="text" name="icon" :value="connector?.icon" placeholder="e.g. 💬" maxlength="10">
          </div>

          <div class="form-group">
            <label>Website URL</label>
            <input type="url" name="website_url" :value="connector?.website_url" placeholder="https://slack.com">
          </div>

          <div class="form-group full">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Describe what this connector integrates with…">{{ connector?.description }}</textarea>
          </div>

          <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" :value="connector?.sort_order ?? 0" min="0">
          </div>

          <div class="form-group checkboxes">
            <label class="checkbox-label">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" name="is_active" value="1" :checked="connector?.is_active ?? true">
              Active (visible on platform)
            </label>
          </div>
        </div>

        <!-- Catalog metadata -->
        <div class="section-divider"></div>
        <div class="section-label">Catalog Metadata</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Version</label>
            <input type="text" name="version" :value="connector?.version" placeholder="e.g. 1.0.0">
          </div>

          <div class="form-group">
            <label>Vendor</label>
            <input type="text" name="vendor" :value="connector?.vendor" placeholder="e.g. Salesforce">
          </div>

          <div class="form-group">
            <label>Owner</label>
            <input type="text" name="owner" :value="connector?.owner" placeholder="e.g. Platform Team">
          </div>

          <div class="form-group">
            <label>Status</label>
            <select name="status" v-model="catalogStatus">
              <option value="active">Active</option>
              <option value="deprecated">Deprecated</option>
              <option value="draft">Draft</option>
            </select>
          </div>

          <div class="form-group">
            <label>SLA Tier</label>
            <select name="sla_tier" v-model="slaTier">
              <option value="">— None —</option>
              <option value="critical">Critical</option>
              <option value="high">High</option>
              <option value="standard">Standard</option>
              <option value="low">Low</option>
            </select>
          </div>

          <div class="form-group">
            <label>Environment</label>
            <div class="checkbox-group">
              <label class="checkbox-label" v-for="env in ['dev','test','prod']" :key="env">
                <input type="checkbox" name="environment[]" :value="env" :checked="selectedEnvironments.includes(env)" @change="toggleEnv(env)">
                {{ env }}
              </label>
            </div>
          </div>

          <div class="form-group full">
            <label>Tags</label>
            <input type="text" name="tags" :value="tagsValue" placeholder="e.g. crm, sales, cloud">
            <p class="hint">Comma-separated list of tags for search and discovery.</p>
          </div>
        </div>

        <!-- OAuth config -->
        <div class="section-divider"></div>
        <div class="section-label">OAuth Configuration</div>

        <div class="form-grid">
          <div class="form-group checkboxes">
            <label class="checkbox-label">
              <input type="hidden" name="is_oauth" value="0">
              <input type="checkbox" name="is_oauth" value="1" :checked="connector?.is_oauth ?? false" @change="oauthEnabled = $event.target.checked">
              This connector uses OAuth 2.0
            </label>
          </div>

          <template v-if="oauthEnabled">
            <div class="form-group">
              <label>Client ID</label>
              <input type="text" name="oauth_client_id" :value="connector?.oauth_client_id" placeholder="Your app's client ID" autocomplete="off">
            </div>

            <div class="form-group">
              <label>Client Secret</label>
              <input type="password" name="oauth_client_secret" placeholder="{{ connector?.oauth_client_secret ? '••••••••' : 'Your app\'s client secret' }}" autocomplete="new-password">
              <p v-if="connector?.oauth_client_secret" class="hint">Leave blank to keep the existing secret.</p>
            </div>

            <div class="form-group full">
              <label>Authorization URL</label>
              <input type="url" name="oauth_auth_url" :value="connector?.oauth_auth_url" placeholder="https://provider.com/oauth/authorize">
            </div>

            <div class="form-group full">
              <label>Token URL</label>
              <input type="url" name="oauth_token_url" :value="connector?.oauth_token_url" placeholder="https://provider.com/oauth/token">
            </div>

            <div class="form-group full">
              <label>Scopes</label>
              <input type="text" name="oauth_scopes" :value="connector?.oauth_scopes" placeholder="e.g. offline_access Mail.ReadWrite">
              <p class="hint">Space-separated list of OAuth scopes.</p>
            </div>

            <div class="form-group full">
              <label>Extra Parameters <span class="hint-inline">(JSON)</span></label>
              <textarea name="oauth_extra_params" rows="3" placeholder='e.g. {"response_mode": "query"}'>{{ extraParamsJson }}</textarea>
              <p class="hint">Additional parameters merged into the authorization URL. Must be valid JSON or empty.</p>
            </div>

            <div class="form-group full">
              <div class="callback-info">
                <span class="callback-label">Callback URL</span>
                <code class="callback-url">{{ callbackUrl }}</code>
                <p class="hint">Register this URL as a redirect URI in your OAuth app settings.</p>
              </div>
            </div>
          </template>
        </div>

        <!-- Config schema builder -->
        <div class="section-divider"></div>
        <div class="section-label">User Config Schema</div>
        <p class="schema-desc">Define fields users must fill in to configure this connector (e.g. API keys, workspace IDs).</p>

        <input type="hidden" name="config_schema" :value="configSchemaJson">

        <div class="schema-fields">
          <div v-for="(field, i) in schemaFields" :key="i" class="schema-row">
            <input v-model="field.key"         type="text"   placeholder="key (e.g. api_key)"  class="schema-input" />
            <input v-model="field.label"       type="text"   placeholder="Label"               class="schema-input" />
            <select v-model="field.type" class="schema-select">
              <option value="text">Text</option>
              <option value="password">Password</option>
              <option value="url">URL</option>
              <option value="email">Email</option>
              <option value="textarea">Textarea</option>
            </select>
            <input v-model="field.placeholder" type="text"   placeholder="Placeholder (opt.)"  class="schema-input schema-input-sm" />
            <input v-model="field.hint"        type="text"   placeholder="Hint text (opt.)"    class="schema-input schema-input-sm" />
            <label class="schema-check"><input type="checkbox" v-model="field.required"> Required</label>
            <button type="button" class="btn-remove-field" @click="removeField(i)" title="Remove">✕</button>
          </div>

          <div v-if="schemaFields.length === 0" class="schema-empty">No config fields defined.</div>
        </div>

        <button type="button" class="btn-add-field" @click="addField">+ Add Field</button>

        <div class="form-actions" style="margin-top: 1.75rem;">
          <button type="submit" class="btn-submit">{{ connector ? 'Save Changes' : 'Create Connector' }}</button>
          <a href="/admin/connectors" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>

  </AdminShell>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:      { type: Object, default: () => ({}) },
  csrfToken: { type: String, default: '' },
  connector: { type: Object, default: null },
  errors:    { type: Array,  default: () => [] },
});

const oauthEnabled        = ref(props.connector?.is_oauth ?? false);
const catalogStatus       = ref(props.connector?.status ?? 'active');
const slaTier             = ref(props.connector?.sla_tier ?? '');
const selectedEnvironments = ref(props.connector?.environment ?? []);
const tagsValue           = computed(() => (props.connector?.tags ?? []).join(', '));
const formAction          = props.connector ? `/admin/connectors/${props.connector.id}` : '/admin/connectors';

function toggleEnv(env) {
  const i = selectedEnvironments.value.indexOf(env);
  if (i === -1) selectedEnvironments.value.push(env);
  else selectedEnvironments.value.splice(i, 1);
}

const extraParamsJson = computed(() => {
  const p = props.connector?.oauth_extra_params;
  if (!p || (typeof p === 'object' && Object.keys(p).length === 0)) return '';
  return typeof p === 'string' ? p : JSON.stringify(p, null, 2);
});

const callbackUrl = computed(() => {
  const slug = props.connector?.slug;
  if (!slug) return window.location.origin + '/connectors/{slug}/callback';
  return window.location.origin + `/connectors/${slug}/callback`;
});

// Config schema builder
const defaultField = () => ({ key: '', label: '', type: 'text', placeholder: '', hint: '', required: false });

const schemaFields = ref(
  (props.connector?.config_schema ?? []).map(f => ({ ...defaultField(), ...f }))
);

function addField()        { schemaFields.value.push(defaultField()); }
function removeField(i)    { schemaFields.value.splice(i, 1); }

const configSchemaJson = computed(() => {
  const fields = schemaFields.value.filter(f => f.key && f.label);
  return fields.length ? JSON.stringify(fields) : '';
});
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.4rem; }
.back-link:hover { color: #fca5a5; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.form-card {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; padding: 1.75rem; max-width: 700px;
}

.section-label {
  font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.08em; color: #6b7280; margin-bottom: 1rem;
}
.section-divider { border-top: 1px solid rgba(239,68,68,0.08); margin: 1.75rem 0 1.5rem; }

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
.form-group { display: flex; flex-direction: column; gap: 0.35rem; }
.form-group.full { grid-column: 1 / -1; }
.form-group.checkboxes { grid-column: 1 / -1; display: flex; flex-direction: column; gap: 0.6rem; }

label { font-size: 0.82rem; font-weight: 600; color: #d1d5db; }
.req  { color: #ef4444; }
.hint { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; }
.hint-inline { font-size: 0.75rem; color: #4b5563; font-weight: 400; }

input[type="text"],
input[type="number"],
input[type="url"],
input[type="password"],
textarea {
  padding: 0.65rem 0.875rem;
  background: rgba(12,4,4,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.5rem; color: #e5e7eb; font-size: 1rem; font-family: inherit;
  transition: border-color 0.18s; width: 100%;
}
input:focus, textarea:focus { outline: none; border-color: rgba(239,68,68,0.45); }
input::placeholder, textarea::placeholder { color: #4b5563; }
textarea { resize: vertical; min-height: 80px; }

.checkbox-label {
  display: flex; align-items: center; gap: 0.5rem;
  font-size: 0.875rem; color: #d1d5db; cursor: pointer; font-weight: 400;
}
.checkbox-label input[type="checkbox"] { width: 16px; height: 16px; accent-color: #ef4444; flex-shrink: 0; }
.checkbox-label input[type="hidden"] { display: none; }

.checkbox-group { display: flex; gap: 1rem; flex-wrap: wrap; padding-top: 0.25rem; }

.callback-info { background: rgba(239,68,68,0.04); border: 1px solid rgba(239,68,68,0.12); border-radius: 0.5rem; padding: 0.875rem 1rem; }
.callback-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; display: block; margin-bottom: 0.4rem; }
.callback-url { display: block; font-family: monospace; font-size: 0.82rem; color: #fca5a5; word-break: break-all; margin-bottom: 0.4rem; }

.form-actions { display: flex; gap: 0.75rem; align-items: center; }
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

.schema-desc { font-size: 0.82rem; color: #6b7280; margin-bottom: 1rem; }
.schema-fields { display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 0.875rem; }
.schema-row { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; background: rgba(239,68,68,0.03); border: 1px solid rgba(239,68,68,0.08); border-radius: 0.5rem; padding: 0.6rem 0.75rem; }
.schema-input { flex: 1; min-width: 100px; padding: 0.45rem 0.6rem; background: rgba(12,4,4,0.8); border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem; color: #e5e7eb; font-size: 0.85rem; font-family: inherit; }
.schema-input-sm { flex: 0.7; }
.schema-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.schema-input::placeholder { color: #374151; }
.schema-select { padding: 0.45rem 0.6rem; background: rgba(12,4,4,0.8); border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem; color: #e5e7eb; font-size: 0.85rem; font-family: inherit; flex-shrink: 0; }
.schema-select:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.schema-select option { background: #140606; }
.schema-check { display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; color: #9ca3af; cursor: pointer; flex-shrink: 0; white-space: nowrap; }
.schema-check input[type="checkbox"] { accent-color: #ef4444; }
.schema-empty { font-size: 0.82rem; color: #4b5563; padding: 0.5rem 0; }
.btn-remove-field { background: none; border: none; color: #6b7280; cursor: pointer; font-size: 0.85rem; padding: 0.25rem; flex-shrink: 0; transition: color 0.15s; }
.btn-remove-field:hover { color: #fca5a5; }
.btn-add-field { padding: 0.45rem 0.875rem; border-radius: 0.5rem; background: none; border: 1px dashed rgba(239,68,68,0.25); color: #6b7280; font-size: 0.82rem; font-family: inherit; cursor: pointer; transition: all 0.18s; }
.btn-add-field:hover { border-color: rgba(239,68,68,0.4); color: #fca5a5; }

@media (max-width: 600px) {
  .form-grid { grid-template-columns: 1fr; }
  .form-group.full { grid-column: 1; }
  .form-group.checkboxes { grid-column: 1; }
  .page-title { font-size: 1.3rem; }
  .schema-row { flex-direction: column; align-items: stretch; }
  .schema-input, .schema-input-sm, .schema-select { width: 100%; }
}
</style>
