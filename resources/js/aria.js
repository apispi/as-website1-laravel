import { createApp } from 'vue';
import DashboardAriaPage from './components/DashboardAriaPage.vue';

const el = document.getElementById('aria-app');
if (el) {
    createApp(DashboardAriaPage, {
        user:      JSON.parse(el.dataset.user      || '{}'),
        csrfToken: el.dataset.csrf || '',
    }).mount(el);
}
