<template>
  <div class="dt-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <div class="dt-overlay" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside class="dt-sidebar">
      <div class="dt-sidebar-header">
        <a href="/" class="dt-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="dt-logo-icon">
            <defs>
              <linearGradient id="dtlg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dtlg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dtlg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="dt-sidebar-close" @click="sidebarOpen = false" aria-label="Close">✕</button>
      </div>

      <nav class="dt-nav">
        <span class="dt-nav-label">Workspace</span>
        <a href="/dashboard"            class="dt-nav-link"><span class="dt-nav-icon">⬡</span> Home</a>
        <a href="/dashboard/agents"     class="dt-nav-link"><span class="dt-nav-icon">◈</span> My Agents</a>
        <a href="/dashboard/connectors" class="dt-nav-link"><span class="dt-nav-icon">⬡</span> My Connectors</a>
        <a href="/dashboard/catalog"    class="dt-nav-link"><span class="dt-nav-icon">◎</span> Catalog</a>
        <a href="/dashboard/training"   class="dt-nav-link active"><span class="dt-nav-icon">◷</span> Training</a>
        <a href="/dashboard/aria"       class="dt-nav-link"><span class="dt-nav-icon">◇</span> Aria</a>

        <span class="dt-nav-label">Account</span>

        <template v-if="user.is_admin">
          <span class="dt-nav-label">Administration</span>
          <a href="/admin" class="dt-nav-link dt-nav-admin"><span class="dt-nav-icon">⬡</span> Admin Panel</a>
        </template>
      </nav>

      <div class="dt-sidebar-footer">
        <div class="dt-user-row">
          <div class="dt-avatar">{{ initial }}</div>
          <div class="dt-user-text">
            <div class="dt-user-name">{{ user.name }}</div>
            <div class="dt-user-email">{{ user.email }}</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <div class="dt-main">
      <header class="dt-topbar">
        <button class="dt-menu-btn" @click="sidebarOpen = true"><span></span><span></span><span></span></button>
        <a href="/" class="dt-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="dt-logo-icon">
            <defs>
              <linearGradient id="dtlg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dtlg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dtlg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="dt-topbar-right">
          <div class="dt-avatar dt-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <main class="dt-content">

        <div class="dt-page-header">
          <div>
            <a href="/dashboard" class="dt-back">← Dashboard</a>
            <h1 class="dt-page-title">Training Catalog</h1>
            <p class="dt-page-sub">{{ filteredTrainings.length }} course{{ filteredTrainings.length !== 1 ? 's' : '' }} available</p>
          </div>
          <div class="dt-search-wrap">
            <span class="dt-search-icon">⊕</span>
            <input
              v-model="search"
              type="text"
              class="dt-search"
              placeholder="Search courses…"
              autocomplete="off"
            />
            <button v-if="search" class="dt-search-clear" @click="search = ''" aria-label="Clear">✕</button>
          </div>
        </div>

        <!-- Training cards -->
        <div v-if="filteredTrainings.length" class="dt-grid">
          <a
            v-for="t in filteredTrainings"
            :key="t.id"
            :href="enrolUrl(t)"
            class="dt-card"
            :class="{ featured: t.is_featured }"
          >
            <!-- Banner -->
            <div class="dt-card-banner">
              <div class="dt-card-banner-inner">
                <div class="dt-banner-cat">{{ (t.category || 'Course').toUpperCase() }}</div>
                <div class="dt-banner-title">{{ t.title }}</div>
                <div v-if="t.format" class="dt-banner-meta">
                  {{ t.format.toUpperCase() }}{{ t.duration ? ' · ' + t.duration.toUpperCase() : '' }}
                </div>
              </div>
              <span v-if="t.badge" class="dt-badge" :class="badgeClass(t.badge)">{{ t.badge }}</span>
            </div>

            <!-- Body -->
            <div class="dt-card-body">
              <div class="dt-card-meta">
                <span v-if="t.duration"><span class="dt-meta-icon">◷</span> {{ t.duration }}</span>
                <span v-if="t.modules_count"><span class="dt-meta-icon">◎</span> {{ t.modules_count }} {{ t.format === 'Workshop' ? 'topics' : 'modules' }}</span>
                <span v-if="t.level"><span class="dt-meta-icon">◇</span> {{ t.level }}</span>
              </div>
              <h2 class="dt-card-title">{{ t.title }}</h2>
              <p v-if="t.description" class="dt-card-desc">{{ t.description }}</p>
              <div v-if="t.instructor" class="dt-instructor">
                <div class="dt-instructor-avatar">◈</div>
                <span class="dt-instructor-name">{{ t.instructor }}</span>
              </div>
              <div class="dt-card-footer">
                <div v-if="t.price" class="dt-price">
                  {{ t.price }}<span v-if="t.price_unit"> / {{ t.price_unit }}</span>
                </div>
                <span class="dt-enrol-btn">Enrol Now →</span>
              </div>
            </div>
          </a>
        </div>

        <!-- Empty -->
        <div v-else class="dt-empty">
          <div class="dt-empty-icon">◷</div>
          <div class="dt-empty-title">{{ search ? 'No courses match your search' : 'No courses available yet' }}</div>
          <div class="dt-empty-desc">{{ search ? 'Try a different keyword.' : 'Check back soon for new training.' }}</div>
        </div>

      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user:      { type: Object, default: () => ({}) },
  csrfToken: { type: String, default: '' },
  trainings: { type: Array,  default: () => [] },
});

