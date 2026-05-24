<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="all-agents">

    <div class="page-header">
      <a href="/admin/subscriptions" class="back-link">← Active Agents</a>
      <div class="header-row">
        <div class="agent-avatar">◈</div>
        <div>
          <h1 class="page-title">{{ agent.name }}</h1>
          <div class="agent-meta">
            <span v-if="agent.category" class="meta-item">{{ agent.category }}</span>
            <span v-if="agent.badge" class="badge-inline" :class="agent.badge.toLowerCase()">{{ agent.badge }}</span>
            <span class="status-badge" :class="agent.is_active ? 'active' : 'inactive'">
              {{ agent.is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
        <a :href="`/admin/agents/${agent.id}/edit`" class="btn-edit">Edit Agent</a>
      </div>
    </div>

    <!-- Top-level tabs -->
    <div class="top-tab-bar">
      <button :class="['top-tab', { active: tab === 'details' }]"     @click="tab = 'details'">Details</button>
      <button :class="['top-tab', { active: tab === 'subscribers' }]" @click="tab = 'subscribers'">
        Subscribers <span class="top-tab-count">{{ subscriptions.length }}</span>
      </button>
      <button :class="['top-tab', { active: tab === 'skills' }]"      @click="tab = 'skills'">
        Skills <span class="top-tab-count">{{ skills.length }}</span>
      </button>
      <button :class="['top-tab', { active: tab === 'connectors' }]"  @click="tab = 'connectors'">
        Connectors <span class="top-tab-count">{{ connectors.length }}</span>
      </button>
    </div>

    <!-- ── DETAILS TAB ── -->
    <div v-show="tab === 'details'" class="tab-content">
      <div class="detail-grid">

        <div class="card">
          <div class="card-header">Overview</div>
          <div class="info-rows">
            <div class="info-row">
              <span class="info-label">Slug</span>
              <span class="info-val mono">{{ agent.slug }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Rating</span>
              <span class="info-val">{{ agent.rating ? '⭐ ' + agent.rating : '—' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Users</span>
              <span class="info-val">{{ agent.users_count || '—' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Price</span>
              <span class="info-val price">{{ agent.price || '—' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Sort order</span>
              <span class="info-val mono">{{ agent.sort_order ?? 0 }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Featured</span>
              <span class="info-val">{{ agent.is_featured ? 'Yes' : 'No' }}</span>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">Description</div>
          <div class="card-body">
            <p class="description-text">{{ agent.description || '—' }}</p>
          </div>
        </div>

        <div v-if="agent.tagline || agent.target_audience || agent.cta_headline" class="card span-2">
          <div class="card-header">Positioning</div>
          <div class="info-rows">
            <div v-if="agent.tagline" class="info-row">
              <span class="info-label">Tagline</span>
              <span class="info-val">{{ agent.tagline }}</span>
            </div>
            <div v-if="agent.target_audience" class="info-row">
              <span class="info-label">Target Audience</span>
              <span class="info-val">{{ agent.target_audience }}</span>
            </div>
            <div v-if="agent.cta_headline" class="info-row">
              <span class="info-label">CTA Headline</span>
              <span class="info-val">{{ agent.cta_headline }}</span>
            </div>
          </div>
        </div>

        <div v-if="agent.features?.length" class="card">
          <div class="card-header">Key Capabilities</div>
          <ul class="feature-list">
            <li v-for="f in agent.features" :key="f">{{ f }}</li>
          </ul>
        </div>

        <div v-if="agent.includes?.length" class="card">
          <div class="card-header">What's Included</div>
          <ul class="feature-list">
            <li v-for="f in agent.includes" :key="f">{{ f }}</li>
          </ul>
        </div>

      </div>
    </div>

    <!-- ── SUBSCRIBERS TAB ── -->
    <div v-show="tab === 'subscribers'" class="tab-content">
      <div class="sub-stats">
        <div class="stat-pill"><span class="stat-num">{{ activeCount }}</span> active</div>
        <div class="stat-pill"><span class="stat-num">{{ cancelledCount }}</span> cancelled</div>
        <div class="stat-pill"><span class="stat-num">{{ expiredCount }}</span> expired</div>
      </div>

      <div v-if="subscriptions.length === 0" class="empty-state">
        <div class="empty-icon">◈</div>
        <div class="empty-title">No subscribers yet</div>
        <div class="empty-desc">Assign this agent to users from the Users section.</div>
      </div>

      <div v-else class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>User</th>
              <th>Status</th>
              <th>Started</th>
              <th>Expires</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="sub in subscriptions" :key="sub.id">
              <td>
                <a :href="`/admin/users/${sub.user?.id}?tab=agents`" class="user-cell">
                  <div class="user-avatar">{{ sub.user?.name?.charAt(0)?.toUpperCase() ?? '?' }}</div>
                  <div>
                    <div class="user-name">{{ sub.user?.name ?? '—' }}</div>
                    <div class="user-email">{{ sub.user?.email ?? '' }}</div>
                  </div>
                </a>
              </td>
              <td><span class="status-badge" :class="sub.status">{{ sub.status }}</span></td>
              <td class="muted small">{{ formatDate(sub.started_at) }}</td>
              <td class="muted small">{{ sub.expires_at ? formatDate(sub.expires_at) : 'Ongoing' }}</td>
              <td class="actions">
                <a :href="`/admin/users/${sub.user?.id}?tab=agents`" class="btn-ghost">Manage →</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── SKILLS TAB ── -->
    <div v-show="tab === 'skills'" class="tab-content">
      <div v-if="skills.length === 0" class="empty-state">
        <div class="empty-icon">◇</div>
        <div class="empty-title">No skills assigned</div>
        <div class="empty-desc">
          <a :href="`/admin/agents/${agent.id}/edit?tab=skills`" class="empty-link">Edit agent to assign skills →</a>
        </div>
      </div>
      <div v-else class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Skill</th>
              <th>Category</th>
              <th>Description</th>
              <th>Last Synced</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="s in skills" :key="s.id">
              <td>
                <div class="skill-name-cell">
                  <a :href="`/admin/agents/${agent.id}/skills/${s.id}`" class="item-name skill-link">{{ s.name }}</a>
                  <span v-if="hasDrift(s)" class="drift-badge" title="This skill's definition differs from the catalog">⚠ Drifted</span>
                </div>
              </td>
              <td class="muted">{{ s.category || '—' }}</td>
              <td class="muted skill-desc">{{ s.description || '—' }}</td>
              <td class="muted small">{{ s.refreshed_at ? formatDate(s.refreshed_at) : 'Never' }}</td>
              <td class="actions">
                <button class="btn-refresh" @click="openRefreshModal(s)">↻ Refresh</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Refresh Confirmation Modal -->
    <Teleport to="body">
      <div v-if="refreshTarget" class="modal-overlay" @click.self="closeModal">
        <div class="modal">
          <div class="modal-header">
            <div class="modal-title">Refresh skill from catalog?</div>
            <button class="modal-close" @click="closeModal">✕</button>
          </div>
          <div class="modal-body">
            <p class="modal-warn">
              This will overwrite <strong>{{ refreshTarget.name }}</strong>'s agent-specific definition with the current catalog values. Any customisations will be lost.
            </p>
            <div v-if="hasDrift(refreshTarget)" class="diff-block">
              <div class="diff-title">Changes that will be applied:</div>
              <div v-if="refreshTarget.name !== refreshTarget.catalog_name" class="diff-row">
                <span class="diff-label">Name</span>
                <span class="diff-from">{{ refreshTarget.name }}</span>
                <span class="diff-arrow">→</span>
                <span class="diff-to">{{ refreshTarget.catalog_name }}</span>
              </div>
              <div v-if="refreshTarget.category !== refreshTarget.catalog_category" class="diff-row">
                <span class="diff-label">Category</span>
                <span class="diff-from">{{ refreshTarget.category || '—' }}</span>
                <span class="diff-arrow">→</span>
                <span class="diff-to">{{ refreshTarget.catalog_category || '—' }}</span>
              </div>
              <div v-if="refreshTarget.description !== refreshTarget.catalog_desc" class="diff-row diff-row-col">
                <span class="diff-label">Description</span>
                <div class="diff-desc-pair">
                  <div class="diff-desc from">{{ refreshTarget.description || '—' }}</div>
                  <div class="diff-desc-arrow">↓</div>
                  <div class="diff-desc to">{{ refreshTarget.catalog_desc || '—' }}</div>
                </div>
              </div>
            </div>
            <div v-else class="no-drift">
              No differences found — this agent's definition already matches the catalog.
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn-cancel" @click="closeModal">Cancel</button>
            <form :action="`/admin/agents/${agent.id}/skills/${refreshTarget.id}/refresh`" method="POST" style="display:inline">
              <input type="hidden" name="_token" :value="csrfToken">
              <button type="submit" class="btn-confirm-refresh">Refresh from Catalog</button>
            </form>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- ── CONNECTORS TAB ── -->
    <div v-show="tab === 'connectors'" class="tab-content">
      <div v-if="connectors.length === 0" class="empty-state">
        <div class="empty-icon">⬡</div>
        <div class="empty-title">No connectors assigned</div>
        <div class="empty-desc">
          <a :href="`/admin/agents/${agent.id}/edit?tab=connectors`" class="empty-link">Edit agent to assign connectors →</a>
        </div>
      </div>
      <div v-else class="table-wrap">
        <table class="data-table">
          <thead>
            <tr><th>Connector</th><th>Category</th><th>Type</th></tr>
          </thead>
          <tbody>
            <tr v-for="c in connectors" :key="c.id">
              <td>
                <div class="item-cell">
                  <div class="item-icon">{{ c.icon || '⬡' }}</div>
                  <span class="item-name">{{ c.name }}</span>
                </div>
              </td>
              <td class="muted">{{ c.category || '—' }}</td>
              <td>
                <span class="type-badge" :class="c.is_oauth ? 'oauth' : 'api'">
                  {{ c.is_oauth ? 'OAuth' : 'API' }}
                </span>
              </td>
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
  user:          { type: Object, default: () => ({}) },
  csrfToken:     { type: String, default: '' },
  agent:         { type: Object, default: () => ({}) },
  skills:        { type: Array,  default: () => [] },
  connectors:    { type: Array,  default: () => [] },
  subscriptions: { type: Array,  default: () => [] },
});

const tab           = ref('details');
const refreshTarget = ref(null);

const activeCount    = computed(() => props.subscriptions.filter(s => s.status === 'active').length);
const cancelledCount = computed(() => props.subscriptions.filter(s => s.status === 'cancelled').length);
const expiredCount   = computed(() => props.subscriptions.filter(s => s.status === 'expired').length);

function hasDrift(s) {
  return s.name !== s.catalog_name
    || s.category !== s.catalog_category
    || s.description !== s.catalog_desc;
}

function openRefreshModal(skill) { refreshTarget.value = skill; }
function closeModal()            { refreshTarget.value = null; }

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.75rem; }
.back-link:hover { color: #fca5a5; }

.header-row { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.agent-avatar {
  width: 48px; height: 48px; border-radius: 0.75rem; flex-shrink: 0;
  background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem; color: #fca5a5;
}
.page-title { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.agent-meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.meta-item  { font-size: 0.82rem; color: #6b7280; }

.badge-inline { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; }
.badge-inline.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.badge-inline.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.badge-inline.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }

.status-badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 99px; font-size: 0.68rem; font-weight: 600; text-transform: capitalize; }
.status-badge.active    { background: rgba(0,217,126,0.1);   color: #00d97e; }
.status-badge.inactive  { background: rgba(107,114,128,0.12); color: #9ca3af; }
.status-badge.cancelled { background: rgba(239,68,68,0.1);   color: #fca5a5; }
.status-badge.expired   { background: rgba(107,114,128,0.12); color: #9ca3af; }

.btn-edit {
  margin-left: auto; padding: 0.5rem 1rem; border-radius: 0.5rem;
  background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3);
  color: #fca5a5; font-size: 0.82rem; font-weight: 600;
  text-decoration: none; transition: all 0.18s; white-space: nowrap;
}
.btn-edit:hover { background: rgba(239,68,68,0.22); }

/* Tabs */
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

/* Subscriber stats */
.sub-stats { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
.stat-pill { padding: 0.4rem 0.85rem; border-radius: 99px; background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.15); font-size: 0.82rem; color: #9ca3af; }
.stat-num  { font-weight: 700; color: #fca5a5; }

/* Detail grid */
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; }
.card { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; }
.card.span-2 { grid-column: 1 / -1; }
.card-header { padding: 0.875rem 1.25rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.card-body { padding: 1.25rem; }
.description-text { font-size: 0.9rem; color: #d1d5db; line-height: 1.65; }

.info-rows {}
.info-row { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1.25rem; border-bottom: 1px solid rgba(239,68,68,0.06); gap: 1rem; }
.info-row:last-child { border-bottom: none; }
.info-label { font-size: 0.82rem; color: #6b7280; flex-shrink: 0; }
.info-val { font-size: 0.875rem; color: #e5e7eb; text-align: right; }
.info-val.price { color: #FCD34D; font-weight: 600; }
.mono { font-family: monospace; font-size: 0.8rem; color: #9ca3af; }

.feature-list { list-style: none; padding: 0.75rem 1.25rem; display: flex; flex-direction: column; gap: 0.5rem; }
.feature-list li { font-size: 0.875rem; color: #d1d5db; padding-left: 1rem; position: relative; }
.feature-list li::before { content: '›'; position: absolute; left: 0; color: #ef4444; font-weight: 700; }

/* Tables */
.table-wrap { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.03); }

.user-cell { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
.user-cell:hover .user-name { color: #fca5a5; }
.user-avatar {
  width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #ef4444, #fca5a5);
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 0.78rem; color: #fff;
}
.user-name  { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.user-email { font-size: 0.75rem; color: #6b7280; margin-top: 0.1rem; }

.item-cell { display: flex; align-items: center; gap: 0.65rem; }
.item-icon { width: 28px; height: 28px; border-radius: 0.375rem; flex-shrink: 0; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; }
.item-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.muted { color: #6b7280; font-size: 0.875rem; }
.small { font-size: 0.8rem; white-space: nowrap; }

.type-badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 99px; font-size: 0.68rem; font-weight: 700; }
.type-badge.oauth { background: rgba(99,102,241,0.12); color: #a5b4fc; }
.type-badge.api   { background: rgba(217,119,6,0.12);  color: #FCD34D; }

.actions { text-align: right; }
.btn-ghost { display: inline-block; padding: 0.3rem 0.55rem; border: 1px solid rgba(239,68,68,0.15); border-radius: 0.4rem; color: #9ca3af; font-size: 0.78rem; text-decoration: none; transition: all 0.18s; }
.btn-ghost:hover { border-color: rgba(239,68,68,0.35); color: #fca5a5; }

.empty-state { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px; }
.empty-icon  { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.empty-desc  { font-size: 0.875rem; color: #6b7280; }
.empty-link  { color: #fca5a5; text-decoration: none; }
.empty-link:hover { text-decoration: underline; }

/* Skills table extras */
.skill-link { text-decoration: none; }
.skill-link:hover { color: #fca5a5 !important; text-decoration: underline; }
.skill-name-cell { display: flex; align-items: center; gap: 0.5rem; }
.drift-badge { font-size: 0.65rem; font-weight: 700; padding: 0.12rem 0.45rem; border-radius: 99px; background: rgba(245,158,11,0.12); color: #fbbf24; white-space: nowrap; }
.skill-desc { max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.8rem; }
.btn-refresh { padding: 0.3rem 0.65rem; border-radius: 0.4rem; background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.18); color: #9ca3af; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.15s; white-space: nowrap; }
.btn-refresh:hover { background: rgba(239,68,68,0.15); color: #fca5a5; border-color: rgba(239,68,68,0.35); }

/* Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 1rem; }
.modal { background: #140808; border: 1px solid rgba(239,68,68,0.2); border-radius: 1rem; width: 100%; max-width: 520px; overflow: hidden; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.1rem 1.4rem; border-bottom: 1px solid rgba(239,68,68,0.1); }
.modal-title { font-size: 1rem; font-weight: 700; color: #f1f5f9; }
.modal-close { background: none; border: none; color: #6b7280; cursor: pointer; font-size: 1rem; padding: 0.2rem; line-height: 1; }
.modal-close:hover { color: #fca5a5; }
.modal-body { padding: 1.25rem 1.4rem; }
.modal-warn { font-size: 0.875rem; color: #d1d5db; line-height: 1.6; margin-bottom: 1rem; }
.modal-warn strong { color: #fca5a5; }

.diff-block { background: rgba(239,68,68,0.04); border: 1px solid rgba(239,68,68,0.12); border-radius: 0.6rem; padding: 0.75rem 1rem; display: flex; flex-direction: column; gap: 0.6rem; }
.diff-title { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 0.25rem; }
.diff-row { display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; font-size: 0.82rem; }
.diff-row-col { flex-direction: column; align-items: flex-start; }
.diff-label { font-size: 0.7rem; font-weight: 600; color: #6b7280; min-width: 70px; }
.diff-from { color: #fca5a5; text-decoration: line-through; opacity: 0.7; }
.diff-arrow { color: #4b5563; }
.diff-to { color: #00d97e; font-weight: 600; }
.diff-desc-pair { display: flex; flex-direction: column; gap: 0.3rem; width: 100%; margin-top: 0.2rem; }
.diff-desc { font-size: 0.8rem; padding: 0.4rem 0.6rem; border-radius: 0.35rem; line-height: 1.5; }
.diff-desc.from { background: rgba(239,68,68,0.08); color: #fca5a5; text-decoration: line-through; opacity: 0.7; }
.diff-desc.to   { background: rgba(0,217,126,0.06);  color: #00d97e; }
.diff-desc-arrow { color: #4b5563; font-size: 0.8rem; padding-left: 0.4rem; }
.no-drift { font-size: 0.85rem; color: #6b7280; font-style: italic; }

.modal-footer { display: flex; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.4rem; border-top: 1px solid rgba(239,68,68,0.1); }
.btn-cancel { padding: 0.5rem 1rem; border-radius: 0.5rem; background: rgba(107,114,128,0.1); border: 1px solid rgba(107,114,128,0.2); color: #9ca3af; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.15s; }
.btn-cancel:hover { background: rgba(107,114,128,0.2); color: #e5e7eb; }
.btn-confirm-refresh { padding: 0.5rem 1rem; border-radius: 0.5rem; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35); color: #fca5a5; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.15s; }
.btn-confirm-refresh:hover { background: rgba(239,68,68,0.28); }

@media (max-width: 640px) {
  .detail-grid { grid-template-columns: 1fr; }
  .card.span-2 { grid-column: 1; }
  .page-title { font-size: 1.25rem; }
  .btn-edit { margin-left: 0; }
  .top-tab { padding: 0.5rem 0.75rem; font-size: 0.8rem; }
}
</style>
