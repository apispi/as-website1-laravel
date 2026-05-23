import { createApp } from 'vue';
import UserConnectorsPage from './components/UserConnectorsPage.vue';

const el = document.getElementById('user-connectors-app');
if (el) {
    createApp(UserConnectorsPage, {
        user:            JSON.parse(el.dataset.user || '{}'),
        csrfToken:       el.dataset.csrf || '',
        userConnectors:  JSON.parse(el.dataset.userConnectors || '[]'),
        available:       JSON.parse(el.dataset.available || '[]'),
        flashSuccess:    el.dataset.flashSuccess || '',
        flashError:      el.dataset.flashError || '',
    }).mount(el);
}
