<template>
  <div class="up-shell" :class="{ 'sidebar-open': sidebarOpen }">

    <div class="up-overlay" @click="sidebarOpen = false"></div>

    <aside class="up-sidebar">
      <div class="up-sidebar-header">
        <a href="/" class="up-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="up-logo-icon">
            <defs>
              <linearGradient id="uplg" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#uplg)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#uplg)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <button class="up-sidebar-close" @click="sidebarOpen = false" aria-label="Close menu">✕</button>
      </div>

      <nav class="up-nav">
        <span class="up-nav-label">Workspace</span>
        <a href="/dashboard" class="up-nav-link">
          <span class="up-nav-icon">⬡</span> Home
        </a>
        <a href="/dashboard/subscriptions" class="up-nav-link">
          <span class="up-nav-icon">◈</span> My Agents
        </a>
        <a href="/dashboard/connectors" class="up-nav-link">
          <span class="up-nav-icon">⬡</span> My Connectors
        </a>
        <a href="/dashboard/catalog" class="up-nav-link">
          <span class="up-nav-icon">◎</span> Catalog
        </a>
        <a href="/dashboard/aria"   class="up-nav-link"><span class="up-nav-icon">◇</span> Aria</a>
        <a href="/dashboard/training" class="up-nav-link">
          <span class="up-nav-icon">◷</span> Training
        </a>
        <template v-if="user.is_admin">
          <span class="up-nav-label">Administration</span>
          <a href="/admin" class="up-nav-link up-nav-admin">
            <span class="up-nav-icon">⬡</span> Admin Panel
          </a>
        </template>
      </nav>

      <div class="up-sidebar-footer">
        <a href="/dashboard/profile" class="up-user-row up-user-row-active">
          <div class="up-avatar">{{ initial }}</div>
          <div class="up-user-text">
            <div class="up-user-name">{{ user.name }}</div>
            <div class="up-user-email">{{ user.email }}</div>
          </div>
        </a>
        <form method="POST" action="/logout">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="up-signout">⏻ Sign Out</button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="up-main">
      <header class="up-topbar">
        <button class="up-menu-btn" @click="sidebarOpen = true" aria-label="Open menu">
          <span></span><span></span><span></span>
        </button>
        <a href="/" class="up-topbar-logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27" class="up-logo-icon">
            <defs>
              <linearGradient id="uplg2" x1=".5" y1="0" x2=".5" y2="1">
                <stop offset="0%" stop-color="#FCD34D"/>
                <stop offset="100%" stop-color="#D97706"/>
              </linearGradient>
            </defs>
            <path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#uplg2)"/>
            <path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#uplg2)"/>
          </svg>
          <span>ApiSpi</span>
        </a>
        <div class="up-topbar-right">
          <div class="up-avatar up-avatar-sm">{{ initial }}</div>
        </div>
      </header>

      <main class="up-content">

        <!-- Profile hero -->
        <div class="up-hero">
          <div class="up-hero-avatar">{{ initial }}</div>
          <div class="up-hero-info">
            <div class="up-hero-name">{{ user.name }}</div>
            <div class="up-hero-email">{{ user.email }}</div>
          </div>
        </div>

        <div v-if="flashSuccess" class="up-flash success">✓ {{ flashSuccess }}</div>
        <div v-if="flashError"   class="up-flash error">{{ flashError }}</div>

        <!-- Account details -->
        <div class="up-card">
          <div class="up-card-header">
            <h2 class="up-card-title">Account Details</h2>
            <p class="up-card-sub">Update your display name</p>
          </div>
          <form method="POST" action="/dashboard/profile">
            <input type="hidden" name="_token" :value="csrfToken">
            <input type="hidden" name="_method" value="PUT">
            <div class="up-form-group">
              <label class="up-label" for="profile-name">Full Name</label>
              <input id="profile-name" type="text" name="name" :value="user.name" required
                     class="up-input" :class="{ 'up-input-error': flashField === 'name' }"
                     placeholder="Your full name">
            </div>
            <div class="up-form-group">
              <label class="up-label">Email Address</label>
              <div class="up-input-static">
                <span class="up-input-static-val">{{ user.email }}</span>
                <span class="up-input-lock">🔒 Read only</span>
              </div>
              <p class="up-hint">Email cannot be changed. Contact support if needed.</p>
            </div>
            <div class="up-form-footer">
              <button type="submit" class="up-btn-save">Save Changes</button>
            </div>
          </form>
        </div>

        <!-- Change password -->
        <div class="up-card">
          <div class="up-card-header">
            <h2 class="up-card-title">Change Password</h2>
            <p class="up-card-sub">Choose a strong password of at least 8 characters</p>
          </div>
          <form method="POST" action="/dashboard/profile/password">
            <input type="hidden" name="_token" :value="csrfToken">
            <input type="hidden" name="_method" value="PUT">
            <div class="up-form-group">
              <label class="up-label" for="current-password">Current Password</label>
              <input id="current-password" type="password" name="current_password" required
                     class="up-input" :class="{ 'up-input-error': flashField === 'current_password' }"
                     placeholder="Enter your current password">
              <p v-if="flashField === 'current_password'" class="up-field-error">Incorrect current password.</p>
            </div>
            <div class="up-form-row">
              <div class="up-form-group">
                <label class="up-label" for="new-password">New Password</label>
                <input id="new-password" type="password" name="password" required minlength="8"
                       class="up-input" :class="{ 'up-input-error': flashField === 'password' }"
                       placeholder="Min. 8 characters">
              </div>
              <div class="up-form-group">
                <label class="up-label" for="confirm-password">Confirm New Password</label>
                <input id="confirm-password" type="password" name="password_confirmation" required
                       class="up-input" placeholder="Repeat new password">
              </div>
            </div>
            <div class="up-form-footer">
              <button type="submit" class="up-btn-save">Update Password</button>
            </div>
          </form>
        </div>

      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user:         { type: Object, default: () => ({}) },
  csrfToken:    { type: String, default: '' },
  flashSuccess: { type: String, default: '' },
  flashError:   { type: String, default: '' },
  flashField:   { type: String, default: '' },
});

