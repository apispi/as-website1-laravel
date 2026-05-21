<template>
  <div class="ac-shell">

    <div class="ac-overlay" :class="{ open: sidebarOpen }" @click="sidebarOpen = false"></div>
    <aside class="ac-sidebar" :class="{ open: sidebarOpen }">
      <div class="ac-sidebar-header">
        <a href="/" class="ac-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="ac-logo-icon">
            <defs>
              <linearGradient id="aclg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#aclg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#aclg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="ac-sidebar-close" @click="sidebarOpen = false">✕</button>
      </div>

      <nav class="ac-nav">
        <span class="ac-nav-label">Workspace</span>
        <a href="/dashboard" class="ac-nav-link">
          <span class="ac-nav-icon">⬡</span> Overview
        </a>
        <a href="/dashboard/agents" class="ac-nav-link">
          <span class="ac-nav-icon">◈</span> My Agents
        </a>
        <a href="/dashboard/catalog" class="ac-nav-link active">
          <span class="ac-nav-icon">◎</span> Agent Catalog
        </a>
        <a href="/training" class="ac-nav-link">
          <span class="ac-nav-icon">◷</span> Training
        </a>
        <span class="ac-nav-label">Account</span>
        <a href="/contact" class="ac-nav-link">
          <span class="ac-nav-icon">◉</span> Support
        </a>
        <template v-if="user.is_admin">
          <span class="ac-nav-label">Administration</span>
          <a href="/admin" class="ac-nav-link ac-nav-admin">
            <span class="ac-nav-icon">⬡</span> Admin Panel
          </a>
        </template>
      </nav>

      <div class="ac-sidebar-footer">
        <div class="ac-user-row">
          <div class="ac-avatar">{{ initial }}</div>
          <div class="ac-user-text">
            <div class="ac-user-name">{{ user.name }}</div>
            <div class="ac-user-email">{{ user.email }}</div>
          </div>
        </div>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="ac-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="ac-main">
      <header class="ac-topbar">
        <button class="ac-menu-btn" @click="sidebarOpen = true">
          <span></span><span></span><span></span>
        </button>
        <a href="/" class="ac-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="ac-logo-icon">
            <defs>
              <linearGradient id="aclg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#aclg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#aclg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="ac-topbar-right">
          <div class="ac-avatar ac-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <main class="ac-content">
        <div class="ac-page-header">
          <div>
            <h1 class="ac-page-title">Agent Catalog</h1>
            <p class="ac-page-sub">{{ filtered.length }} of {{ agents.length }} available agents</p>
          </div>
        </div>

        <!-- Search + filter -->
        <div class="ac-toolbar">
          <div class="ac-search-wrap">
            <span class="ac-search-icon">◎</span>
            <input v-model="search" type="text" placeholder="Search agents…" class="ac-search-input">
          </div>
          <div class="ac-filter-tabs">
            <button v-for="cat in categories" :key="cat"
                    :class="['ac-tab', { active: activeCategory === cat }]"
                    @click="activeCategory = cat">
              {{ cat }}
            </button>
          </div>
        </div>

        <!-- Empty -->
        <div v-if="filtered.length === 0" class="ac-empty">
          <div class="ac-empty-icon">◎</div>
          <div class="ac-empty-title">No agents found</div>
          <div class="ac-empty-desc">Try a different search or category.</div>
        </div>

        <!-- Grid -->
        <div v-else class="ac-grid">
          <a v-for="agent in filtered" :key="agent.id"
             :href="`/agents/${agent.slug}`"
             class="ac-card">
            <div class="ac-card-top">
              <div class="ac-card-icon">◈</div>
              <span v-if="agent.badge" class="ac-badge" :class="agent.badge.toLowerCase()">{{ agent.badge }}</span>
            </div>
            <div class="ac-card-name">{{ agent.name }}</div>
            <div class="ac-card-category">{{ agent.category }}</div>
            <div class="ac-card-desc">{{ agent.description }}</div>
            <div class="ac-card-footer">
              <span class="ac-card-price">{{ agent.price ?? '—' }}</span>
              <span class="ac-card-cta">View →</span>
            </div>
          </a>
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
  agents:    { type: Array,  default: () => [] },
});

const sidebarOpen    = ref(false);
const search         = ref('');
const activeCategory = ref('All');

const initial = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

const categories = computed(() => {
  const cats = [...new Set(props.agents.map(a => a.category).filter(Boolean))].sort();
  return ['All', ...cats];
});

const filtered = computed(() => {
  return props.agents.filter(a => {
    const q = search.value.toLowerCase();
    const matchesSearch = !q || a.name.toLowerCase().includes(q) || (a.description || '').toLowerCase().includes(q);
    const matchesCat    = activeCategory.value === 'All' || a.category === activeCategory.value;
    return matchesSearch && matchesCat;
  });
});
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.ac-shell { display: flex; min-height: 100vh; background: #0a0805; color: #e5e7eb; font-family: 'Instrument Sans', system-ui, sans-serif; }

.ac-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 90; }
.ac-overlay.open { display: block; }

