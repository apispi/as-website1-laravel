import { createApp } from 'vue';
import DashboardApp from './components/DashboardApp.vue';

const el = document.getElementById('dashboard-app');
if (el) {
    createApp(DashboardApp, {
        user: JSON.parse(el.dataset.user || '{}'),
        csrfToken: el.dataset.csrf || '',
        subscriptions: JSON.parse(el.dataset.subscriptions || '[]'),
    }).mount(el);
}
