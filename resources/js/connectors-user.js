import { createApp } from 'vue';
import UserConnectorsPage from './components/UserConnectorsPage.vue';
import UserConnectorEditPage from './components/UserConnectorEditPage.vue';

const listEl = document.getElementById('user-connectors-app');
if (listEl) {
    createApp(UserConnectorsPage, {
        user:            JSON.parse(listEl.dataset.user || '{}'),
        csrfToken:       listEl.dataset.csrf || '',
        userConnectors:  JSON.parse(listEl.dataset.userConnectors || '[]'),
        available:       JSON.parse(listEl.dataset.available || '[]'),
        flashSuccess:    listEl.dataset.flashSuccess || '',
        flashError:      listEl.dataset.flashError || '',
    }).mount(listEl);
}

const editEl = document.getElementById('user-connector-edit-app');
if (editEl) {
    createApp(UserConnectorEditPage, {
        user:          JSON.parse(editEl.dataset.user || '{}'),
        csrfToken:     editEl.dataset.csrf || '',
        userConnector: JSON.parse(editEl.dataset.userConnector || '{}'),
        flashSuccess:  editEl.dataset.flashSuccess || '',
        flashError:    editEl.dataset.flashError || '',
        errors:        JSON.parse(editEl.dataset.errors || '[]'),
    }).mount(editEl);
}
