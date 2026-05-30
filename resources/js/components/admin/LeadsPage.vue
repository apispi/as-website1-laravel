<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="leads">

    <div class="page-header">
      <div>
        <h1 class="page-title">Leads</h1>
        <p class="page-sub">{{ filtered.length }} of {{ leads.length }} leads</p>
      </div>
      <div style="display:flex;gap:0.5rem;">
        <a href="/digital-avatars" target="_blank" class="btn-ghost">Digital Avatars ↗</a>
        <a href="/partners" target="_blank" class="btn-ghost">Partners ↗</a>
      </div>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>

    <div class="toolbar">
      <div class="search-wrap">
        <span class="search-icon">◎</span>
        <input v-model="search" type="text" placeholder="Search name, email, company…" class="search-input">
      </div>
      <div class="filter-tabs">
        <button v-for="f in sourceFilters" :key="'src-'+f"
                :class="['tab', { active: sourceFilter === f }]"
                @click="sourceFilter = f">
          {{ f }}
        </button>
      </div>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Source</th>
            <th>Details</th>
            <th>Submitted</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="lead in pageRows" :key="lead.id">
            <td class="name-cell">{{ lead.name }}</td>
            <td>
              <a :href="`mailto:${lead.email}`" class="email-link">{{ lead.email }}</a>
            </td>
            <td class="muted">{{ lead.company || '—' }}</td>
            <td>
              <span :class="['source-badge', lead.source === 'partner' ? 'source-partner' : lead.source === 'contact' ? 'source-contact' : 'source-avatar']">
                {{ lead.source === 'partner' ? 'Partner' : lead.source === 'contact' ? 'Contact' : 'Avatar' }}
              </span>
            </td>
            <td class="muted use-cell">
              <template v-if="lead.source === 'partner'">
                <span v-if="lead.partner_type" class="role-badge">{{ lead.partner_type }}</span>
                <span v-if="lead.use_case" class="muted" style="display:block;font-size:0.78rem;margin-top:0.2rem;">{{ lead.use_case }}</span>
              </template>
              <template v-else>
                <span v-if="lead.role" class="role-badge">{{ lead.role }}</span>
                <span v-if="lead.use_case" class="muted" style="display:block;font-size:0.78rem;margin-top:0.2rem;">{{ lead.use_case }}</span>
              </template>
            </td>
            <td class="muted date-cell">{{ formatDate(lead.created_at) }}</td>
            <td class="actions">
              <form method="POST" :action="`/admin/leads/${lead.id}`" style="display:inline"
                    @submit.prevent="confirmDelete($event, lead.name)">
                <input type="hidden" name="_token" :value="csrfToken">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn-danger">Delete</button>
              </form>
            </td>
          </tr>
          <tr v-if="filtered.length === 0">
            <td colspan="7" class="empty-row">No leads found</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="totalPages > 1" class="pagination">
      <button class="page-btn" :disabled="curPage === 1" @click="curPage--">‹</button>
      <template v-for="p in pageNumbers" :key="p">
        <span v-if="p === '…'" class="page-ellipsis">…</span>
        <button v-else :class="['page-btn', { active: p === curPage }]" @click="curPage = p">{{ p }}</button>
      </template>
      <button class="page-btn" :disabled="curPage === totalPages" @click="curPage++">›</button>
    </div>

  </AdminShell>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:         { type: Object, default: () => ({}) },
  csrfToken:    { type: String, default: '' },
  leads:        { type: Array,  default: () => [] },
  flashSuccess: { type: String, default: '' },
});

const search       = ref('');
const sourceFilter = ref('All');
const curPage      = ref(1);
const PER_PAGE     = 25;

const sourceFilters = ['All', 'Avatar', 'Partner', 'Contact'];

const filtered = computed(() => {
  const q = search.value.toLowerCase();
  return props.leads.filter(l => {
    const matchSearch = !q ||
      (l.name  || '').toLowerCase().includes(q) ||
      (l.email || '').toLowerCase().includes(q) ||
      (l.company || '').toLowerCase().includes(q);
    const src = l.source === 'partner' ? 'Partner' : l.source === 'contact' ? 'Contact' : 'Avatar';
    const matchSource = sourceFilter.value === 'All' || src === sourceFilter.value;
    return matchSearch && matchSource;
  });
});

