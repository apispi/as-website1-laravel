<template>
  <AdminShell :user="user" :csrf-token="''" page="azure-policies">

    <div class="page-header">
      <h1 class="page-title">Microsoft Entra</h1>
      <p class="page-sub">Conditional Access policies and directory roles from your M365 tenant</p>
    </div>

    <!-- Not configured -->
    <div v-if="!ready" class="notice warning">
      <strong>App token not configured.</strong>
      Set <code>AZURE_TENANT_ID</code> to your specific tenant ID (not <code>common</code>) and ensure
      <code>AZURE_CLIENT_ID</code> / <code>AZURE_CLIENT_SECRET</code> are set in <code>.env</code>.
      Then grant the app <strong>Policy.Read.All</strong> and <strong>RoleManagement.Read.Directory</strong>
      application permissions in Azure Portal with admin consent.
    </div>

    <!-- Role Map -->
    <div class="section">
      <h2 class="section-title">Role Mapping</h2>
      <p class="section-sub">Entra groups mapped to ApiSpi roles (synced on each Azure login)</p>
      <div v-if="Object.keys(roleMap).length" class="table-wrap">
        <table class="data-table">
          <thead>
            <tr><th>Entra Group (name or object ID)</th><th>ApiSpi Role</th></tr>
          </thead>
          <tbody>
            <tr v-for="(role, group) in roleMap" :key="group">
              <td><code>{{ group }}</code></td>
              <td><span class="badge" :class="role === 'admin' ? 'badge-red' : 'badge-blue'">{{ role }}</span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="empty-hint">
        No role mappings configured. Set <code>AZURE_ADMIN_GROUP</code> or <code>AZURE_AGENT_GROUP</code> in <code>.env</code>.
      </div>
    </div>

    <!-- Conditional Access Policies -->
    <div class="section">
      <h2 class="section-title">Conditional Access Policies
        <span class="count-badge">{{ policies.length }}</span>
      </h2>
      <div v-if="!ready" class="empty-hint">App token required — see notice above.</div>
      <div v-else-if="!policies.length" class="empty-hint">No policies found or insufficient permissions.</div>
      <div v-else class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>State</th>
              <th>Users</th>
              <th>Apps</th>
              <th>Grant Controls</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in policies" :key="p.id">
              <td>
                <span class="name">{{ p.displayName }}</span>
                <span class="id-hint">{{ p.id }}</span>
              </td>
              <td>
                <span class="badge" :class="stateClass(p.state)">{{ p.state }}</span>
              </td>
              <td class="small-cell">
                <span v-if="p.conditions?.users?.includeUsers?.includes('All')">All users</span>
                <span v-else-if="p.conditions?.users?.includeGroups?.length">
                  {{ p.conditions.users.includeGroups.length }} group(s)
                </span>
                <span v-else class="muted">—</span>
              </td>
              <td class="small-cell">
                <span v-if="p.conditions?.applications?.includeApplications?.includes('All')">All apps</span>
                <span v-else-if="p.conditions?.applications?.includeApplications?.length">
                  {{ p.conditions.applications.includeApplications.length }} app(s)
                </span>
                <span v-else class="muted">—</span>
              </td>
              <td class="small-cell">
                <span v-if="p.grantControls?.builtInControls?.length">
                  {{ p.grantControls.builtInControls.join(', ') }}
                </span>
                <span v-else class="muted">—</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Directory Roles -->
    <div class="section">
      <h2 class="section-title">Directory Roles
        <span class="count-badge">{{ roles.length }}</span>
      </h2>
      <div v-if="!ready" class="empty-hint">App token required — see notice above.</div>
      <div v-else-if="!roles.length" class="empty-hint">No directory roles found or insufficient permissions.</div>
      <div v-else class="table-wrap">
        <table class="data-table">
          <thead>
            <tr><th>Role Name</th><th>Description</th><th>ID</th></tr>
          </thead>
          <tbody>
            <tr v-for="r in roles" :key="r.id">
              <td><span class="name">{{ r.displayName }}</span></td>
              <td class="muted small-cell">{{ r.description || '—' }}</td>
              <td><code class="id-hint">{{ r.id }}</code></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </AdminShell>
</template>

<script setup>
import AdminShell from './AdminShell.vue';

const props = defineProps({
  user:     { type: Object, default: () => ({}) },
  policies: { type: Array,  default: () => [] },
  roles:    { type: Array,  default: () => [] },
  roleMap:  { type: Object, default: () => ({}) },
  tenant:   { type: String, default: '' },
  ready:    { type: Boolean, default: false },
});

function stateClass(state) {
  if (state === 'enabled')          return 'badge-green';
  if (state === 'enabledForReportingButNotEnforced') return 'badge-yellow';
  return 'badge-gray';
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.page-header { margin-bottom: 2rem; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; }
.page-sub { font-size: 0.875rem; color: #6b7280; margin-top: 0.3rem; }

.notice { padding: 1rem 1.25rem; border-radius: 0.75rem; font-size: 0.875rem; margin-bottom: 2rem; line-height: 1.6; }
.notice.warning { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.25); color: #fbbf24; }
.notice code { background: rgba(255,255,255,0.08); padding: 0.1rem 0.35rem; border-radius: 0.25rem; font-size: 0.8rem; }

.section { margin-bottom: 2.5rem; }
.section-title { font-size: 1.05rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.5rem; }
.section-sub { font-size: 0.82rem; color: #6b7280; margin-bottom: 1rem; }
.count-badge { background: rgba(239,68,68,0.15); color: #fca5a5; font-size: 0.72rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 9999px; }

.table-wrap { overflow-x: auto; border-radius: 0.75rem; border: 1px solid rgba(239,68,68,0.1); }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.data-table th { padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; background: rgba(24,10,10,0.6); border-bottom: 1px solid rgba(239,68,68,0.08); }
.data-table td { padding: 0.75rem 1rem; border-bottom: 1px solid rgba(239,68,68,0.06); color: #d1d5db; vertical-align: top; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: rgba(239,68,68,0.03); }

.name { display: block; color: #f1f5f9; font-weight: 500; }
.id-hint { display: block; font-size: 0.72rem; color: #4b5563; font-family: monospace; margin-top: 0.15rem; }
.small-cell { font-size: 0.82rem; }
.muted { color: #4b5563; }

code { background: rgba(255,255,255,0.06); padding: 0.15rem 0.4rem; border-radius: 0.3rem; font-size: 0.78rem; color: #fca5a5; font-family: monospace; }

.empty-hint { font-size: 0.875rem; color: #4b5563; padding: 1.5rem; background: rgba(24,10,10,0.4); border: 1px solid rgba(239,68,68,0.08); border-radius: 0.75rem; }
.empty-hint code { background: rgba(255,255,255,0.06); padding: 0.1rem 0.35rem; border-radius: 0.25rem; }

.badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 600; text-transform: capitalize; }
.badge-red    { background: rgba(239,68,68,0.15);   color: #fca5a5; }
.badge-blue   { background: rgba(59,130,246,0.15);  color: #93c5fd; }
.badge-green  { background: rgba(34,197,94,0.15);   color: #86efac; }
.badge-yellow { background: rgba(234,179,8,0.15);   color: #fde047; }
.badge-gray   { background: rgba(107,114,128,0.15); color: #9ca3af; }
</style>
