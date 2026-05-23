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

        <!-- Association tabs -->
        <div class="assoc-tabs-wrap">
          <div class="assoc-tab-bar">
            <button type="button"
                    :class="['assoc-tab', { active: assocTab === 'skills' }]"
                    @click="assocTab = 'skills'">
              Skills
              <span class="assoc-tab-count">{{ selectedSkillCount }}</span>
            </button>
            <button type="button"
                    :class="['assoc-tab', { active: assocTab === 'connectors' }]"
                    @click="assocTab = 'connectors'">
              Connectors
              <span class="assoc-tab-count">{{ selectedConnectorCount }}</span>
            </button>
          </div>

          <!-- Skills panel -->
          <div v-show="assocTab === 'skills'" class="assoc-panel">
            <div v-if="Object.keys(allSkills).length === 0" class="assoc-empty">
              No skills defined yet. <a href="/admin/skills/create" class="assoc-link">Create one →</a>
            </div>
            <div v-else class="skills-grid">
              <div v-for="(categorySkills, category) in allSkills" :key="category" class="skill-category">
                <div class="skill-category-label">{{ category }}</div>
                <div class="skill-checkboxes">
                  <label v-for="skill in categorySkills" :key="skill.id" class="skill-checkbox-label">
                    <input type="checkbox" name="skill_ids[]" :value="skill.id"
                           v-model="selectedSkillIds" :true-value="skill.id">
                    {{ skill.name }}
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Connectors panel -->
          <div v-show="assocTab === 'connectors'" class="assoc-panel">
            <p class="assoc-desc">Select the connectors this agent requires users to have connected.</p>
            <div v-if="Object.keys(allConnectors).length === 0" class="assoc-empty">
              No connectors defined yet. <a href="/admin/connectors/create" class="assoc-link">Create one →</a>
            </div>
            <div v-else class="skills-grid">
              <div v-for="(categoryConnectors, category) in allConnectors" :key="category" class="skill-category">
                <div class="skill-category-label">{{ category || 'Other' }}</div>
                <div class="skill-checkboxes">
                  <label v-for="connector in categoryConnectors" :key="connector.id" class="skill-checkbox-label">
                    <input type="checkbox" name="connector_ids[]" :value="connector.id"
                           v-model="selectedConnectorIds" :true-value="connector.id">
                    <span v-if="connector.icon" class="connector-icon-sm">{{ connector.icon }}</span>
                    {{ connector.name }}
                  </label>
                </div>
              </div>
            </div>
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
import { ref, computed } from 'vue';
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:               { type: Object, default: () => ({}) },
  csrfToken:          { type: String, default: '' },
  agent:              { type: Object, default: null },
  errors:             { type: Array,  default: () => [] },
  allSkills:          { type: Object, default: () => ({}) },
  agentSkillIds:      { type: Array,  default: () => [] },
  allConnectors:      { type: Object, default: () => ({}) },
  agentConnectorIds:  { type: Array,  default: () => [] },
});

const formAction           = props.agent ? `/admin/agents/${props.agent.id}` : '/admin/agents';
const selectedSkillIds     = ref([...props.agentSkillIds]);
const selectedConnectorIds = ref([...props.agentConnectorIds]);
const assocTab             = ref('skills');

const selectedSkillCount     = computed(() => selectedSkillIds.value.length);
const selectedConnectorCount = computed(() => selectedConnectorIds.value.length);
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
.assoc-desc  { font-size: 0.82rem; color: #6b7280; margin-bottom: 1rem; }
.assoc-empty { font-size: 0.82rem; color: #4b5563; }
.assoc-link  { color: #fca5a5; text-decoration: none; }
.assoc-link:hover { text-decoration: underline; }

.skills-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
.skill-category { background: rgba(12,4,4,0.5); border: 1px solid rgba(239,68,68,0.1); border-radius: 0.625rem; padding: 0.875rem; }
.skill-category-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #ef4444; margin-bottom: 0.625rem; }
.skill-checkboxes { display: flex; flex-direction: column; gap: 0.4rem; }
.skill-checkbox-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: #d1d5db; cursor: pointer; font-weight: 400; }
.skill-checkbox-label input[type="checkbox"] { width: 14px; height: 14px; accent-color: #ef4444; flex-shrink: 0; }

textarea.mono { font-family: 'Menlo', 'Monaco', 'Consolas', monospace; font-size: 0.8rem; }

.connector-icon-sm { font-size: 0.85rem; line-height: 1; }

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
