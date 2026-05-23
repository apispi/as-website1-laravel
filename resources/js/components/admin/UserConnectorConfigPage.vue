<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="users">

    <div class="page-header">
      <a :href="`/admin/users/${subject.id}/connectors`" class="back-link">← {{ subject.name }} — Connectors</a>
      <h1 class="page-title">
        <span class="connector-icon">{{ userConnector.connector.icon || '⬡' }}</span>
        {{ userConnector.connector.name }} Config
      </h1>
      <p class="page-sub">Configuration for {{ subject.name }}</p>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>
    <div v-if="errors.length" class="flash error">
      <div v-for="e in errors" :key="e">{{ e }}</div>
    </div>

    <div v-if="!schema.length" class="empty-state">
      <div class="empty-icon">⬡</div>
      <div class="empty-title">No config fields defined</div>
      <div class="empty-desc">
        Add config fields to the
        <a :href="`/admin/connectors/${userConnector.connector.id}/edit`" class="empty-link">
          {{ userConnector.connector.name }} connector
        </a>
        to enable per-user configuration.
      </div>
    </div>

    <div v-else class="form-card">
      <form method="POST" :action="formAction">
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

  </AdminShell>
</template>

<script setup>
import { computed } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:   { type: Object, default: () => ({}) },
  csrfToken:     { type: String, default: '' },
  subject:       { type: Object, default: () => ({}) },
  userConnector: { type: Object, default: () => ({}) },
  errors:        { type: Array,  default: () => [] },
  flashSuccess:  { type: String, default: '' },
  flashError:    { type: String, default: '' },
});

const schema     = computed(() => props.userConnector.connector.config_schema || []);
const config     = computed(() => props.userConnector.config || {});
const formAction = computed(() =>
  `/admin/users/${props.subject.id}/connectors/${props.userConnector.id}/config`
);

function currentValue(key) { return config.value[key] ?? ''; }
function hasValue(key)      { return !!config.value[key]; }
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.4rem; }
.back-link:hover { color: #fca5a5; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.6rem; }
.connector-icon { font-size: 1.4rem; }
.page-sub { color: #6b7280; font-size: 0.875rem; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.empty-state { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 480px; }
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; }
.empty-link  { color: #fca5a5; text-decoration: none; }
.empty-link:hover { text-decoration: underline; }

.form-card { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; padding: 1.75rem; max-width: 700px; }

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
.form-group { display: flex; flex-direction: column; gap: 0.35rem; }
.form-group.full { grid-column: 1 / -1; }

label { font-size: 0.82rem; font-weight: 600; color: #d1d5db; }
.req  { color: #ef4444; }
.hint { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; }

input, textarea {
  padding: 0.65rem 0.875rem;
  background: rgba(12,4,4,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.5rem; color: #e5e7eb; font-size: 1rem; font-family: inherit;
  transition: border-color 0.18s; width: 100%;
}
input:focus, textarea:focus { outline: none; border-color: rgba(239,68,68,0.45); }
input::placeholder, textarea::placeholder { color: #4b5563; }
textarea { resize: vertical; min-height: 80px; }

.form-actions { display: flex; gap: 0.75rem; align-items: center; }
.btn-submit { padding: 0.65rem 1.5rem; border-radius: 0.625rem; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35); color: #fca5a5; font-size: 0.9rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.18s; min-height: 44px; }
.btn-submit:hover { background: rgba(239,68,68,0.25); }
.btn-cancel { padding: 0.65rem 1.25rem; border-radius: 0.625rem; border: 1px solid rgba(239,68,68,0.12); color: #6b7280; font-size: 0.875rem; text-decoration: none; transition: all 0.18s; min-height: 44px; display: inline-flex; align-items: center; }
.btn-cancel:hover { border-color: rgba(239,68,68,0.25); color: #9ca3af; }

@media (max-width: 600px) {
  .form-grid { grid-template-columns: 1fr; }
  .form-group.full { grid-column: 1; }
  .page-title { font-size: 1.3rem; }
}
</style>
