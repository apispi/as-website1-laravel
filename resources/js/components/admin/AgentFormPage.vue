<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="agents">

    <div class="page-header">
      <div>
        <a href="/admin/agents" class="back-link">← Agents</a>
        <h1 class="page-title">{{ agent ? 'Edit Agent' : 'New Agent' }}</h1>
      </div>
    </div>

    <div v-if="errors.length" class="flash error">
      <div v-for="e in errors" :key="e">{{ e }}</div>
    </div>

    <div class="form-card">
      <form method="POST" :action="formAction">
        <input type="hidden" name="_token" :value="csrfToken">
        <input v-if="agent" type="hidden" name="_method" value="PUT">

        <div class="form-grid">
          <div class="form-group full">
            <label>Name <span class="req">*</span></label>
            <input type="text" name="name" :value="agent?.name" required placeholder="e.g. Bid & Tender Response">
          </div>

          <div class="form-group">
            <label>Slug <span class="req">*</span></label>
            <input type="text" name="slug" :value="agent?.slug" required placeholder="e.g. bid-tender">
            <p class="hint">Used in the URL: /agents/slug</p>
          </div>

          <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" :value="agent?.category" placeholder="e.g. Security & Compliance">
          </div>

          <div class="form-group full">
            <label>Description <span class="req">*</span></label>
            <textarea name="description" rows="3" required placeholder="Short description shown on the marketplace card.">{{ agent?.description }}</textarea>
          </div>

          <div class="form-group">
            <label>Badge</label>
            <select name="badge">
              <option value="">None</option>
              <option value="Popular"  :selected="agent?.badge === 'Popular'">Popular</option>
              <option value="Premium"  :selected="agent?.badge === 'Premium'">Premium</option>
              <option value="New"      :selected="agent?.badge === 'New'">New</option>
            </select>
          </div>

          <div class="form-group">
            <label>Rating</label>
            <input type="number" name="rating" :value="agent?.rating" step="0.01" min="0" max="5" placeholder="e.g. 4.90">
          </div>

          <div class="form-group">
            <label>Users Count</label>
            <input type="text" name="users_count" :value="agent?.users_count" placeholder="e.g. 340+">
          </div>

          <div class="form-group">
            <label>Price</label>
            <input type="text" name="price" :value="agent?.price" placeholder="e.g. $499/mo">
          </div>

          <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" :value="agent?.sort_order ?? 0" min="0">
          </div>

          <div class="form-group checkboxes">
            <label class="checkbox-label">
              <input type="hidden" name="is_featured" value="0">
              <input type="checkbox" name="is_featured" value="1" :checked="agent?.is_featured">
              Featured (highlighted card on marketplace)
            </label>
            <label class="checkbox-label">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" name="is_active" value="1" :checked="agent?.is_active ?? true">
              Active (visible on marketplace)
            </label>
          </div>
        </div>

        <div class="section-divider">
          <span>Rich Content</span>
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label>Checkout Name</label>
            <input type="text" name="checkout_name" :value="agent?.checkout_name" placeholder="e.g. Bid+Tender+Response">
            <p class="hint">Used in checkout URL query string.</p>
          </div>

          <div class="form-group">
            <label>Target Audience</label>
            <input type="text" name="target_audience" :value="agent?.target_audience" placeholder="e.g. ICT consultancies, Defence contractors">
          </div>

          <div class="form-group">
            <label>Tagline</label>
            <input type="text" name="tagline" :value="agent?.tagline" placeholder="Short tagline (optional)">
          </div>

          <div class="form-group">
            <label>CTA Headline</label>
            <input type="text" name="cta_headline" :value="agent?.cta_headline" placeholder="e.g. Win More Tenders with AI">
          </div>

          <div class="form-group full">
            <label>CTA Subtext</label>
            <input type="text" name="cta_sub" :value="agent?.cta_sub" placeholder="e.g. Start automating your government bid responses today">
          </div>

          <div class="form-group full">
            <label>Key Capabilities (one per line)</label>
            <textarea name="features" rows="6" placeholder="Reads and parses RFQs&#10;Matches CVs to selection criteria&#10;...">{{ agent?.features ? agent.features.join('\n') : '' }}</textarea>
          </div>

          <div class="form-group full">
            <label>What's Included (one per line)</label>
            <textarea name="includes" rows="6" placeholder="RFQ/RFT document analysis&#10;CV-to-criteria matching&#10;...">{{ agent?.includes ? agent.includes.join('\n') : '' }}</textarea>
          </div>

          <div class="form-group full">
            <label>Use Cases (JSON array)</label>
            <textarea name="use_cases" rows="8" class="mono" placeholder='[{"title":"...","description":"..."}]'>{{ agent?.use_cases ? JSON.stringify(agent.use_cases, null, 2) : '' }}</textarea>
          </div>

          <div class="form-group full">
            <label>Pricing Plans (JSON array)</label>
            <textarea name="pricing_plans" rows="12" class="mono" placeholder='[{"name":"Starter","description":"...","price":"$299/month","amount":299,"features":["..."],"is_recommended":false,"checkout_name":"..."}]'>{{ agent?.pricing_plans ? JSON.stringify(agent.pricing_plans, null, 2) : '' }}</textarea>
          </div>

          <div class="form-group full">
            <label>FAQs (JSON array)</label>
            <textarea name="faqs" rows="8" class="mono" placeholder='[{"question":"...","answer":"..."}]'>{{ agent?.faqs ? JSON.stringify(agent.faqs, null, 2) : '' }}</textarea>
          </div>
        </div>

        <!-- Hidden inputs — always in DOM so all selections submit regardless of page -->
        <template v-for="id in selectedSkillIds" :key="'sk' + id">
          <input type="hidden" name="skill_ids[]" :value="id">
        </template>
        <template v-for="id in selectedConnectorIds" :key="'cn' + id">
          <input type="hidden" name="connector_ids[]" :value="id">
        </template>

        <!-- Association tabs -->
        <div class="assoc-tabs-wrap">
          <div class="assoc-tab-bar">
            <button type="button"
                    :class="['assoc-tab', { active: assocTab === 'skills' }]"
                    @click="assocTab = 'skills'">
              Skills
              <span class="assoc-tab-count">{{ selectedSkillIds.length }}</span>
            </button>
            <button type="button"
                    :class="['assoc-tab', { active: assocTab === 'connectors' }]"
                    @click="assocTab = 'connectors'">
              Connectors
              <span class="assoc-tab-count">{{ selectedConnectorIds.length }}</span>
            </button>
          </div>

          <!-- Skills panel -->
          <div v-show="assocTab === 'skills'" class="assoc-panel">
            <div v-if="allSkills.length === 0" class="assoc-empty">
              No skills defined yet. <a href="/admin/skills/create" class="assoc-link">Create one →</a>
            </div>
            <template v-else>
              <div class="assoc-toolbar">
                <div class="assoc-search-wrap">
                  <span class="assoc-search-icon">◎</span>
                  <input v-model="skillSearch" type="text" placeholder="Search skills…" class="assoc-search-input">
                </div>
                <div class="assoc-filter-tabs">
                  <button type="button" :class="['assoc-ftab', { active: skillView === 'all' }]"     @click="skillView = 'all'">All <span class="assoc-ftab-n">{{ allSkills.length }}</span></button>
                  <button type="button" :class="['assoc-ftab', { active: skillView === 'selected' }]" @click="skillView = 'selected'">Selected <span class="assoc-ftab-n">{{ selectedSkillIds.length }}</span></button>
                </div>
              </div>
              <div class="assoc-table-wrap">
                <table class="assoc-table">
                  <thead>
                    <tr>
                      <th class="col-check">
                        <input type="checkbox" class="row-check"
                               :checked="skillPageAllSelected"
                               :indeterminate="skillPageSomeSelected && !skillPageAllSelected"
                               @change="toggleSkillPage">
                      </th>
                      <th>Skill</th>
                      <th>Category</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="skill in skillPageRows" :key="skill.id"
                        :class="{ selected: selectedSkillIds.includes(skill.id) }"
                        @click="toggleSkill(skill.id)">
                      <td class="col-check" @click.stop>
                        <input type="checkbox" class="row-check"
                               :checked="selectedSkillIds.includes(skill.id)"
                               @change="toggleSkill(skill.id)">
                      </td>
                      <td class="item-name">{{ skill.name }}</td>
                      <td class="item-cat">{{ skill.category || '—' }}</td>
                    </tr>
                    <tr v-if="skillFiltered.length === 0">
                      <td colspan="3" class="assoc-empty-row">No skills found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-if="skillTotalPages > 1" class="assoc-pagination">
                <button type="button" class="pg-btn" :disabled="skillPage === 1" @click="skillPage--">‹</button>
                <template v-for="p in skillPageNumbers" :key="p">
                  <span v-if="p === '…'" class="pg-ellipsis">…</span>
                  <button v-else type="button" :class="['pg-btn', { active: p === skillPage }]" @click="skillPage = p">{{ p }}</button>
                </template>
                <button type="button" class="pg-btn" :disabled="skillPage === skillTotalPages" @click="skillPage++">›</button>
              </div>
            </template>
          </div>

          <!-- Connectors panel -->
          <div v-show="assocTab === 'connectors'" class="assoc-panel">
            <div v-if="allConnectors.length === 0" class="assoc-empty">
              No connectors defined yet. <a href="/admin/connectors/create" class="assoc-link">Create one →</a>
            </div>
            <template v-else>
              <div class="assoc-toolbar">
                <div class="assoc-search-wrap">
                  <span class="assoc-search-icon">◎</span>
                  <input v-model="cnSearch" type="text" placeholder="Search connectors…" class="assoc-search-input">
                </div>
                <div class="assoc-filter-tabs">
                  <button type="button" :class="['assoc-ftab', { active: cnView === 'all' }]"      @click="cnView = 'all'">All <span class="assoc-ftab-n">{{ allConnectors.length }}</span></button>
                  <button type="button" :class="['assoc-ftab', { active: cnView === 'selected' }]" @click="cnView = 'selected'">Selected <span class="assoc-ftab-n">{{ selectedConnectorIds.length }}</span></button>
                </div>
              </div>
              <div class="assoc-table-wrap">
                <table class="assoc-table">
                  <thead>
                    <tr>
                      <th class="col-check">
                        <input type="checkbox" class="row-check"
                               :checked="cnPageAllSelected"
                               :indeterminate="cnPageSomeSelected && !cnPageAllSelected"
                               @change="toggleCnPage">
                      </th>
                      <th>Connector</th>
                      <th>Category</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="cn in cnPageRows" :key="cn.id"
                        :class="{ selected: selectedConnectorIds.includes(cn.id) }"
                        @click="toggleConnector(cn.id)">
                      <td class="col-check" @click.stop>
                        <input type="checkbox" class="row-check"
                               :checked="selectedConnectorIds.includes(cn.id)"
                               @change="toggleConnector(cn.id)">
                      </td>
                      <td class="item-name">
                        <span v-if="cn.icon" class="cn-icon">{{ cn.icon }}</span>{{ cn.name }}
                      </td>
                      <td class="item-cat">{{ cn.category || '—' }}</td>
                    </tr>
                    <tr v-if="cnFiltered.length === 0">
                      <td colspan="3" class="assoc-empty-row">No connectors found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-if="cnTotalPages > 1" class="assoc-pagination">
                <button type="button" class="pg-btn" :disabled="cnPage === 1" @click="cnPage--">‹</button>
                <template v-for="p in cnPageNumbers" :key="p">
                  <span v-if="p === '…'" class="pg-ellipsis">…</span>
                  <button v-else type="button" :class="['pg-btn', { active: p === cnPage }]" @click="cnPage = p">{{ p }}</button>
                </template>
                <button type="button" class="pg-btn" :disabled="cnPage === cnTotalPages" @click="cnPage++">›</button>
              </div>
            </template>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-submit">{{ agent ? 'Save Changes' : 'Create Agent' }}</button>
          <a href="/admin/agents" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>

  </AdminShell>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:               { type: Object, default: () => ({}) },
  csrfToken:          { type: String, default: '' },
  agent:              { type: Object, default: null },
  errors:             { type: Array,  default: () => [] },
  allSkills:          { type: Array,  default: () => [] },
  agentSkillIds:      { type: Array,  default: () => [] },
  allConnectors:      { type: Array,  default: () => [] },
  agentConnectorIds:  { type: Array,  default: () => [] },
});