const sidebarOpen = ref(false);
const initial = computed(() => props.user.name?.charAt(0).toUpperCase() ?? '?');
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.up-shell {
  display: flex; min-height: 100vh; background: #0a0805;
  font-family: 'Instrument Sans', system-ui, sans-serif;
}

/* Sidebar */
.up-sidebar {
  width: 240px; flex-shrink: 0;
  background: rgba(14,8,4,0.95);
  border-right: 1px solid rgba(217,119,6,0.1);
  display: flex; flex-direction: column;
  position: fixed; top: 0; left: 0; height: 100vh; z-index: 40;
  transition: transform 0.25s ease;
}
.up-overlay {
  display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 39;
}

.up-sidebar-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.25rem 1rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.08);
}
.up-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; }
.up-logo-icon { width: 22px; height: 25px; }
.up-logo span { font-size: 1.1rem; font-weight: 700; color: #FCD34D; letter-spacing: -0.01em; }
.up-sidebar-close {
  display: none; background: none; border: none; color: #6b7280;
  font-size: 1rem; cursor: pointer; padding: 0.25rem;
}

.up-nav {
  flex: 1; padding: 1rem 0.625rem; overflow-y: auto;
  display: flex; flex-direction: column; gap: 0.125rem;
}
.up-nav-label {
  font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.1em; color: #4b5563;
  padding: 0.75rem 0.5rem 0.25rem; display: block;
}
.up-nav-link {
  display: flex; align-items: center; gap: 0.625rem;
  padding: 0.5rem 0.75rem; border-radius: 0.5rem;
  color: #9ca3af; font-size: 0.875rem; font-weight: 500; text-decoration: none;
  transition: all 0.15s;
}
.up-nav-link:hover { background: rgba(217,119,6,0.06); color: #FCD34D; }
.up-nav-link.active { background: rgba(217,119,6,0.1); color: #FCD34D; }
.up-nav-icon { font-size: 0.9rem; width: 18px; text-align: center; flex-shrink: 0; }
.up-nav-admin { color: #fca5a5; }
.up-nav-admin:hover { background: rgba(239,68,68,0.06); color: #fca5a5; }

.up-sidebar-footer {
  padding: 0.875rem; border-top: 1px solid rgba(217,119,6,0.08);
  display: flex; flex-direction: column; gap: 0.5rem;
}
.up-user-row {
  display: flex; align-items: center; gap: 0.625rem;
  padding: 0.5rem 0.625rem; border-radius: 0.5rem;
  text-decoration: none; color: inherit;
  transition: background 0.15s;
  cursor: pointer;
}
.up-user-row:hover { background: rgba(217,119,6,0.06); }
.up-user-row-active { background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.2); }
.up-user-row-active:hover { background: rgba(217,119,6,0.15); }
.up-avatar {
  width: 32px; height: 32px; border-radius: 50%;
  background: linear-gradient(135deg, #D97706, #FCD34D);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.8rem; font-weight: 700; color: #0a0805; flex-shrink: 0;
}
.up-avatar-sm { width: 28px; height: 28px; font-size: 0.72rem; }
.up-user-text { overflow: hidden; }
.up-user-name { font-size: 0.8rem; font-weight: 600; color: #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.up-user-email { font-size: 0.7rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.up-signout {
  width: 100%; padding: 0.5rem; border-radius: 0.4rem;
  background: none; border: 1px solid rgba(217,119,6,0.12);
  color: #6b7280; font-size: 0.78rem; cursor: pointer; font-family: inherit;
  transition: all 0.15s; text-align: center; min-height: 36px;
}
.up-signout:hover { border-color: rgba(217,119,6,0.3); color: #9ca3af; }

/* Main */
.up-main { flex: 1; margin-left: 240px; display: flex; flex-direction: column; min-height: 100vh; }

.up-topbar {
  display: none; align-items: center; justify-content: space-between;
  padding: 0.75rem 1rem; border-bottom: 1px solid rgba(217,119,6,0.08);
  background: rgba(14,8,4,0.9); position: sticky; top: 0; z-index: 30;
}
.up-menu-btn {
  display: flex; flex-direction: column; gap: 4px;
  background: none; border: none; cursor: pointer; padding: 4px;
}
.up-menu-btn span { display: block; width: 20px; height: 2px; background: #9ca3af; border-radius: 1px; }
.up-topbar-logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; }
.up-topbar-logo span { font-size: 1rem; font-weight: 700; color: #FCD34D; }
.up-topbar-right { display: flex; align-items: center; gap: 0.75rem; }

.up-content { padding: 2rem 2.5rem; max-width: 680px; }

/* Hero */
.up-hero {
  display: flex; align-items: center; gap: 1.25rem;
  background: rgba(28,18,8,0.8); border: 1px solid rgba(217,119,6,0.2);
  border-radius: 1.25rem; padding: 1.5rem 1.75rem; margin-bottom: 2rem;
}
.up-hero-avatar {
  width: 64px; height: 64px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #D97706, #FCD34D);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.6rem; font-weight: 800; color: #0a0805;
  border: 2px solid rgba(217,119,6,0.4);
}
.up-hero-info { min-width: 0; }
.up-hero-name  { font-size: 1.25rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.25rem; }
.up-hero-email { font-size: 0.875rem; color: #9ca3af; }

/* Flash */
.up-flash {
  padding: 0.75rem 1rem; border-radius: 0.625rem;
  font-size: 0.875rem; margin-bottom: 1.5rem; font-weight: 500;
}
.up-flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.3); color: #00d97e; }
.up-flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; }

/* Cards */
.up-card {
  background: rgba(20,12,6,0.8); border: 1px solid rgba(217,119,6,0.15);
  border-radius: 1.25rem; padding: 1.75rem; margin-bottom: 1.5rem;
}
.up-card-header { margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(217,119,6,0.1); }
.up-card-title { font-size: 1.05rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.25rem; }
.up-card-sub   { font-size: 0.82rem; color: #6b7280; }

/* Form */
.up-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.up-form-group { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.25rem; }
.up-form-footer { padding-top: 0.5rem; border-top: 1px solid rgba(217,119,6,0.08); margin-top: 0.25rem; }

.up-label { font-size: 0.8rem; font-weight: 700; color: #d1d5db; letter-spacing: 0.02em; text-transform: uppercase; }

.up-input {
  padding: 0.75rem 1rem;
  background: rgba(10,8,5,0.9); border: 1px solid rgba(217,119,6,0.25);
  border-radius: 0.625rem; color: #f1f5f9; font-size: 0.95rem; font-family: inherit;
  transition: border-color 0.18s, box-shadow 0.18s; width: 100%;
}
.up-input:focus {
  outline: none;
  border-color: rgba(217,119,6,0.6);
  box-shadow: 0 0 0 3px rgba(217,119,6,0.1);
}
.up-input::placeholder { color: #4b5563; }
.up-input-error { border-color: rgba(239,68,68,0.6); }
.up-input-error:focus { border-color: rgba(239,68,68,0.7); box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }

.up-input-static {
  display: flex; align-items: center; justify-content: space-between;
  padding: 0.75rem 1rem;
  background: rgba(10,8,5,0.5); border: 1px solid rgba(217,119,6,0.1);
  border-radius: 0.625rem;
}
.up-input-static-val { font-size: 0.95rem; color: #9ca3af; }
.up-input-lock { font-size: 0.72rem; color: #4b5563; flex-shrink: 0; }

.up-hint { font-size: 0.76rem; color: #4b5563; }
.up-field-error { font-size: 0.78rem; color: #ef4444; }

.up-btn-save {
  padding: 0.7rem 1.75rem; border-radius: 0.625rem;
  background: rgba(217,119,6,0.2); border: 1px solid rgba(217,119,6,0.45);
  color: #FCD34D; font-size: 0.9rem; font-weight: 700; cursor: pointer;
  font-family: inherit; transition: all 0.18s; min-height: 44px;
  margin-top: 1rem;
}
.up-btn-save:hover { background: rgba(217,119,6,0.32); border-color: rgba(217,119,6,0.65); }

@media (max-width: 768px) {
  .up-sidebar { transform: translateX(-100%); }
  .up-shell.sidebar-open .up-sidebar { transform: translateX(0); }
  .up-shell.sidebar-open .up-overlay { display: block; }
  .up-sidebar-close { display: block; }
  .up-main { margin-left: 0; }
  .up-topbar { display: flex; }
  .up-content { padding: 1.25rem 1rem; }
  .up-hero { padding: 1.25rem; }
  .up-hero-avatar { width: 52px; height: 52px; font-size: 1.3rem; }
  .up-form-row { grid-template-columns: 1fr; }
}
</style>
