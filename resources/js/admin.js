import { createApp } from 'vue';
import DashboardPage from './components/admin/DashboardPage.vue';
import UsersPage from './components/admin/UsersPage.vue';
import AgentsPage from './components/admin/AgentsPage.vue';
import AgentFormPage from './components/admin/AgentFormPage.vue';
import UserDetailPage from './components/admin/UserDetailPage.vue';
import UserAgentsPage from './components/admin/UserAgentsPage.vue';
import AllAgentsPage from './components/admin/AllAgentsPage.vue';
import SkillsPage from './components/admin/SkillsPage.vue';
import SkillFormPage from './components/admin/SkillFormPage.vue';

const pages = { dashboard: DashboardPage, users: UsersPage, 'user-detail': UserDetailPage, 'user-agents': UserAgentsPage, agents: AgentsPage, 'agent-form': AgentFormPage, 'all-agents': AllAgentsPage, skills: SkillsPage, 'skill-form': SkillFormPage };

const el = document.getElementById('admin-app');
if (el) {
    const page = el.dataset.page || 'dashboard';
    const Component = pages[page];
    if (Component) {
        createApp(Component, JSON.parse(el.dataset.props || '{}')).mount(el);
    }
}
