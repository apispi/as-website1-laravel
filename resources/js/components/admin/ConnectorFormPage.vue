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

        <div class="form-actions">
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

const oauthEnabled = ref(props.connector?.is_oauth ?? false);
const formAction   = props.connector ? `/admin/connectors/${props.connector.id}` : '/admin/connectors';

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

@media (max-width: 600px) {
  .form-grid { grid-template-columns: 1fr; }
  .form-group.full { grid-column: 1; }
  .form-group.checkboxes { grid-column: 1; }
  .page-title { font-size: 1.3rem; }
}
</style>
