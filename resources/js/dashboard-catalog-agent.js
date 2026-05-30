import { createApp } from 'vue';
import DashboardCatalogAgentPage from './components/DashboardCatalogAgentPage.vue';

const el = document.getElementById('dashboard-catalog-agent-app');
if (el) {
    createApp(DashboardCatalogAgentPage, {
        user:      JSON.parse(el.dataset.user      || '{}'),
        csrfToken: el.dataset.csrf                 || '',
        agent:     JSON.parse(el.dataset.agent     || '{}'),
    }).mount(el);
}