watch([search, sourceFilter], () => { curPage.value = 1; });

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / PER_PAGE)));
const pageRows   = computed(() => filtered.value.slice((curPage.value - 1) * PER_PAGE, curPage.value * PER_PAGE));
const pageNumbers = computed(() => {
  const total = totalPages.value, cur = curPage.value;
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
  const pages = new Set([1, total, cur, cur - 1, cur + 1].filter(p => p >= 1 && p <= total));
  const sorted = [...pages].sort((a, b) => a - b);
  const result = [];
  for (let i = 0; i < sorted.length; i++) {
    if (i > 0 && sorted[i] - sorted[i - 1] > 1) result.push('…');
    result.push(sorted[i]);
  }
  return result;
});

function formatDate(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString('en-AU', { day: 'numeric', month: 'short', year: 'numeric' });
}

function confirmDelete(event, name) {
  if (confirm(`Delete lead "${name}"? This cannot be undone.`)) {
    event.target.submit();
  }
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.page-title  { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
.page-sub    { color: #6b7280; font-size: 0.875rem; }

.btn-ghost { display: inline-block; padding: 0.5rem 0.875rem; border: 1px solid rgba(239,68,68,0.2); border-radius: 0.5rem; color: #9ca3af; font-size: 0.82rem; text-decoration: none; transition: all 0.18s; }
.btn-ghost:hover { border-color: rgba(239,68,68,0.4); color: #fca5a5; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }

.toolbar { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.search-wrap { position: relative; flex: 1; min-width: 220px; }
.search-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem; pointer-events: none; }
.search-input { width: 100%; padding: 0.625rem 0.875rem 0.625rem 2.25rem; background: rgba(24,10,10,0.8); border: 1px solid rgba(239,68,68,0.15); border-radius: 0.625rem; color: #e5e7eb; font-size: 1rem; font-family: inherit; transition: border-color 0.18s; }
.search-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.search-input::placeholder { color: #4b5563; }

.filter-tabs { display: flex; gap: 0.375rem; flex-wrap: wrap; }
.tab { padding: 0.45rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; background: none; border: 1px solid rgba(239,68,68,0.12); color: #6b7280; font-family: inherit; transition: all 0.18s; }
.tab:hover { border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.tab.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; }

.table-wrap { background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1); border-radius: 1rem; overflow: hidden; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 680px; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: rgba(239,68,68,0.1); }
.data-table tbody tr:hover .name-cell { color: #fca5a5; }
.data-table tbody tr:hover .muted { color: #9ca3af; }

.name-cell  { font-weight: 600; color: #e5e7eb; white-space: nowrap; }
.email-link { color: #9ca3af; text-decoration: none; font-size: 0.85rem; }
.email-link:hover { color: #fca5a5; }
.muted { color: #6b7280; font-size: 0.85rem; }
.use-cell  { max-width: 200px; }
.date-cell { white-space: nowrap; font-size: 0.82rem; }
.empty-row { text-align: center; padding: 2.5rem; color: #4b5563; }

.role-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; white-space: nowrap; }
.source-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 700; white-space: nowrap; }
.source-avatar  { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.25); color: #93c5fd; }
.source-partner { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); color: #fcd34d; }
.source-contact { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7; }

.actions { text-align: right; }
.btn-danger { display: inline-block; padding: 0.3rem 0.6rem; border-radius: 0.4rem; font-size: 0.75rem; font-weight: 600; cursor: pointer; border: none; font-family: inherit; background: rgba(239,68,68,0.1); color: #fca5a5; transition: all 0.18s; }
.btn-danger:hover { background: rgba(239,68,68,0.25); }

.pagination { display: flex; align-items: center; justify-content: center; gap: 0.375rem; margin-top: 1.25rem; flex-wrap: wrap; }
.page-btn { min-width: 36px; height: 36px; border-radius: 0.4rem; border: 1px solid rgba(239,68,68,0.15); background: none; color: #9ca3af; font-size: 0.82rem; font-family: inherit; cursor: pointer; transition: all 0.18s; padding: 0 0.5rem; }
.page-btn:hover:not(:disabled) { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.page-btn.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; font-weight: 700; }
.page-btn:disabled { opacity: 0.35; cursor: default; }
.page-ellipsis { color: #4b5563; font-size: 0.82rem; padding: 0 0.25rem; }
</style>