const formAction           = props.agent ? `/admin/agents/${props.agent.id}` : '/admin/agents';
const selectedSkillIds     = ref([...props.agentSkillIds]);
const selectedConnectorIds = ref([...props.agentConnectorIds]);
const assocTab             = ref('skills');

const PER_PAGE = 15;

// ── Skills ──
const skillSearch = ref('');
const skillView   = ref('all');
const skillPage   = ref(1);

const skillFiltered = computed(() => {
  const q = skillSearch.value.toLowerCase();
  return props.allSkills.filter(s => {
    const matchSearch = !q || s.name.toLowerCase().includes(q) || (s.category || '').toLowerCase().includes(q);
    const matchView   = skillView.value === 'all' || selectedSkillIds.value.includes(s.id);
    return matchSearch && matchView;
  });
});

watch([skillSearch, skillView], () => { skillPage.value = 1; });

const skillTotalPages = computed(() => Math.max(1, Math.ceil(skillFiltered.value.length / PER_PAGE)));
const skillPageRows   = computed(() => {
  const start = (skillPage.value - 1) * PER_PAGE;
  return skillFiltered.value.slice(start, start + PER_PAGE);
});
const skillPageNumbers = computed(() => pageNumbers(skillPage.value, skillTotalPages.value));
const skillPageAllSelected  = computed(() => skillPageRows.value.length > 0 && skillPageRows.value.every(s => selectedSkillIds.value.includes(s.id)));
const skillPageSomeSelected = computed(() => skillPageRows.value.some(s => selectedSkillIds.value.includes(s.id)));

