<template>
  <div class="uce-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <div class="uce-overlay" @click="sidebarOpen = false"></div>

    <aside class="uce-sidebar">
      <div class="uce-sidebar-header">
        <a href="/" class="uce-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="uce-logo-icon">
            <defs>
              <linearGradient id="ucelg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#ucelg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#ucelg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="uce-sidebar-close" @click="sidebarOpen = false">✕</button>
      </div>

      <nav class="uce-nav">
        <span class="uce-nav-label">Workspace</span>
        <a href="/dashboard"            class="uce-nav-link"><span class="uce-nav-icon">⬡</span> Home</a>
        <a href="/dashboard/agents"     class="uce-nav-link"><span class="uce-nav-icon">◈</span> My Agents</a>
        <a href="/dashboard/connectors" class="uce-nav-link active"><span class="uce-nav-icon">⬡</span> My Connectors</a>
        <a href="/dashboard/catalog"    class="uce-nav-link"><span class="uce-nav-icon">◎</span> Catalog</a>
        <a href="/dashboard/aria"       class="uce-nav-link"><span class="uce-nav-icon">◇</span> Aria</a>
        <a href="/training"             class="uce-nav-link"><span class="uce-nav-icon">◷</span> Training</a>
        <span class="uce-nav-label">Account</span>
        <a href="/contact"              class="uce-nav-link"><span class="uce-nav-icon">◉</span> Support</a>
        <template v-if="user.is_admin">
          <span class="uce-nav-label">Administration</span>
          <a href="/admin" class="uce-nav-link uce-nav-admin"><span class="uce-nav-icon">⬡</span> Admin Panel</a>
        </template>
      </nav>

      <div class="uce-sidebar-footer">
        <a href="/dashboard/profile" class="uce-user-row">
          <div class="uce-avatar">{{ initial }}</div>
          <div class="uce-user-text">
            <div class="uce-user-name">{{ user.name }}</div>
            <div class="uce-user-email">{{ user.email }}</div>
          </div>
        </a>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="uce-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="uce-main">
      <header class="uce-topbar">
        <button class="uce-menu-btn" @click="sidebarOpen = true"><span></span><span></span><span></span></button>
        <a href="/" class="uce-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" style="width:20px;height:20px;">
            <defs><linearGradient id="ucelg2" x1=".5" y1="0" x2=".5" y2="1"><stop offset="0%" stop-color="#FCD34D"/><stop offset="100%" stop-color="#D97706"/></linearGradient></defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#ucelg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#ucelg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="uce-avatar uce-avatar-sm">{{ initial }}</div>
      </header>

      <main class="uce-content">

        <div class="uce-breadcrumb">
          <a href="/dashboard/connectors" class="uce-back">← My Connectors</a>
        </div>

        <!-- Header -->
        <div class="uce-header">
          <div class="uce-header-icon">{{ connector.icon || '⬡' }}</div>
          <div class="uce-header-body">
            <h1 class="uce-title">{{ connector.name }}</h1>
            <div class="uce-header-meta">
              <span v-if="connector.category" class="uce-category">{{ connector.category }}</span>
              <span v-if="connector.category && userConnector.status" class="uce-sep">·</span>
              <span class="uce-status-badge" :class="userConnector.status">{{ userConnector.status }}</span>
              <span v-if="connector.is_oauth" class="uce-type-badge oauth">OAuth</span>
              <span v-else class="uce-type-badge api">API</span>
            </div>
          </div>
          <div class="uce-header-actions">
            <a v-if="connector.is_oauth && userConnector.status === 'active'"
               :href="`/connectors/${connector.slug}/disconnect`"
               class="uce-btn-ghost"
               onclick="return confirm('Disconnect this connector?')">
              Disconnect
            </a>
            <a v-else-if="connector.is_oauth"
               :href="`/connectors/${connector.slug}/authorize`"
               class="uce-btn-primary">
              Reconnect →
            </a>
          </div>
        </div>

        <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
        <div v-if="flashError"   class="flash error">{{ flashError }}</div>
        <div v-if="errors.length" class="flash error">
          <div v-for="e in errors" :key="e">{{ e }}</div>
        </div>

        <!-- Tabs -->
        <div class="tab-bar">
          <button v-for="t in tabs" :key="t.key"
                  :class="['tab-btn', { active: activeTab === t.key }]"
                  type="button"
                  @click="activeTab = t.key">
            {{ t.label }}
          </button>
        </div>

        <form :action="`/dashboard/connectors/${userConnector.id}`" method="POST" class="uce-form-card">
          <input type="hidden" name="_token" :value="csrfToken">
          <input type="hidden" name="_method" value="PUT">

          <!-- ── Tab: Details ── -->
          <div v-show="activeTab === 'details'">
            <div class="uce-info-grid">
              <div class="uce-info-item">
                <span class="uce-info-label">Status</span>
                <span class="uce-info-value">
                  <span class="uce-status-badge" :class="userConnector.status">{{ userConnector.status }}</span>
                </span>
              </div>
              <div class="uce-info-item" v-if="userConnector.connected_at">
                <span class="uce-info-label">Connected</span>
                <span class="uce-info-value">{{ formatDate(userConnector.connected_at) }}</span>
              </div>
              <div class="uce-info-item" v-if="connector.website_url">
                <span class="uce-info-label">Website</span>
                <a :href="connector.website_url" target="_blank" rel="noopener" class="uce-info-link">
                  {{ connector.website_url }} ↗
                </a>
              </div>
            </div>

            <div v-if="connector.description" class="uce-description">
              {{ connector.description }}
            </div>

            <div class="uce-section-title" style="margin-top:1.5rem;">Notes</div>
            <div class="uce-form-group full">
              <textarea name="notes" v-model="form.notes" rows="4"
                        placeholder="Add any notes about this connection…"></textarea>
            </div>

            <div class="uce-form-actions">
              <button type="submit" class="uce-btn-submit">Save</button>
              <a href="/dashboard/connectors" class="uce-btn-cancel">Back</a>
            </div>
          </div>

          <!-- ── Tab: Metadata ── -->
          <div v-show="activeTab === 'metadata'">
            <div class="uce-meta-grid">
              <div v-if="connector.version" class="uce-meta-item">
                <span class="uce-meta-label">Version</span>
                <span class="uce-meta-value">{{ connector.version }}</span>
              </div>
              <div v-if="connector.vendor" class="uce-meta-item">
                <span class="uce-meta-label">Vendor</span>
                <span class="uce-meta-value">{{ connector.vendor }}</span>
              </div>
              <div v-if="connector.owner" class="uce-meta-item">
                <span class="uce-meta-label">Owner</span>
                <span class="uce-meta-value">{{ connector.owner }}</span>
              </div>
              <div v-if="connector.status" class="uce-meta-item">
                <span class="uce-meta-label">Catalog Status</span>
                <span class="uce-meta-value uce-meta-capitalize">{{ connector.status }}</span>
              </div>
              <div v-if="connector.sla_tier" class="uce-meta-item">
                <span class="uce-meta-label">SLA Tier</span>
                <span class="uce-meta-value uce-meta-capitalize">{{ connector.sla_tier }}</span>
              </div>
              <div v-if="connector.environment?.length" class="uce-meta-item">
                <span class="uce-meta-label">Environments</span>
                <span class="uce-meta-value">
                  <span v-for="env in connector.environment" :key="env" class="uce-env-badge">{{ env }}</span>
                </span>
              </div>
              <div v-if="connector.tags?.length" class="uce-meta-item uce-meta-full">
                <span class="uce-meta-label">Tags</span>
                <span class="uce-meta-value">
                  <span v-for="tag in connector.tags" :key="tag" class="uce-tag">{{ tag }}</span>
                </span>
              </div>
            </div>

            <div v-if="!hasMetadata" class="uce-empty-state">
              No metadata available for this connector.
            </div>
          </div>

          <!-- ── Tab: Configuration ── -->
          <div v-show="activeTab === 'configuration'">
            <template v-if="schema.length">
              <div class="uce-form-grid">
                <div
                  v-for="field in schema"
                  :key="field.key"
                  :class="['uce-form-group', field.type === 'textarea' ? 'full' : '']"
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

              <div class="uce-form-actions">
                <button type="submit" class="uce-btn-submit">Save</button>
                <a href="/dashboard/connectors" class="uce-btn-cancel">Back</a>
              </div>
            </template>

            <div v-else class="uce-empty-state">
              No configuration fields for this connector.
            </div>
          </div>

        </form>

      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';