const sidebarOpen = ref(false);
const search = ref('');
const initial = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

const filteredTrainings = computed(() => {
  const q = search.value.trim().toLowerCase();
  if (!q) return props.trainings;
  return props.trainings.filter(t =>
    (t.title       || '').toLowerCase().includes(q) ||
    (t.category    || '').toLowerCase().includes(q) ||
    (t.description || '').toLowerCase().includes(q) ||
    (t.instructor  || '').toLowerCase().includes(q)
  );
});

function enrolUrl(t) {
  if (t.stripe_payment_link) return t.stripe_payment_link;
  if (t.checkout_amount) return `/checkout?agent=${encodeURIComponent(t.checkout_name || t.title)}&amount=${t.checkout_amount}&type=training`;
  return '/contact';
}

function badgeClass(badge) {
  const b = (badge || '').toLowerCase();
  if (b === 'popular')       return 'badge-popular';
  if (b === 'new')           return 'badge-new';
  if (b === 'certification') return 'badge-cert';
  return '';
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.dt-shell { display: flex; min-height: 100vh; background: #0a0805; color: #e5e7eb; font-family: 'Instrument Sans', system-ui, sans-serif; }

.dt-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 90; }
.sidebar-open .dt-overlay { display: block; }

/* Sidebar */
.dt-sidebar { width: 240px; background: rgba(14,11,6,0.98); border-right: 1px solid rgba(217,119,6,0.15); position: fixed; top: 0; left: 0; height: 100vh; display: flex; flex-direction: column; z-index: 100; overflow-y: auto; transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); }
.dt-sidebar-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.dt-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.15rem; font-weight: 700; }
.dt-logo-icon { width: 26px; height: 26px; }
.dt-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.1rem; padding: 0.25rem; }