function toggleSkill(id) {
  const idx = selectedSkillIds.value.indexOf(id);
  if (idx === -1) selectedSkillIds.value.push(id);
  else selectedSkillIds.value.splice(idx, 1);
}

function toggleSkillPage() {
  if (skillPageAllSelected.value) {
    skillPageRows.value.forEach(s => {
      const idx = selectedSkillIds.value.indexOf(s.id);
      if (idx !== -1) selectedSkillIds.value.splice(idx, 1);
    });
  } else {
    skillPageRows.value.forEach(s => {
      if (!selectedSkillIds.value.includes(s.id)) selectedSkillIds.value.push(s.id);
    });
  }
}

// ── Connectors ──
const cnSearch = ref('');
const cnView   = ref('all');
const cnPage   = ref(1);

const cnFiltered = computed(() => {
  const q = cnSearch.value.toLowerCase();
  return props.allConnectors.filter(c => {
    const matchSearch = !q || c.name.toLowerCase().includes(q) || (c.category || '').toLowerCase().includes(q);
    const matchView   = cnView.value === 'all' || selectedConnectorIds.value.includes(c.id);
    return matchSearch && matchView;
  });
});

watch([cnSearch, cnView], () => { cnPage.value = 1; });

const cnTotalPages = computed(() => Math.max(1, Math.ceil(cnFiltered.value.length / PER_PAGE)));
const cnPageRows   = computed(() => {
  const start = (cnPage.value - 1) * PER_PAGE;
  return cnFiltered.value.slice(start, start + PER_PAGE);
});
const cnPageNumbers = computed(() => pageNumbers(cnPage.value, cnTotalPages.value));
const cnPageAllSelected  = computed(() => cnPageRows.value.length > 0 && cnPageRows.value.every(c => selectedConnectorIds.value.includes(c.id)));
const cnPageSomeSelected = computed(() => cnPageRows.value.some(c => selectedConnectorIds.value.includes(c.id)));

