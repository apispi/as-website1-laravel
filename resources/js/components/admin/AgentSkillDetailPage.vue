<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="all-agents">

    <!-- Breadcrumb -->
    <div class="back-row">
      <a :href="`/admin/agents/${agent.id}`" class="back-link">← {{ agent.name }}</a>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>

    <!-- Header -->
    <div class="page-header">
      <div class="header-main">
        <div class="skill-avatar">◇</div>
        <div class="header-text">
          <h1 class="page-title">{{ pivot.name || skill.catalog_name }}</h1>
          <div class="header-sub">
            <span class="muted">Agent skill on</span>
            <a :href="`/admin/agents/${agent.id}`" class="agent-link">{{ agent.name }}</a>
          </div>
        </div>
        <div class="header-actions">
          <span v-if="hasDrift" class="drift-pill">⚠ Drifted from catalog</span>
          <span v-else class="sync-pill">✓ In sync</span>
          <button class="btn-refresh" @click="showModal = true">↻ Refresh from Catalog</button>
        </div>
      </div>
    </div>

    <!-- Comparison cards -->
    <div class="compare-grid">

      <!-- Agent definition -->
      <div class="card">
        <div class="card-header">
          <div class="card-header-left">
            <span>Agent Definition</span>
            <span class="scope-note">Unique to {{ agent.name }}</span>
          </div>
          <button v-if="!editing" class="btn-edit-inline" @click="startEdit">Edit</button>
          <button v-else class="btn-cancel-inline" @click="cancelEdit">Cancel</button>
        </div>

        <!-- View mode -->
        <template v-if="!editing">
          <div class="field-block" :class="{ drifted: pivot.name !== skill.catalog_name }">
            <div class="field-label">Name</div>
            <div class="field-val">{{ pivot.name || '—' }}</div>
          </div>
          <div class="field-block" :class="{ drifted: pivot.category !== skill.catalog_category }">
            <div class="field-label">Category</div>
            <div class="field-val">{{ pivot.category || '—' }}</div>
          </div>
          <div class="field-block desc-block" :class="{ drifted: pivot.description !== skill.catalog_desc }">
            <div class="field-label">Description</div>
            <div class="field-val desc">{{ pivot.description || '—' }}</div>
          </div>
          <div class="field-block">
            <div class="field-label">Last Synced</div>
            <div class="field-val muted-val">{{ pivot.refreshed_at ? formatDate(pivot.refreshed_at) : 'Never' }}</div>
          </div>
        </template>

        <!-- Edit mode -->
        <template v-else>
          <form :action="`/admin/agents/${agent.id}/skills/${skill.id}`" method="POST" class="edit-form">
            <input type="hidden" name="_token" :value="csrfToken">
            <input type="hidden" name="_method" value="PUT">
            <div class="edit-field">
              <label class="field-label">Name</label>
              <input type="text" name="name" class="edit-input" v-model="editName" required>
            </div>
            <div class="edit-field">
              <label class="field-label">Category</label>
              <input type="text" name="category" class="edit-input" v-model="editCategory" placeholder="e.g. NLP, Document">
            </div>
            <div class="edit-field">
              <label class="field-label">Description</label>
              <textarea name="description" class="edit-textarea" v-model="editDescription" rows="4"></textarea>
            </div>
            <div class="edit-actions">
              <button type="submit" class="btn-save">Save Changes</button>
              <p class="scope-hint">Changes apply only to <strong>{{ agent.name }}</strong> — other agents and the catalog are unaffected.</p>
            </div>
          </form>
        </template>
      </div>

      <!-- Catalog definition -->
      <div class="card catalog-card">
        <div class="card-header">
          <span>Catalog Definition</span>
          <a :href="`/admin/skills/${skill.id}/edit`" class="edit-catalog-link">Edit in Catalog →</a>
        </div>
        <div class="field-block" :class="{ drifted: pivot.name !== skill.catalog_name }">
          <div class="field-label">Name</div>
          <div class="field-val">{{ skill.catalog_name || '—' }}</div>
        </div>
        <div class="field-block" :class="{ drifted: pivot.category !== skill.catalog_category }">
          <div class="field-label">Category</div>
          <div class="field-val">{{ skill.catalog_category || '—' }}</div>
        </div>
        <div class="field-block desc-block" :class="{ drifted: pivot.description !== skill.catalog_desc }">
          <div class="field-label">Description</div>
          <div class="field-val desc">{{ skill.catalog_desc || '—' }}</div>
        </div>
        <div class="field-block">
          <div class="field-label">Skill ID</div>
          <div class="field-val muted-val mono">{{ skill.id }}</div>
        </div>
      </div>

    </div>

    <!-- Refresh Confirmation Modal -->
    <Teleport to="body">
      <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
        <div class="modal">
          <div class="modal-header">
            <div class="modal-title">Refresh from catalog?</div>
            <button class="modal-close" @click="showModal = false">✕</button>
          </div>
          <div class="modal-body">
            <p class="modal-warn">
              This will overwrite the agent-specific definition of <strong>{{ pivot.name || skill.catalog_name }}</strong> with the current catalog values. Any customisations will be lost.
            </p>
            <div v-if="hasDrift" class="diff-block">
              <div class="diff-title">Changes that will be applied:</div>
              <div v-if="pivot.name !== skill.catalog_name" class="diff-row">
                <span class="diff-label">Name</span>
                <span class="diff-from">{{ pivot.name }}</span>
                <span class="diff-arrow">→</span>
                <span class="diff-to">{{ skill.catalog_name }}</span>
              </div>
              <div v-if="pivot.category !== skill.catalog_category" class="diff-row">
                <span class="diff-label">Category</span>
                <span class="diff-from">{{ pivot.category || '—' }}</span>
                <span class="diff-arrow">→</span>
                <span class="diff-to">{{ skill.catalog_category || '—' }}</span>
              </div>
              <div v-if="pivot.description !== skill.catalog_desc" class="diff-row diff-row-col">
                <span class="diff-label">Description</span>
                <div class="diff-desc-pair">
                  <div class="diff-desc from">{{ pivot.description || '—' }}</div>
                  <div class="diff-desc-arrow">↓</div>
                  <div class="diff-desc to">{{ skill.catalog_desc || '—' }}</div>
                </div>
              </div>
            </div>
            <div v-else class="no-drift">No differences — already in sync with the catalog.</div>
          </div>
          <div class="modal-footer">
            <button class="btn-cancel" @click="showModal = false">Cancel</button>
            <form :action="`/admin/agents/${agent.id}/skills/${skill.id}/refresh`" method="POST" style="display:inline">
              <input type="hidden" name="_token" :value="csrfToken">
              <button type="submit" class="btn-confirm-refresh">Refresh from Catalog</button>
            </form>
          </div>
        </div>
      </div>
    </Teleport>

  </AdminShell>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:  { type: Object, default: () => ({}) },
  csrfToken:    { type: String, default: '' },
  flashSuccess: { type: String, default: '' },
  agent:        { type: Object, default: () => ({}) },
  skill:        { type: Object, default: () => ({}) },
  pivot:        { type: Object, default: () => ({}) },
});

