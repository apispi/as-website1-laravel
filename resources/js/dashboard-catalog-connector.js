import { createApp } from 'vue';
import DashboardCatalogConnectorPage from './components/DashboardCatalogConnectorPage.vue';

const el = document.getElementById('dashboard-catalog-connector-app');
if (el) {
    createApp(DashboardCatalogConnectorPage, {
        user:          JSON.parse(el.dataset.user          || '{}'),
        csrfToken:     el.dataset.csrf                     || '',
        connector:     JSON.parse(el.dataset.connector     || '{}'),
        userConnector: JSON.parse(el.dataset.userConnector || 'null'),
    }).mount(el);
}
