import { createApp } from 'vue';
import DashboardPage from './components/admin/DashboardPage.vue';
import UsersPage from './components/admin/UsersPage.vue';
import AgentsPage from './components/admin/AgentsPage.vue';
import AgentFormPage from './components/admin/AgentFormPage.vue';
import UserDetailPage from './components/admin/UserDetailPage.vue';
import UserAgentsPage from './components/admin/UserAgentsPage.vue';
import AllAgentsPage from './components/admin/AllAgentsPage.vue';
import AgentDetailAdminPage from './components/admin/AgentDetailAdminPage.vue';
import SubscriptionDetailPage from './components/admin/SubscriptionDetailPage.vue';
import SkillsPage from './components/admin/SkillsPage.vue';
import SkillFormPage from './components/admin/SkillFormPage.vue';
import ActivityLogPage from './components/admin/ActivityLogPage.vue';
import TrainingsPage from './components/admin/TrainingsPage.vue';
import TrainingFormPage from './components/admin/TrainingFormPage.vue';
import ConnectorsPage from './components/admin/ConnectorsPage.vue';
import ConnectorFormPage from './components/admin/ConnectorFormPage.vue';
import UserConnectorsPage from './components/admin/UserConnectorsPage.vue';
import UserConnectorsManagePage from './components/admin/UserConnectorsManagePage.vue';
import UserConnectorConfigPage from './components/admin/UserConnectorConfigPage.vue';
import UserConnectorEditPage from './components/admin/UserConnectorEditPage.vue';

const pages = { dashboard: DashboardPage, users: UsersPage, 'user-detail': UserDetailPage, 'user-agents': UserAgentsPage, agents: AgentsPage, 'agent-form': AgentFormPage, 'all-agents': AllAgentsPage, 'agent-detail-admin': AgentDetailAdminPage, 'subscription-detail': SubscriptionDetailPage, skills: SkillsPage, 'skill-form': SkillFormPage, 'activity-log': ActivityLogPage, trainings: TrainingsPage, 'training-form': TrainingFormPage, connectors: ConnectorsPage, 'connector-form': ConnectorFormPage, 'user-connectors': UserConnectorsPage, 'user-connectors-manage': UserConnectorsManagePage, 'user-connector-config': UserConnectorConfigPage, 'user-connector-edit': UserConnectorEditPage };

const el = document.getElementById('admin-app');
if (el) {
    const page = el.dataset.page || 'dashboard';
    const Component = pages[page];
    if (Component) {
        createApp(Component, JSON.parse(el.dataset.props || '{}')).mount(el);
    }
}