const showModal = ref(false);
const editing   = ref(false);
const editName        = ref('');
const editCategory    = ref('');
const editDescription = ref('');

function startEdit() {
  editName.value        = props.pivot.name        ?? '';
  editCategory.value    = props.pivot.category    ?? '';
  editDescription.value = props.pivot.description ?? '';
  editing.value = true;
}
function cancelEdit() { editing.value = false; }

const hasDrift = computed(() =>
  props.pivot.name        !== props.skill.catalog_name ||
  props.pivot.category    !== props.skill.catalog_category ||
  props.pivot.description !== props.skill.catalog_desc
);

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.flash.success { margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 0.5rem; background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.2); color: #00d97e; font-size: 0.875rem; }

.back-row { margin-bottom: 1rem; }
.back-link { font-size: 0.82rem; color: #6b7280; text-decoration: none; }
.back-link:hover { color: #fca5a5; }

.page-header { margin-bottom: 1.75rem; }
.header-main { display: flex; align-items: flex-start; gap: 1rem; flex-wrap: wrap; }
.skill-avatar {
  width: 44px; height: 44px; border-radius: 0.6rem; flex-shrink: 0;
  background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem; color: #fca5a5;
}
.header-text { flex: 1; min-width: 0; }
.page-title { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.25rem; }
.header-sub { font-size: 0.85rem; color: #6b7280; display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap; }
.agent-link { color: #fca5a5; text-decoration: none; font-weight: 500; }
.agent-link:hover { text-decoration: underline; }
.muted { color: #6b7280; }

.header-actions { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-left: auto; }
.drift-pill { padding: 0.25rem 0.65rem; border-radius: 99px; font-size: 0.72rem; font-weight: 700; background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
.sync-pill  { padding: 0.25rem 0.65rem; border-radius: 99px; font-size: 0.72rem; font-weight: 700; background: rgba(0,217,126,0.08); color: #00d97e; border: 1px solid rgba(0,217,126,0.2); }
.btn-refresh { padding: 0.45rem 0.9rem; border-radius: 0.45rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5; font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: all 0.15s; }
.btn-refresh:hover { background: rgba(239,68,68,0.18); }

/* Comparison grid */
.compare-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; max-width: 900px; }

.card { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; }
.catalog-card { border-color: rgba(107,114,128,0.15); background: rgba(12,12,14,0.6); }

.card-header-left { display: flex; flex-direction: column; gap: 0.15rem; }
.scope-note { font-size: 0.65rem; font-weight: 400; color: #4b5563; text-transform: none; letter-spacing: 0; }

.card-header {
  display: flex; justify-content: space-between; align-items: center;
  padding: 0.875rem 1.25rem;
  font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em;
  color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08);
  background: rgba(239,68,68,0.03);
}
.catalog-card .card-header { background: rgba(107,114,128,0.05); border-color: rgba(107,114,128,0.1); }
.card-sub { font-size: 0.7rem; font-weight: 400; color: #4b5563; text-transform: none; letter-spacing: 0; }
.edit-catalog-link { font-size: 0.72rem; font-weight: 600; color: #6b7280; text-decoration: none; text-transform: none; letter-spacing: 0; }
.edit-catalog-link:hover { color: #fca5a5; }

.field-block { padding: 0.875rem 1.25rem; border-bottom: 1px solid rgba(239,68,68,0.06); transition: background 0.15s; }
.catalog-card .field-block { border-color: rgba(107,114,128,0.08); }
.field-block:last-child { border-bottom: none; }
.field-block.drifted { background: rgba(245,158,11,0.04); }

.field-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 0.35rem; }
.field-val { font-size: 0.9rem; color: #e5e7eb; }
.field-val.desc { line-height: 1.6; font-size: 0.875rem; color: #d1d5db; }
.muted-val { color: #6b7280; font-size: 0.82rem; }
.mono { font-family: monospace; }
.desc-block { }

/* Inline edit */
.btn-edit-inline   { padding: 0.2rem 0.6rem; border-radius: 0.35rem; font-size: 0.72rem; font-weight: 600; cursor: pointer; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; transition: all 0.15s; }
.btn-edit-inline:hover { background: rgba(239,68,68,0.18); }
.btn-cancel-inline { padding: 0.2rem 0.6rem; border-radius: 0.35rem; font-size: 0.72rem; font-weight: 600; cursor: pointer; background: rgba(107,114,128,0.1); border: 1px solid rgba(107,114,128,0.2); color: #9ca3af; transition: all 0.15s; }
.btn-cancel-inline:hover { background: rgba(107,114,128,0.2); color: #e5e7eb; }

.edit-form { padding: 0; }
.edit-field { padding: 0.875rem 1.25rem; border-bottom: 1px solid rgba(239,68,68,0.06); display: flex; flex-direction: column; gap: 0.4rem; }
.edit-field:last-of-type { border-bottom: none; }
.edit-input, .edit-textarea { width: 100%; padding: 0.5rem 0.7rem; background: rgba(12,4,4,0.8); border: 1px solid rgba(252,211,77,0.35); border-radius: 0.4rem; color: #e5e7eb; font-size: 0.875rem; font-family: inherit; transition: border-color 0.18s; resize: vertical; }
.edit-input:focus, .edit-textarea:focus { outline: none; border-color: rgba(252,211,77,0.85); box-shadow: 0 0 0 3px rgba(252,211,77,0.1); }
.edit-actions { padding: 0.875rem 1.25rem; }
.btn-save { padding: 0.45rem 1.1rem; border-radius: 0.45rem; background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.15s; }
.btn-save:hover { background: rgba(239,68,68,0.24); }
.scope-hint { margin-top: 0.6rem; font-size: 0.75rem; color: #4b5563; line-height: 1.5; }
.scope-hint strong { color: #6b7280; }

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
  .compare-grid { grid-template-columns: 1fr; }
  .header-main { flex-wrap: wrap; }
  .header-actions { margin-left: 0; }
  .page-title { font-size: 1.2rem; }
}
</style>