const props = defineProps({
  user:          { type: Object, default: () => ({}) },
  csrfToken:     { type: String, default: '' },
  userConnector: { type: Object, default: () => ({}) },
  flashSuccess:  { type: String, default: '' },
  flashError:    { type: String, default: '' },
  errors:        { type: Array,  default: () => [] },
});

const sidebarOpen = ref(false);
const initial     = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());
const connector   = computed(() => props.userConnector.connector ?? {});
const schema      = computed(() => connector.value.config_schema ?? []);
const config      = computed(() => props.userConnector.config ?? {});

const tabs = [
  { key: 'details',       label: 'Details' },
  { key: 'metadata',      label: 'Metadata' },
  { key: 'configuration', label: 'Configuration' },
];

const activeTab = ref(props.errors.length ? 'configuration' : 'details');

const form = reactive({ notes: props.userConnector.notes ?? '' });

function currentValue(key) { return config.value[key] ?? ''; }
function hasValue(key)      { return !!config.value[key]; }

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-AU', { day: 'numeric', month: 'short', year: 'numeric' });
}

const hasMetadata = computed(() => {
  const c = connector.value;
  return c.version || c.vendor || c.owner || c.status || c.sla_tier ||
         c.environment?.length || c.tags?.length;
});
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.uce-shell { display: flex; min-height: 100vh; background: #0D0B08; color: #e5e7eb; font-family: 'Instrument Sans', system-ui, sans-serif; }

.uce-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 90; }
.sidebar-open .uce-overlay { display: block; }