.dt-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.dt-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.dt-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.dt-nav-link:hover { background: rgba(217,119,6,0.1); color: #FCD34D; }
.dt-nav-link.active { background: rgba(217,119,6,0.15); color: #FCD34D; font-weight: 600; }
.dt-nav-admin { color: #fca5a5 !important; }
.dt-nav-admin:hover { background: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
.dt-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.dt-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.dt-user-row { display: flex; align-items: center; gap: 0.75rem; }
.dt-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0a0805; flex-shrink: 0; }
.dt-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.dt-user-text { min-width: 0; }
.dt-user-name  { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dt-user-email { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Main */
.dt-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

.dt-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(14,11,6,0.98); border-bottom: 1px solid rgba(217,119,6,0.12); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.dt-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.05rem; font-weight: 700; }
.dt-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.dt-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }
.dt-topbar-right { display: flex; align-items: center; }

.dt-content { flex: 1; padding: 2rem; max-width: 1100px; width: 100%; }

.dt-page-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
.dt-back       { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.35rem; }
.dt-back:hover { color: #FCD34D; }
.dt-page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.dt-page-sub   { color: #6b7280; font-size: 0.875rem; }

.dt-search-wrap { position: relative; display: flex; align-items: center; }
.dt-search-icon { position: absolute; left: 0.75rem; font-size: 1rem; color: #4b5563; pointer-events: none; }
.dt-search { background: rgba(28,24,16,0.8); border: 1px solid rgba(217,119,6,0.2); border-radius: 0.625rem; padding: 0.55rem 2.25rem 0.55rem 2.25rem; color: #e5e7eb; font-size: 0.875rem; width: 260px; outline: none; transition: border-color 0.18s; }
.dt-search::placeholder { color: #4b5563; }
.dt-search:focus { border-color: rgba(217,119,6,0.5); }
.dt-search-clear { position: absolute; right: 0.6rem; background: none; border: none; cursor: pointer; color: #4b5563; font-size: 0.75rem; padding: 0.25rem; line-height: 1; }
.dt-search-clear:hover { color: #9ca3af; }

/* Grid */
.dt-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }

/* Card */
.dt-card { background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.15); border-radius: 1.25rem; overflow: hidden; text-decoration: none; color: inherit; display: flex; flex-direction: column; transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s; }
.dt-card:hover { transform: translateY(-4px); border-color: rgba(217,119,6,0.35); box-shadow: 0 12px 32px rgba(0,0,0,0.3); }
.dt-card.featured { border-color: rgba(217,119,6,0.3); }

/* Banner */
.dt-card-banner { height: 140px; background: linear-gradient(135deg, #0d0400 0%, #2a1000 60%, #0d0400 100%); position: relative; display: flex; align-items: flex-end; overflow: hidden; }
.dt-card-banner-inner { padding: 1.25rem 1.5rem; }
.dt-banner-cat   { font-size: 0.65rem; font-family: monospace; letter-spacing: 0.12em; color: #D97706; margin-bottom: 0.4rem; opacity: 0.85; }
.dt-banner-title { font-size: 1.35rem; font-weight: 800; color: #fff; line-height: 1.2; }
.dt-banner-meta  { font-size: 0.68rem; font-family: monospace; letter-spacing: 0.06em; color: #D97706; opacity: 0.65; margin-top: 0.3rem; }

/* Badge */
.dt-badge { position: absolute; top: 0.875rem; right: 0.875rem; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
.badge-popular { background: rgba(255,180,30,0.2); border: 1px solid rgba(255,180,30,0.4); color: #fbbf24; }
.badge-new      { background: rgba(0,217,126,0.2); border: 1px solid rgba(0,217,126,0.4); color: #34d399; }
.badge-cert     { background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.4); color: #93c5fd; }

/* Card body */
.dt-card-body { padding: 1.25rem 1.5rem; flex: 1; display: flex; flex-direction: column; gap: 0.625rem; }
.dt-card-meta { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.dt-card-meta span { font-size: 0.78rem; color: #6b7280; display: flex; align-items: center; gap: 0.3rem; }
.dt-meta-icon { font-size: 0.85rem; }
.dt-card-title { font-size: 1.1rem; font-weight: 700; color: #f1f5f9; line-height: 1.3; }
.dt-card-desc  { font-size: 0.85rem; color: #6b7280; line-height: 1.65; flex: 1; }

.dt-instructor { display: flex; align-items: center; gap: 0.6rem; }
.dt-instructor-avatar { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #0a0805; flex-shrink: 0; }
.dt-instructor-name { font-size: 0.82rem; color: #9ca3af; }

.dt-card-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); margin-top: auto; flex-wrap: wrap; gap: 0.5rem; }
.dt-price { font-size: 1.3rem; font-weight: 700; color: #FCD34D; }
.dt-price span { font-size: 0.8rem; color: #6b7280; font-weight: 400; }
.dt-enrol-btn { font-size: 0.85rem; font-weight: 600; color: #FCD34D; padding: 0.45rem 0.9rem; border-radius: 0.5rem; background: rgba(217,119,6,0.12); border: 1px solid rgba(217,119,6,0.3); transition: all 0.18s; }
.dt-card:hover .dt-enrol-btn { background: rgba(217,119,6,0.22); }

/* Empty */
.dt-empty { text-align: center; padding: 4rem 2rem; }
.dt-empty-icon  { font-size: 2.5rem; opacity: 0.15; margin-bottom: 1rem; }
.dt-empty-title { font-size: 1.1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.5rem; }
.dt-empty-desc  { font-size: 0.875rem; color: #6b7280; }

/* Mobile */
@media (max-width: 768px) {
  .dt-sidebar { transform: translateX(-100%); }
  .sidebar-open .dt-sidebar { transform: translateX(0); }
  .dt-sidebar-close { display: block; }
  .dt-main { margin-left: 0; }
  .dt-topbar { display: flex; }
  .dt-content { padding: 1.25rem; }
  .dt-page-title { font-size: 1.3rem; }
  .dt-grid { grid-template-columns: 1fr; }
}
</style>