function toggleConnector(id) {
  const idx = selectedConnectorIds.value.indexOf(id);
  if (idx === -1) selectedConnectorIds.value.push(id);
  else selectedConnectorIds.value.splice(idx, 1);
}

function toggleCnPage() {
  if (cnPageAllSelected.value) {
    cnPageRows.value.forEach(c => {
      const idx = selectedConnectorIds.value.indexOf(c.id);
      if (idx !== -1) selectedConnectorIds.value.splice(idx, 1);
    });
  } else {
    cnPageRows.value.forEach(c => {
      if (!selectedConnectorIds.value.includes(c.id)) selectedConnectorIds.value.push(c.id);
    });
  }
}

// ── Shared pagination helper ──
function pageNumbers(cur, total) {
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
  const pages = new Set([1, total, cur, cur - 1, cur + 1].filter(p => p >= 1 && p <= total));
  const sorted = [...pages].sort((a, b) => a - b);
  const result = [];
  for (let i = 0; i < sorted.length; i++) {
    if (i > 0 && sorted[i] - sorted[i - 1] > 1) result.push('…');
    result.push(sorted[i]);
  }
  return result;
}
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
  border-radius: 1rem; padding: 1.75rem; max-width: 780px;
}

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
.form-group { display: flex; flex-direction: column; gap: 0.35rem; }
.form-group.full { grid-column: 1 / -1; }
.form-group.checkboxes { grid-column: 1 / -1; display: flex; flex-direction: column; gap: 0.6rem; }

