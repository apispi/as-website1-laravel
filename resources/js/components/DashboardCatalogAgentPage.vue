<template>
  <div class="dca-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <div class="dca-overlay" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside class="dca-sidebar">
      <div class="dca-sidebar-header">
        <a href="/" class="dca-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="dca-logo-icon">
            <defs>
              <linearGradient id="dcalg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dcalg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dcalg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="dca-sidebar-close" @click="sidebarOpen = false" aria-label="Close">✕</button>
      </div>

      <nav class="dca-nav">
        <span class="dca-nav-label">Workspace</span>
        <a href="/dashboard"            class="dca-nav-link"><span class="dca-nav-icon">⬡</span> Home</a>
        <a href="/dashboard/subscriptions"     class="dca-nav-link"><span class="dca-nav-icon">◈</span> My Agents</a>
        <a href="/dashboard/connectors" class="dca-nav-link"><span class="dca-nav-icon">⬡</span> My Connectors</a>
        <a href="/dashboard/catalog"    class="dca-nav-link active"><span class="dca-nav-icon">◎</span> Catalog</a>
        <a href="/dashboard/training"   class="dca-nav-link"><span class="dca-nav-icon">◷</span> Training</a>
        <a href="/dashboard/aria"       class="dca-nav-link"><span class="dca-nav-icon">◇</span> Aria</a>

        <span class="dca-nav-label">Account</span>

        <template v-if="user.is_admin">
          <span class="dca-nav-label">Administration</span>
          <a href="/admin" class="dca-nav-link dca-nav-admin"><span class="dca-nav-icon">⬡</span> Admin Panel</a>
        </template>
      </nav>

      <div class="dca-sidebar-footer">
        <div class="dca-user-row">
          <div class="dca-avatar">{{ initial }}</div>
          <div class="dca-user-text">
            <div class="dca-user-name">{{ user.name }}</div>
            <div class="dca-user-email">{{ user.email }}</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <div class="dca-main">
      <header class="dca-topbar">
        <button class="dca-menu-btn" @click="sidebarOpen = true"><span></span><span></span><span></span></button>
        <a href="/" class="dca-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="dca-logo-icon">
            <defs>
              <linearGradient id="dcalg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#dcalg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#dcalg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="dca-topbar-right">
          <div class="dca-avatar dca-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <main class="dca-content">

        <a href="/dashboard/catalog" class="dca-back">← Agent Catalog</a>

        <!-- Hero -->
        <div class="dca-hero">
          <div class="dca-hero-left">
            <div class="dca-hero-icon">◈</div>
            <div>
              <div class="dca-hero-category">{{ (agent.category || 'Agent').toUpperCase() }}</div>
              <h1 class="dca-hero-title">{{ agent.name }}</h1>
              <p v-if="agent.tagline" class="dca-hero-tagline">{{ agent.tagline }}</p>
              <div class="dca-hero-meta">
                <span v-if="agent.badge" class="dca-badge" :class="(agent.badge || '').toLowerCase()">{{ agent.badge }}</span>
                <span v-if="agent.rating" class="dca-meta-pill">⭐ {{ agent.rating }}</span>
                <span v-if="agent.users_count" class="dca-meta-pill">{{ agent.users_count }} users</span>
              </div>
            </div>
          </div>
          <div class="dca-hero-right">
            <div v-if="agent.price" class="dca-price">
              {{ agent.price }}<span v-if="agent.price_unit"> / {{ agent.price_unit }}</span>
            </div>
            <a v-if="!isSubscribed" :href="ctaUrl" class="dca-cta-btn">Get This Agent →</a>
            <span v-else class="dca-subscribed-badge">✓ Already Subscribed</span>
          </div>
        </div>

        <!-- Description -->
        <div v-if="agent.description" class="dca-section">
          <h2 class="dca-section-title">Overview</h2>
          <p class="dca-description">{{ agent.description }}</p>
        </div>

        <!-- Features -->
        <div v-if="agent.features && agent.features.length" class="dca-section">
          <h2 class="dca-section-title">Features</h2>
          <ul class="dca-list">
            <li v-for="(f, i) in agent.features" :key="i" class="dca-list-item">
              <span class="dca-list-icon">◇</span> {{ f }}
            </li>
          </ul>
        </div>

        <!-- What's Included -->
        <div v-if="agent.includes && agent.includes.length" class="dca-section">
          <h2 class="dca-section-title">What's Included</h2>
          <ul class="dca-list">
            <li v-for="(item, i) in agent.includes" :key="i" class="dca-list-item">
              <span class="dca-list-icon">◈</span> {{ item }}
            </li>
          </ul>
        </div>

        <!-- Use Cases -->
        <div v-if="agent.use_cases && agent.use_cases.length" class="dca-section">
          <h2 class="dca-section-title">Use Cases</h2>
          <div class="dca-use-cases">
            <div v-for="(uc, i) in agent.use_cases" :key="i" class="dca-use-case">{{ uc }}</div>
          </div>
        </div>

        <!-- Skills -->
        <div v-if="agent.skills && agent.skills.length" class="dca-section">
          <h2 class="dca-section-title">Skills</h2>
          <div class="dca-skills">
            <div v-for="s in agent.skills" :key="s.id" class="dca-skill">
              <div class="dca-skill-top">
                <span class="dca-skill-name">{{ s.name }}</span>
                <span v-if="s.category" class="dca-skill-cat">{{ s.category }}</span>
              </div>
              <p v-if="s.description" class="dca-skill-desc">{{ s.description }}</p>
            </div>
          </div>
        </div>

        <!-- Bottom CTA -->
        <div class="dca-bottom-cta">
          <div class="dca-bottom-cta-text">
            <div class="dca-bottom-cta-title">Ready to get started?</div>
            <div class="dca-bottom-cta-sub">Deploy {{ agent.name }} and start automating your workflows today.</div>
          </div>
          <a :href="ctaUrl" class="dca-cta-btn">Get This Agent →</a>
        </div>

      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user:           { type: Object, default: () => ({}) },
  csrfToken:      { type: String, default: '' },
  agent:          { type: Object, default: () => ({}) },
  isSubscribed:   { type: Boolean, default: false },
});

