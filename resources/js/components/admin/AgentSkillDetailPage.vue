<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="all-agents">

    <div class="back-row">
      <a :href="`/admin/agents/${agent.id}/edit?tab=skills`" class="back-link">← {{ agent.name }}</a>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>

    <div class="page-header">
      <div class="header-main">
        <div class="skill-avatar">◇</div>
        <div class="header-text">
          <h1 class="page-title">{{ skill.catalog_name }}</h1>
          <div class="header-sub">
            <span class="muted">Skill on</span>
            <a :href="`/admin/agents/${agent.id}/edit?tab=skills`" class="agent-link">{{ agent.name }}</a>
          </div>
        </div>
        <div class="header-actions">
          <a :href="`/admin/skills/${skill.id}/edit`" class="btn-edit-catalog">Edit in Catalog →</a>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="field-block">
        <div class="field-label">Name</div>
        <div class="field-val">{{ skill.catalog_name || '—' }}</div>
      </div>
      <div class="field-block">
        <div class="field-label">Category</div>
        <div class="field-val">{{ skill.catalog_category || '—' }}</div>
      </div>
      <div class="field-block">
        <div class="field-label">Slug</div>
        <div class="field-val mono muted-val">{{ skill.slug || '—' }}</div>
      </div>
      <div class="field-block desc-block">
        <div class="field-label">Description</div>
        <div class="field-val desc">{{ skill.catalog_desc || '—' }}</div>
      </div>
      <div class="field-block">
        <div class="field-label">Skill ID</div>
        <div class="field-val mono muted-val">{{ skill.id }}</div>
      </div>
    </div>

  </AdminShell>
</template>

<script setup>
import AdminShell from './AdminShell.vue';

defineProps({
  currentUser:  { type: Object, default: () => ({}) },
  csrfToken:    { type: String, default: '' },
  flashSuccess: { type: String, default: '' },
  agent:        { type: Object, default: () => ({}) },
  skill:        { type: Object, default: () => ({}) },
  pivot:        { type: Object, default: () => ({}) },
});
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
.btn-edit-catalog {
  padding: 0.45rem 0.9rem; border-radius: 0.45rem;
  border: 1px solid rgba(239,68,68,0.2); color: #9ca3af;
  font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: all 0.15s;
}
.btn-edit-catalog:hover { border-color: rgba(239,68,68,0.4); color: #fca5a5; }

.card { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; max-width: 560px; }

.field-block { padding: 0.875rem 1.25rem; border-bottom: 1px solid rgba(239,68,68,0.06); }
.field-block:last-child { border-bottom: none; }

.field-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 0.35rem; }
.field-val { font-size: 0.9rem; color: #e5e7eb; }
.field-val.desc { line-height: 1.6; font-size: 0.875rem; color: #d1d5db; }
.muted-val { color: #6b7280; font-size: 0.82rem; }
.mono { font-family: monospace; }

@media (max-width: 640px) {
  .header-main { flex-wrap: wrap; }
  .header-actions { margin-left: 0; }
  .page-title { font-size: 1.2rem; }
}
</style>
