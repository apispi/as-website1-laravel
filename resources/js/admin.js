import { createApp } from 'vue';
import DashboardPage from './components/admin/DashboardPage.vue';
import UsersPage from './components/admin/UsersPage.vue';
import AgentsPage from './components/admin/AgentsPage.vue';

const pages = { dashboard: DashboardPage, users: UsersPage, agents: AgentsPage };

const el = document.getElementById('admin-app');
if (el) {
    const page = el.dataset.page || 'dashboard';
    const Component = pages[page];
    if (Component) {
        createApp(Component, JSON.parse(el.dataset.props || '{}')).mount(el);
    }
}