label { font-size: 0.82rem; font-weight: 600; color: #d1d5db; }
.req { color: #ef4444; }
.hint { font-size: 0.75rem; color: #4b5563; margin-top: 0.1rem; }

input[type="text"],
input[type="number"],
textarea,
select {
  padding: 0.65rem 0.875rem;
  background: rgba(12,4,4,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.5rem; color: #e5e7eb; font-size: 1rem; font-family: inherit;
  transition: border-color 0.18s; width: 100%;
}
input:focus, textarea:focus, select:focus { outline: none; border-color: rgba(239,68,68,0.45); }
input::placeholder, textarea::placeholder { color: #4b5563; }
textarea { resize: vertical; min-height: 80px; }
select option { background: #140606; }

.checkbox-label {
  display: flex; align-items: center; gap: 0.5rem;
  font-size: 0.875rem; color: #d1d5db; cursor: pointer; font-weight: 400;
}
.checkbox-label input[type="checkbox"] { width: 16px; height: 16px; accent-color: #ef4444; flex-shrink: 0; }
.checkbox-label input[type="hidden"] { display: none; }

.section-divider {
  display: flex; align-items: center; gap: 0.75rem;
  margin: 1.75rem 0 1.25rem;
  color: #6b7280; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em;
}
.section-divider::before,
.section-divider::after { content: ''; flex: 1; height: 1px; background: rgba(239,68,68,0.12); }

/* Association tabs */
.assoc-tabs-wrap { margin-top: 1.75rem; border: 1px solid rgba(239,68,68,0.1); border-radius: 0.875rem; overflow: hidden; margin-bottom: 1.5rem; }
.assoc-tab-bar { display: flex; background: rgba(12,4,4,0.6); border-bottom: 1px solid rgba(239,68,68,0.1); }
.assoc-tab { flex: 1; padding: 0.75rem 1rem; background: none; border: none; border-bottom: 2px solid transparent; margin-bottom: -1px; cursor: pointer; font-family: inherit; font-size: 0.82rem; font-weight: 600; color: #6b7280; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.18s; }
.assoc-tab:hover { color: #fca5a5; }
.assoc-tab.active { color: #fca5a5; border-bottom-color: #ef4444; background: rgba(239,68,68,0.04); }
.assoc-tab-count { display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 18px; padding: 0 5px; border-radius: 99px; font-size: 0.68rem; font-weight: 700; background: rgba(239,68,68,0.1); color: #ef4444; }
.assoc-tab.active .assoc-tab-count { background: rgba(239,68,68,0.2); }

.assoc-panel { padding: 1.25rem; }
.assoc-empty { font-size: 0.82rem; color: #4b5563; }
.assoc-link  { color: #fca5a5; text-decoration: none; }
.assoc-link:hover { text-decoration: underline; }

.assoc-toolbar { display: flex; align-items: center; gap: 0.875rem; margin-bottom: 0.875rem; flex-wrap: wrap; }
.assoc-search-wrap { position: relative; flex: 1; min-width: 160px; }
.assoc-search-icon { position: absolute; left: 0.625rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.8rem; pointer-events: none; }
.assoc-search-input {
  width: 100%; padding: 0.5rem 0.75rem 0.5rem 2rem;
  background: rgba(12,4,4,0.8); border: 1px solid rgba(239,68,68,0.15);
  border-radius: 0.5rem; color: #e5e7eb; font-size: 0.875rem; font-family: inherit;
  transition: border-color 0.18s;
}
.assoc-search-input:focus { outline: none; border-color: rgba(239,68,68,0.4); }
.assoc-search-input::placeholder { color: #4b5563; }
.assoc-filter-tabs { display: flex; gap: 0.3rem; flex-shrink: 0; }
.assoc-ftab {
  padding: 0.4rem 0.7rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 600;
  cursor: pointer; background: none; border: 1px solid rgba(239,68,68,0.12);
  color: #6b7280; font-family: inherit; transition: all 0.18s;
  display: flex; align-items: center; gap: 0.35rem;
}
.assoc-ftab:hover { border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.assoc-ftab.active { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #fca5a5; }
.assoc-ftab-n { font-size: 0.68rem; font-weight: 700; background: rgba(239,68,68,0.12); color: #ef4444; border-radius: 99px; padding: 0.05rem 0.35rem; }

.assoc-table-wrap { border: 1px solid rgba(239,68,68,0.1); border-radius: 0.625rem; overflow: hidden; overflow-x: auto; }
.assoc-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; min-width: 360px; }
.assoc-table th { padding: 0.6rem 0.875rem; text-align: left; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; border-bottom: 1px solid rgba(239,68,68,0.08); background: rgba(239,68,68,0.03); }
.assoc-table td { padding: 0.65rem 0.875rem; border-bottom: 1px solid rgba(239,68,68,0.06); vertical-align: middle; color: #d1d5db; }
.assoc-table tbody tr:last-child td { border-bottom: none; }
.assoc-table tbody tr { cursor: pointer; transition: background 0.12s; }
.assoc-table tbody tr:hover td { background: rgba(239,68,68,0.04); }
.assoc-table tbody tr.selected td { background: rgba(239,68,68,0.06); }
.col-check { width: 40px; padding-left: 0.875rem; }
.row-check { width: 14px; height: 14px; accent-color: #ef4444; cursor: pointer; display: block; }
.item-name { font-weight: 500; color: #e5e7eb; }
.item-cat  { color: #6b7280; font-size: 0.78rem; }
.cn-icon   { margin-right: 0.35rem; font-size: 0.9rem; }
.assoc-empty-row { text-align: center; padding: 2rem; color: #4b5563; }

.assoc-pagination { display: flex; align-items: center; justify-content: center; gap: 0.3rem; margin-top: 0.875rem; flex-wrap: wrap; }
.pg-btn {
  min-width: 32px; height: 32px; border-radius: 0.375rem;
  border: 1px solid rgba(239,68,68,0.15); background: none;
  color: #9ca3af; font-size: 0.8rem; font-family: inherit;
  cursor: pointer; transition: all 0.18s; padding: 0 0.4rem;
}
.pg-btn:hover:not(:disabled) { border-color: rgba(239,68,68,0.35); color: #fca5a5; }
.pg-btn.active { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); color: #fca5a5; font-weight: 700; }
.pg-btn:disabled { opacity: 0.35; cursor: default; }
.pg-ellipsis { color: #4b5563; font-size: 0.8rem; padding: 0 0.2rem; }

textarea.mono { font-family: 'Menlo', 'Monaco', 'Consolas', monospace; font-size: 0.8rem; }

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
