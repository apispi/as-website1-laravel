import { createApp } from 'vue';
import AgentCatalogPage from './components/AgentCatalogPage.vue';

const el = document.getElementById('catalog-app');
if (el) {
    createApp(AgentCatalogPage, {
        user:           JSON.parse(el.dataset.user           || '{}'),
        csrfToken:      el.dataset.csrf                      || '',
        agents:         JSON.parse(el.dataset.agents         || '[]'),
        userConnectors: JSON.parse(el.dataset.userConnectors || '[]'),
        available:      JSON.parse(el.dataset.available      || '[]'),
        flashSuccess:   el.dataset.flashSuccess              || '',
        flashError:     el.dataset.flashError                || '',
    }).mount(el);
}
