import { createApp } from 'vue';
import DashboardChatbot from './components/DashboardChatbot.vue';

const el = document.getElementById('dashboard-chat');
if (el) {
    const app = createApp(DashboardChatbot, {
        connectorEndpoint: el.dataset.connectorEndpoint || '',
        csrfToken: el.dataset.csrfToken || '',
    });
    app.mount(el);
}
