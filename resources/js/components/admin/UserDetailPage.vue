<template>
  <AdminShell :user="currentUser" :csrf-token="csrfToken" page="users">

    <div class="page-header">
      <a href="/admin/users" class="back-link">← Users</a>
      <h1 class="page-title">{{ subject.name }}</h1>
    </div>

    <div v-if="flashSuccess" class="flash success">{{ flashSuccess }}</div>
    <div v-if="flashError"   class="flash error">{{ flashError }}</div>

    <div class="detail-grid">

      <!-- Profile card -->
      <div class="card">
        <div class="card-header">Account Details</div>
        <div class="avatar-row">
          <div class="big-avatar">{{ subject.name.charAt(0).toUpperCase() }}</div>
          <div>
            <div class="subject-name">{{ subject.name }}</div>
            <div class="subject-email">{{ subject.email }}</div>
            <span class="role-badge" :class="subject.is_admin ? 'admin' : 'user'">
              {{ subject.is_admin ? 'Admin' : 'User' }}
            </span>
          </div>
        </div>

        <div class="info-rows">
          <div class="info-row">
            <span class="info-label">Member since</span>
            <span class="info-val">{{ formatDate(subject.created_at) }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Email verified</span>
            <span class="info-val">{{ subject.email_verified_at ? formatDate(subject.email_verified_at) : 'Not verified' }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">User ID</span>
            <span class="info-val mono">{{ subject.id }}</span>
          </div>
        </div>
      </div>

      <!-- Actions card -->
      <div class="card">
        <div class="card-header">Actions</div>

        <div class="action-list">
          <div class="action-item">
            <div>
              <div class="action-title">Purchased Agents</div>
              <div class="action-desc">View all agents this user has subscribed to.</div>
            </div>
            <a :href="`/admin/users/${subject.id}/agents`" class="btn-action promote">View Agents</a>
          </div>
          <div class="action-item">
            <div>
              <div class="action-title">Connectors</div>
              <div class="action-desc">Manage integrations connected to this user.</div>
            </div>
            <a :href="`/admin/users/${subject.id}/connectors`" class="btn-action promote">View Connectors</a>
          </div>
          <div class="action-item">
            <div>
              <div class="action-title">{{ subject.is_admin ? 'Remove Admin' : 'Grant Admin' }}</div>
              <div class="action-desc">{{ subject.is_admin ? 'Revoke admin access for this user.' : 'Give this user admin access.' }}</div>
            </div>
            <form v-if="subject.id !== currentUser.id" method="POST" :action="`/admin/users/${subject.id}/toggle-admin`">
              <input type="hidden" name="_token" :value="csrfToken">
              <button type="submit" class="btn-action" :class="subject.is_admin ? 'danger' : 'promote'">
                {{ subject.is_admin ? 'Remove Admin' : 'Make Admin' }}
              </button>
            </form>
            <span v-else class="muted-note">This is your account</span>
          </div>
        </div>
      </div>

    </div>

  </AdminShell>
</template>

<script setup>
import AdminShell from './AdminShell.vue';

const props = defineProps({
  currentUser:  { type: Object, default: () => ({}) },
  csrfToken:    { type: String, default: '' },
  subject:      { type: Object, default: () => ({}) },
  flashSuccess: { type: String, default: '' },
  flashError:   { type: String, default: '' },
});

function formatDate(dateStr) {
  if (!dateStr) return '—';
  return new Date(dateStr).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 1.5rem; }
.back-link { font-size: 0.8rem; color: #6b7280; text-decoration: none; display: block; margin-bottom: 0.4rem; }
.back-link:hover { color: #fca5a5; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; }

.flash { padding: 0.75rem 1rem; border-radius: 0.625rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
.flash.success { background: rgba(0,217,126,0.08); border: 1px solid rgba(0,217,126,0.25); color: #00d97e; }
.flash.error   { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; }

.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; max-width: 860px; }

.card {
  background: rgba(24,10,10,0.6); border: 1px solid rgba(239,68,68,0.1);
  border-radius: 1rem; overflow: hidden;
}
.card-header {
  padding: 0.875rem 1.25rem;
  font-size: 0.78rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.07em; color: #6b7280;
  border-bottom: 1px solid rgba(239,68,68,0.08);
  background: rgba(239,68,68,0.03);
}

.avatar-row { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; border-bottom: 1px solid rgba(239,68,68,0.08); }
.big-avatar {
  width: 52px; height: 52px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #ef4444, #fca5a5);
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 1.2rem; color: #fff;
}
.subject-name  { font-size: 1rem; font-weight: 700; color: #e5e7eb; margin-bottom: 0.15rem; }
.subject-email { font-size: 0.82rem; color: #6b7280; margin-bottom: 0.4rem; }

.role-badge { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600; }
.role-badge.admin { background: rgba(239,68,68,0.12); color: #fca5a5; }
.role-badge.user  { background: rgba(107,114,128,0.12); color: #9ca3af; }

.info-rows {}
.info-row { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1.25rem; border-bottom: 1px solid rgba(239,68,68,0.06); gap: 1rem; }
.info-row:last-child { border-bottom: none; }
.info-label { font-size: 0.82rem; color: #6b7280; flex-shrink: 0; }
.info-val { font-size: 0.875rem; color: #e5e7eb; text-align: right; }
.mono { font-family: monospace; font-size: 0.8rem; color: #9ca3af; }

.action-list { padding: 0.5rem 0; }
.action-item { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.25rem; border-bottom: 1px solid rgba(239,68,68,0.06); }
.action-item:last-child { border-bottom: none; }
.action-title { font-size: 0.875rem; font-weight: 600; color: #e5e7eb; margin-bottom: 0.2rem; }
.action-desc  { font-size: 0.78rem; color: #6b7280; }
.muted-note   { font-size: 0.78rem; color: #4b5563; }

.btn-action {
  padding: 0.45rem 0.9rem; border-radius: 0.5rem; font-size: 0.82rem;
  font-weight: 600; cursor: pointer; border: none; font-family: inherit;
  transition: all 0.18s; white-space: nowrap; min-height: 44px;
}
.btn-action.promote { background: rgba(239,68,68,0.12); color: #fca5a5; }
.btn-action.promote:hover { background: rgba(239,68,68,0.22); }
.btn-action.danger  { background: rgba(107,114,128,0.12); color: #9ca3af; }
.btn-action.danger:hover  { background: rgba(107,114,128,0.22); }

@media (max-width: 640px) {
  .detail-grid { grid-template-columns: 1fr; }
  .page-title { font-size: 1.3rem; }
}
</style>