.uce-sidebar {
  width: 240px; background: rgba(15,11,5,0.99); border-right: 1px solid rgba(217,119,6,0.12);
  position: fixed; top: 0; left: 0; height: 100vh;
  display: flex; flex-direction: column; z-index: 100; overflow-y: auto;
  transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
}
.uce-sidebar-header { display: flex; align-items: center; gap: 0.5rem; padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.uce-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.1rem; font-weight: 700; flex: 1; }
.uce-logo-icon { width: 24px; height: 24px; }
.uce-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1rem; padding: 0.25rem; }

.uce-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.uce-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.uce-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.uce-nav-link:hover { background: rgba(217,119,6,0.08); color: #FCD34D; }
.uce-nav-link.active { background: rgba(217,119,6,0.12); color: #FCD34D; font-weight: 600; }
.uce-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
.uce-nav-admin { color: #ef4444 !important; }
.uce-nav-admin:hover { background: rgba(239,68,68,0.08) !important; color: #ef4444 !important; }

.uce-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.uce-user-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; text-decoration: none; }
.uce-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0D0B08; flex-shrink: 0; }
.uce-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.uce-user-name  { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; }
.uce-user-email { font-size: 0.72rem; color: #6b7280; }
.uce-signout { display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.5rem 0.75rem; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem; transition: all 0.18s; font-family: inherit; min-height: 44px; }
.uce-signout:hover { background: rgba(217,119,6,0.08); color: #D97706; }

.uce-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

.uce-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(13,11,8,0.98); border-bottom: 1px solid rgba(217,119,6,0.1); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.uce-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1rem; font-weight: 700; }
.uce-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.uce-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }

.uce-content { flex: 1; padding: 2rem; max-width: 720px; width: 100%; }

.uce-breadcrumb { margin-bottom: 1.25rem; }
.uce-back { font-size: 0.82rem; color: #6b7280; text-decoration: none; }
.uce-back:hover { color: #FCD34D; }

/* Header */
.uce-header {
  display: flex; align-items: center; gap: 1rem;
  background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.15);
  border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.25rem;
}
.uce-header-icon {
  width: 52px; height: 52px; border-radius: 0.75rem; flex-shrink: 0;
  background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.18);
  display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
}
.uce-header-body { flex: 1; min-width: 0; }
.uce-title { font-size: 1.35rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.uce-header-meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.uce-category { font-size: 0.8rem; color: #6b7280; }
.uce-sep { color: #4b5563; font-size: 0.8rem; }
.uce-status-badge { display: inline-block; padding: 0.18rem 0.5rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; text-transform: capitalize; }
.uce-status-badge.active       { background: rgba(0,217,126,0.1);    color: #00d97e; }
.uce-status-badge.disconnected { background: rgba(107,114,128,0.12); color: #9ca3af; }
.uce-type-badge { display: inline-block; padding: 0.1rem 0.4rem; border-radius: 99px; font-size: 0.65rem; font-weight: 700; }
.uce-type-badge.oauth { background: rgba(99,102,241,0.12); color: #a5b4fc; }
.uce-type-badge.api   { background: rgba(217,119,6,0.12);  color: #FCD34D; }
.uce-header-actions { display: flex; gap: 0.5rem; flex-shrink: 0; flex-wrap: wrap; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

/* Tabs */
.tab-bar {
  display: flex; gap: 0.25rem; margin-bottom: 0;
  border-bottom: 1px solid rgba(217,119,6,0.12);
}
.tab-btn {
  padding: 0.55rem 1.1rem; border-radius: 0.5rem 0.5rem 0 0;
  border: 1px solid transparent; border-bottom: none;
  background: none; color: #6b7280; font-size: 0.82rem; font-weight: 600;
  font-family: inherit; cursor: pointer; transition: all 0.15s;
  position: relative; bottom: -1px;
}
.tab-btn:hover { color: #d1d5db; background: rgba(217,119,6,0.05); }
.tab-btn.active {
  background: rgba(28,24,16,0.7); color: #FCD34D;
  border-color: rgba(217,119,6,0.12); border-bottom-color: rgba(28,24,16,0.7);
}

/* Form card */
.uce-form-card {
  background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.12);
  border-radius: 0 0.875rem 0.875rem 0.875rem; padding: 1.75rem;
  border-top: none;
}

/* Details tab */
.uce-info-grid { display: flex; flex-wrap: wrap; gap: 1.25rem; margin-bottom: 1.25rem; }
.uce-info-item { display: flex; flex-direction: column; gap: 0.25rem; min-width: 140px; }
.uce-info-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #4b5563; }
.uce-info-value { font-size: 0.88rem; color: #d1d5db; }
.uce-info-link  { font-size: 0.82rem; color: #FCD34D; text-decoration: none; word-break: break-all; }
.uce-info-link:hover { text-decoration: underline; }

.uce-description {
  font-size: 0.875rem; color: #9ca3af; line-height: 1.6;
  padding: 0.875rem 1rem; background: rgba(217,119,6,0.03);
  border: 1px solid rgba(217,119,6,0.08); border-radius: 0.5rem;
  margin-bottom: 0.25rem;
}

.uce-section-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 0.75rem; }

/* Metadata tab */
.uce-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.uce-meta-item { display: flex; flex-direction: column; gap: 0.3rem; padding: 0.875rem 1rem; background: rgba(217,119,6,0.03); border: 1px solid rgba(217,119,6,0.08); border-radius: 0.625rem; }
.uce-meta-item.uce-meta-full { grid-column: 1 / -1; }
.uce-meta-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #4b5563; }
.uce-meta-value { font-size: 0.875rem; color: #d1d5db; display: flex; flex-wrap: wrap; gap: 0.375rem; align-items: center; }
.uce-meta-capitalize { text-transform: capitalize; }
.uce-env-badge { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.2); color: #FCD34D; }
.uce-tag { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 99px; font-size: 0.72rem; color: #9ca3af; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); }

/* Configuration tab */
.uce-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
.uce-form-group { display: flex; flex-direction: column; gap: 0.35rem; }
.uce-form-group.full { grid-column: 1 / -1; }

label { font-size: 0.82rem; font-weight: 600; color: #d1d5db; }
.req  { color: #ef4444; }
.hint { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; }

input, textarea {
  padding: 0.65rem 0.875rem;
  background: rgba(18,14,8,0.9); border: 1px solid rgba(217,119,6,0.18);
  border-radius: 0.5rem; color: #e5e7eb; font-size: 0.95rem; font-family: inherit;
  transition: border-color 0.18s; width: 100%;
}
input:focus, textarea:focus { outline: none; border-color: rgba(217,119,6,0.5); }
input::placeholder, textarea::placeholder { color: #4b5563; }
textarea { resize: vertical; min-height: 90px; }

.uce-form-actions { display: flex; gap: 0.75rem; align-items: center; margin-top: 1.5rem; }

.uce-btn-submit {
  padding: 0.65rem 1.5rem; border-radius: 0.625rem;
  background: rgba(217,119,6,0.15); border: 1px solid rgba(217,119,6,0.4);
  color: #FCD34D; font-size: 0.9rem; font-weight: 600; cursor: pointer;
  font-family: inherit; transition: all 0.18s; min-height: 44px;
}
.uce-btn-submit:hover { background: rgba(217,119,6,0.25); }

.uce-btn-cancel {
  padding: 0.65rem 1.25rem; border-radius: 0.625rem;
  border: 1px solid rgba(217,119,6,0.12); color: #6b7280;
  font-size: 0.875rem; text-decoration: none; transition: all 0.18s;
  min-height: 44px; display: inline-flex; align-items: center;
}
.uce-btn-cancel:hover { border-color: rgba(217,119,6,0.3); color: #9ca3af; }

.uce-btn-primary {
  padding: 0.5rem 1rem; border-radius: 0.5rem;
  background: rgba(217,119,6,0.15); border: 1px solid rgba(217,119,6,0.35);
  color: #FCD34D; font-size: 0.82rem; font-weight: 600;
  text-decoration: none; transition: all 0.18s; white-space: nowrap;
}
.uce-btn-primary:hover { background: rgba(217,119,6,0.25); }

.uce-btn-ghost {
  padding: 0.5rem 1rem; border-radius: 0.5rem;
  background: none; border: 1px solid rgba(239,68,68,0.2);
  color: #9ca3af; font-size: 0.82rem; font-weight: 600;
  text-decoration: none; transition: all 0.18s; white-space: nowrap;
}
.uce-btn-ghost:hover { border-color: rgba(239,68,68,0.4); color: #fca5a5; }

.uce-empty-state {
  padding: 2.5rem; text-align: center;
  font-size: 0.875rem; color: #4b5563;
}

@media (max-width: 768px) {
  .uce-sidebar { transform: translateX(-100%); }
  .sidebar-open .uce-sidebar { transform: translateX(0); }
  .uce-sidebar-close { display: block; }
  .uce-main { margin-left: 0; }
  .uce-topbar { display: flex; }
  .uce-content { padding: 1.25rem; }
  .uce-header { flex-wrap: wrap; }
  .uce-meta-grid { grid-template-columns: 1fr; }
  .uce-meta-item.uce-meta-full { grid-column: 1; }
}
@media (max-width: 600px) {
  .uce-form-grid { grid-template-columns: 1fr; }
  .uce-form-group.full { grid-column: 1; }
}
</style>