const sidebarOpen = ref(false);
const initial = computed(() => (props.user.name || 'U').charAt(0).toUpperCase());

const ctaUrl = computed(() => {
  if (props.agent.stripe_payment_link) return props.agent.stripe_payment_link;
  if (props.agent.checkout_amount) {
    const name = encodeURIComponent(props.agent.checkout_name || props.agent.name);
    return `/checkout?agent=${name}&amount=${props.agent.checkout_amount}&agent_id=${props.agent.id}`;
  }
  return '/contact';
});
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.dca-shell { display: flex; min-height: 100vh; background: #0a0805; color: #e5e7eb; font-family: 'Instrument Sans', system-ui, sans-serif; }

.dca-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 90; }
.sidebar-open .dca-overlay { display: block; }

/* Sidebar */
.dca-sidebar { width: 240px; background: rgba(14,11,6,0.98); border-right: 1px solid rgba(217,119,6,0.15); position: fixed; top: 0; left: 0; height: 100vh; display: flex; flex-direction: column; z-index: 100; overflow-y: auto; transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); }
.dca-sidebar-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.dca-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.15rem; font-weight: 700; }
.dca-logo-icon { width: 26px; height: 26px; }
.dca-sidebar-close { display: none; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.1rem; padding: 0.25rem; }

