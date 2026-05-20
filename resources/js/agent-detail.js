import { createApp } from 'vue';
import UserAgentPage from './components/UserAgentPage.vue';

const el = document.getElementById('agent-detail-app');
if (el) {
    createApp(UserAgentPage, {
        user:         JSON.parse(el.dataset.user         || '{}'),
        csrfToken:    el.dataset.csrf                    || '',
        subscription: JSON.parse(el.dataset.subscription || '{}'),
    }).mount(el);
}
