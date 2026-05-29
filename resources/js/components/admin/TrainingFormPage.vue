<template>
  <AdminShell :user="user" :csrf-token="csrfToken" page="trainings">

    <div class="page-header">
      <div>
        <a href="/admin/trainings" class="back-link">← Training Catalog</a>
        <h1 class="page-title">{{ training ? 'Edit Training' : 'New Training' }}</h1>
      </div>
    </div>

    <div v-if="errors.length" class="flash error">
      <div v-for="e in errors" :key="e">{{ e }}</div>
    </div>

    <div class="form-card">
      <form method="POST" :action="formAction">
        <input type="hidden" name="_token" :value="csrfToken">
        <input v-if="training" type="hidden" name="_method" value="PUT">

        <!-- Basic info -->
        <div class="form-grid">
          <div class="form-group full">
            <label>Title <span class="req">*</span></label>
            <input type="text" name="title" :value="training?.title" required placeholder="e.g. Introduction to AI">
          </div>

          <div class="form-group">
            <label>Slug <span class="req">*</span></label>
            <input type="text" name="slug" :value="training?.slug" required placeholder="e.g. intro-to-ai">
            <p class="hint">Used in URL and checkout reference.</p>
          </div>

          <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" :value="training?.category" placeholder="e.g. Fundamentals, Technical">
          </div>

          <div class="form-group full">
            <label>Description <span class="req">*</span></label>
            <textarea name="description" rows="3" required placeholder="Short description shown on the training page.">{{ training?.description }}</textarea>
          </div>

          <div class="form-group">
            <label>Format</label>
            <select name="format">
              <option value="">— Select —</option>
              <option value="Workshop"   :selected="training?.format === 'Workshop'">Workshop</option>
              <option value="Online"     :selected="training?.format === 'Online'">Online</option>
              <option value="Self-paced" :selected="training?.format === 'Self-paced'">Self-paced</option>
              <option value="Webinar"    :selected="training?.format === 'Webinar'">Webinar</option>
            </select>
          </div>

          <div class="form-group">
            <label>Duration</label>
            <input type="text" name="duration" :value="training?.duration" placeholder="e.g. Full Day (8 hrs)">
          </div>

          <div class="form-group">
            <label>Level</label>
            <select name="level">
              <option value="">— Select —</option>
              <option value="All levels"             :selected="training?.level === 'All levels'">All levels</option>
              <option value="Beginner"               :selected="training?.level === 'Beginner'">Beginner</option>
              <option value="Beginner–Intermediate"  :selected="training?.level === 'Beginner–Intermediate'">Beginner–Intermediate</option>
              <option value="Intermediate"           :selected="training?.level === 'Intermediate'">Intermediate</option>
              <option value="Intermediate–Advanced"  :selected="training?.level === 'Intermediate–Advanced'">Intermediate–Advanced</option>
              <option value="Advanced"               :selected="training?.level === 'Advanced'">Advanced</option>
            </select>
          </div>

          <div class="form-group">
            <label>Modules / Sections</label>
            <input type="number" name="modules_count" :value="training?.modules_count" min="0" placeholder="e.g. 8">
          </div>

          <div class="form-group">
            <label>Price</label>
            <input type="text" name="price" :value="training?.price" placeholder="e.g. $1,500">
          </div>

          <div class="form-group">
            <label>Price Unit</label>
            <input type="text" name="price_unit" :value="training?.price_unit" placeholder="e.g. per person">
          </div>

          <div class="form-group">
            <label>Checkout Name</label>
            <input type="text" name="checkout_name" :value="training?.checkout_name" placeholder="e.g. Introduction+to+AI+Course">
          </div>

          <div class="form-group">
            <label>Checkout Amount ($)</label>
            <input type="number" name="checkout_amount" :value="training?.checkout_amount" min="0" placeholder="e.g. 1500">
          </div>

          <div class="form-group">
            <label>Stripe Payment Link</label>
            <input type="url" name="stripe_payment_link" :value="training?.stripe_payment_link" placeholder="https://buy.stripe.com/…">
            <p class="hint">Direct Stripe payment link. When set, overrides the generic checkout for Enrol Now button.</p>
          </div>

          <div class="form-group">
            <label>Badge</label>
            <select name="badge">
              <option value="">None</option>
              <option value="Popular"       :selected="training?.badge === 'Popular'">Popular</option>
              <option value="New"           :selected="training?.badge === 'New'">New</option>
              <option value="Certification" :selected="training?.badge === 'Certification'">Certification</option>
            </select>
          </div>

          <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" :value="training?.sort_order ?? 0" min="0">
          </div>

          <div class="form-group checkboxes">
            <label class="checkbox-label">
              <input type="hidden" name="is_featured" value="0">
              <input type="checkbox" name="is_featured" value="1" :checked="training?.is_featured">
              Featured (highlighted on training page)
            </label>
            <label class="checkbox-label">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" name="is_active" value="1" :checked="training?.is_active ?? true">
              Active (visible on training page)
            </label>
          </div>
        </div>

        <div class="section-divider"><span>Instructor</span></div>

        <div class="form-grid">
          <div class="form-group">
            <label>Instructor Name</label>
            <input type="text" name="instructor" :value="training?.instructor" placeholder="e.g. ApiSpi Team">
          </div>
          <div class="form-group">
            <label>Instructor Role</label>
            <input type="text" name="instructor_role" :value="training?.instructor_role" placeholder="e.g. AI Productivity & Tools">
          </div>
        </div>

        <div class="section-divider"><span>Content</span></div>

        <div class="form-grid">
          <div class="form-group full">
            <label>Topics / Curriculum (one per line)</label>
            <textarea name="topics" rows="8" placeholder="ChatGPT — writing, research & idea generation&#10;Claude — analysis, summarisation & long-form work&#10;...">{{ training?.topics ? training.topics.join('\n') : '' }}</textarea>
          </div>

          <div class="form-group full">
            <label>What's Included (one per line)</label>
            <textarea name="includes_text" rows="5" placeholder="Full day live hands-on workshop&#10;Lifetime video replay access&#10;Course materials & templates&#10;Certificate of completion">{{ training?.includes ? training.includes.join('\n') : '' }}</textarea>
          </div>

          <div class="form-group">
            <label>CTA Headline</label>
            <input type="text" name="cta_headline" :value="training?.cta_headline" placeholder="e.g. Ready to Level Up Your AI Skills?">
          </div>

          <div class="form-group">
            <label>CTA Subtext</label>
            <input type="text" name="cta_sub" :value="training?.cta_sub" placeholder="e.g. Enrol today and start learning">
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-submit">{{ training ? 'Save Changes' : 'Create Training' }}</button>
          <a href="/admin/trainings" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>

  </AdminShell>
</template>

<script setup>
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:      { type: Object, default: () => ({}) },
  csrfToken: { type: String, default: '' },
  training:  { type: Object, default: null },
  errors:    { type: Array,  default: () => [] },
});

const formAction = props.training ? `/admin/trainings/${props.training.id}` : '/admin/trainings';
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

.form-actions { display: flex; gap: 0.75rem; align-items: center; margin-top: 1.5rem; }
.btn-submit {
  padding: 0.65rem 1.5rem; border-radius: 0.625rem;
  background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35);
  color: #fca5a5; font-size: 0.9rem; font-weight: 600; cursor: pointer;
  font-family: inherit; transition: all 0.18s; min-height: 44px;
}
.btn-submit:hover { background: rgba(239,68,68,0.25); }
.btn-cancel {
  padding: 0.65rem 1.25rem; border-radius: 0.625rem; border: 1px solid rgba(239,68,68,0.12);
  color: #6b7280; font-size: 0.875rem; text-decoration: none; transition: all 0.18s;
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