.dca-nav { flex: 1; padding: 1rem 0.75rem; display: flex; flex-direction: column; gap: 0.2rem; }
.dca-nav-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #4b5563; padding: 0.75rem 0.5rem 0.25rem; }
.dca-nav-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; border-radius: 0.5rem; text-decoration: none; color: #9ca3af; font-size: 0.875rem; transition: all 0.18s; min-height: 44px; }
.dca-nav-link:hover { background: rgba(217,119,6,0.1); color: #FCD34D; }
.dca-nav-link.active { background: rgba(217,119,6,0.15); color: #FCD34D; font-weight: 600; }
.dca-nav-admin { color: #fca5a5 !important; }
.dca-nav-admin:hover { background: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
.dca-nav-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }

.dca-sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(217,119,6,0.1); flex-shrink: 0; }
.dca-user-row { display: flex; align-items: center; gap: 0.75rem; }
.dca-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #D97706, #FCD34D); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: #0a0805; flex-shrink: 0; }
.dca-avatar-sm { width: 32px; height: 32px; font-size: 0.8rem; }
.dca-user-text { min-width: 0; }
.dca-user-name  { font-size: 0.85rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dca-user-email { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Main */
.dca-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

.dca-topbar { display: none; position: sticky; top: 0; z-index: 50; background: rgba(14,11,6,0.98); border-bottom: 1px solid rgba(217,119,6,0.12); padding: 0 1rem; height: 56px; align-items: center; justify-content: space-between; }
.dca-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #e5e7eb; font-size: 1.05rem; font-weight: 700; }
.dca-menu-btn { display: flex; flex-direction: column; justify-content: space-between; width: 24px; height: 18px; background: none; border: none; cursor: pointer; padding: 0; }
.dca-menu-btn span { display: block; width: 100%; height: 2px; background: #FCD34D; border-radius: 2px; }
.dca-topbar-right { display: flex; align-items: center; }

.dca-content { flex: 1; padding: 2rem; max-width: 860px; width: 100%; }

.dca-back { font-size: 0.82rem; color: #6b7280; text-decoration: none; display: inline-block; margin-bottom: 1.5rem; transition: color 0.18s; }
.dca-back:hover { color: #FCD34D; }

/* Hero */
.dca-hero { display: flex; align-items: flex-start; justify-content: space-between; gap: 2rem; background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.15); border-radius: 1.25rem; padding: 1.75rem 2rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.dca-hero-left { display: flex; align-items: flex-start; gap: 1.25rem; flex: 1; min-width: 0; }
.dca-hero-icon { width: 52px; height: 52px; border-radius: 0.875rem; flex-shrink: 0; background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.25); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #FCD34D; }
.dca-hero-category { font-size: 0.68rem; font-family: monospace; letter-spacing: 0.1em; color: #D97706; margin-bottom: 0.35rem; opacity: 0.85; }
.dca-hero-title { font-size: 1.6rem; font-weight: 800; color: #f1f5f9; line-height: 1.2; margin-bottom: 0.4rem; }
.dca-hero-tagline { font-size: 0.95rem; color: #9ca3af; margin-bottom: 0.75rem; line-height: 1.5; }
.dca-hero-meta { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; }
.dca-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
.dca-badge.popular { background: rgba(255,180,30,0.2); border: 1px solid rgba(255,180,30,0.4); color: #fbbf24; }
.dca-badge.new      { background: rgba(0,217,126,0.2); border: 1px solid rgba(0,217,126,0.4); color: #34d399; }
.dca-badge.premium  { background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); color: #fca5a5; }
.dca-meta-pill { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 9999px; font-size: 0.75rem; background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.15); color: #9ca3af; }

.dca-hero-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem; flex-shrink: 0; }
.dca-price { font-size: 1.5rem; font-weight: 700; color: #FCD34D; white-space: nowrap; }
.dca-price span { font-size: 0.85rem; color: #6b7280; font-weight: 400; }
.dca-cta-btn { display: inline-block; padding: 0.7rem 1.4rem; border-radius: 0.625rem; background: linear-gradient(135deg, #D97706, #B45309); color: #fff; font-size: 0.9rem; font-weight: 700; text-decoration: none; white-space: nowrap; transition: opacity 0.18s; }
.dca-cta-btn:hover { opacity: 0.88; }
.dca-subscribed-badge { display: inline-block; padding: 0.7rem 1.4rem; border-radius: 0.625rem; background: rgba(0,217,126,0.1); border: 1px solid rgba(0,217,126,0.3); color: #00d97e; font-size: 0.9rem; font-weight: 700; white-space: nowrap; }

/* Sections */
.dca-section { background: rgba(28,24,16,0.7); border: 1px solid rgba(217,119,6,0.12); border-radius: 1rem; padding: 1.5rem 1.75rem; margin-bottom: 1rem; }
.dca-section-title { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #D97706; margin-bottom: 1rem; }
.dca-description { font-size: 0.925rem; color: #9ca3af; line-height: 1.75; }

/* Features / Includes list */
.dca-list { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; }
.dca-list-item { display: flex; align-items: baseline; gap: 0.6rem; font-size: 0.9rem; color: #d1d5db; line-height: 1.5; }
.dca-list-icon { color: #D97706; font-size: 0.8rem; flex-shrink: 0; }

/* Use cases */
.dca-use-cases { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.dca-use-case { padding: 0.45rem 0.9rem; border-radius: 0.5rem; background: rgba(217,119,6,0.07); border: 1px solid rgba(217,119,6,0.15); font-size: 0.85rem; color: #d1d5db; }

/* Skills */
.dca-skills { display: flex; flex-direction: column; gap: 0.625rem; }
.dca-skill { background: rgba(217,119,6,0.04); border: 1px solid rgba(217,119,6,0.1); border-radius: 0.625rem; padding: 0.875rem 1rem; }
.dca-skill-top { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.3rem; flex-wrap: wrap; }
.dca-skill-name { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; }
.dca-skill-cat { font-size: 0.68rem; padding: 0.1rem 0.4rem; border-radius: 99px; background: rgba(217,119,6,0.1); color: #D97706; font-weight: 600; }
.dca-skill-desc { font-size: 0.8rem; color: #6b7280; line-height: 1.55; }

/* Bottom CTA */
.dca-bottom-cta { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; background: rgba(217,119,6,0.07); border: 1px solid rgba(217,119,6,0.2); border-radius: 1rem; padding: 1.5rem 1.75rem; margin-top: 1.5rem; flex-wrap: wrap; }
.dca-bottom-cta-title { font-size: 1rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.2rem; }
.dca-bottom-cta-sub { font-size: 0.875rem; color: #6b7280; }

/* Mobile */
@media (max-width: 768px) {
  .dca-sidebar { transform: translateX(-100%); }
  .sidebar-open .dca-sidebar { transform: translateX(0); }
  .dca-sidebar-close { display: block; }
  .dca-main { margin-left: 0; }
  .dca-topbar { display: flex; }
  .dca-content { padding: 1.25rem; }
  .dca-hero { flex-direction: column; }
  .dca-hero-right { align-items: flex-start; flex-direction: row; align-items: center; flex-wrap: wrap; }
  .dca-hero-title { font-size: 1.3rem; }
  .dca-bottom-cta { flex-direction: column; align-items: flex-start; }
}
</style>