.ac-sidebar { width: 240px; background: rgba(14,11,6,0.98); border-right: 1px solid rgba(217,119,6,0.15); position: fixed; top: 0; left: 0; height: 100vh; display: flex; flex-direction: column; z-index: 100; overflow-y: auto; transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); }
.ac-sidebar-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.ac-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.15rem; font-weight: 700; }
.ac-logo-icon { width: 26px; height: 26px; }
.ac-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.1rem; padding: 0.25rem; }

.ac-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.ac-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.ac-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.ac-nav-link:hover { background: rgba(217,119,6,0.1); color: #FCD34D; }
.ac-nav-link.active { background: rgba(217,119,6,0.15); color: #FCD34D; font-weight: 600; }
.ac-nav-admin { color: #fca5a5 !important; }
.ac-nav-admin:hover { background: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
.ac-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.ac-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.ac-user-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
.ac-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0a0805; flex-shrink: 0; }
.ac-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.ac-user-text { min-width: 0; }
.ac-user-name { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ac-user-email { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ac-signout { display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.5rem 0.75rem; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 0.85rem; border-radius: 0.4rem; transition: all 0.18s; font-family: inherit; text-align: left; min-height: 44px; }
.ac-signout:hover { background: rgba(255,59,48,0.1); color: #ff3b30; }

.ac-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

.ac-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(14,11,6,0.98); border-bottom: 1px solid rgba(217,119,6,0.12); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.ac-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.05rem; font-weight: 700; }
.ac-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.ac-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }
.ac-topbar-right { display: flex; align-items: center; }

.ac-content { flex: 1; padding: 2rem; max-width: 1000px; width: 100%; }

.ac-page-header { margin-bottom: 1.5rem; }
.ac-page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.ac-page-sub { color: #6b7280; font-size: 0.875rem; }

.ac-toolbar { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.ac-search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 360px; }
.ac-search-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; pointer-events: none; }
.ac-search-input { width: 100%; padding: 0.625rem 0.875rem 0.625rem 2.25rem; background: rgba(28,24,16,0.8); border: 1px solid rgba(217,119,6,0.15); border-radius: 0.625rem; color: #e5e7eb; font-size: 0.9rem; font-family: inherit; transition: border-color 0.18s; }
.ac-search-input:focus { outline: none; border-color: rgba(217,119,6,0.4); }
.ac-search-input::placeholder { color: #4b5563; }

.ac-filter-tabs { display: flex; gap: 0.375rem; flex-wrap: wrap; }
.ac-tab { padding: 0.45rem 0.75rem; border-radius: 0.5rem; font-size: 0.78rem; font-weight: 600; cursor: pointer; background: none; border: 1px solid rgba(217,119,6,0.12); color: #6b7280; font-family: inherit; transition: all 0.18s; }
.ac-tab:hover { border-color: rgba(217,119,6,0.3); color: #FCD34D; }
.ac-tab.active { background: rgba(217,119,6,0.12); border-color: rgba(217,119,6,0.3); color: #FCD34D; }

.ac-empty { background: rgba(28,24,16,0.7); border: 1px dashed rgba(217,119,6,0.2); border-radius: 1rem; padding: 3rem 2rem; text-align: center; max-width: 400px; }
.ac-empty-icon { font-size: 2rem; opacity: 0.2; margin-bottom: 0.75rem; }
.ac-empty-title { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.4rem; }
.ac-empty-desc { font-size: 0.875rem; color: #6b7280; }

.ac-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; }
.ac-card {
  display: flex; flex-direction: column;
  background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.12);
  border-radius: 1rem; padding: 1.25rem;
  text-decoration: none; color: inherit;
  transition: border-color 0.2s, transform 0.2s, background 0.2s;
}
.ac-card:hover { border-color: rgba(217,119,6,0.35); transform: translateY(-2px); background: rgba(217,119,6,0.04); }
.ac-card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.875rem; }
.ac-card-icon { font-size: 1.25rem; color: #FCD34D; opacity: 0.8; }
.ac-badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 99px; font-size: 0.67rem; font-weight: 600; }
.ac-badge.popular { background: rgba(217,119,6,0.12); color: #FCD34D; }
.ac-badge.premium { background: rgba(239,68,68,0.12); color: #fca5a5; }
.ac-badge.new     { background: rgba(0,217,126,0.1);  color: #00d97e; }
.ac-card-name { font-size: 0.95rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.2rem; line-height: 1.3; }
.ac-card-category { font-size: 0.75rem; color: #6b7280; margin-bottom: 0.625rem; }
.ac-card-desc { font-size: 0.8rem; color: #9ca3af; line-height: 1.5; flex: 1; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.ac-card-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 0.875rem; border-top: 1px solid rgba(217,119,6,0.08); }
.ac-card-price { font-size: 0.95rem; font-weight: 700; color: #FCD34D; }
.ac-card-cta { font-size: 0.8rem; font-weight: 600; color: #D97706; transition: transform 0.18s; }
.ac-card:hover .ac-card-cta { transform: translateX(3px); }

@media (max-width: 768px) {
  .ac-sidebar { transform: translateX(-100%); }
  .ac-sidebar.open { transform: translateX(0); }
  .ac-sidebar-close { display: block; }
  .ac-main { margin-left: 0; }
  .ac-topbar { display: flex; }
  .ac-content { padding: 1.25rem; }
  .ac-page-title { font-size: 1.3rem; }
  .ac-grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 480px) {
  .ac-grid { grid-template-columns: 1fr; }
}
</style>
