import { createApp } from 'vue';
import UserAgentsListPage from './components/UserAgentsListPage.vue';

const el = document.getElementById('agents-list-app');
if (el) {
    createApp(UserAgentsListPage, {
        user:          JSON.parse(el.dataset.user          || '{}'),
        csrfToken:     el.dataset.csrf                     || '',
        subscriptions: JSON.parse(el.dataset.subscriptions || '[]'),
    }).mount(el);
}
