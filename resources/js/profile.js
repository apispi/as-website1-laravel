import { createApp } from 'vue';
import UserProfilePage from './components/UserProfilePage.vue';

const el = document.getElementById('profile-app');
if (el) {
    createApp(UserProfilePage, {
        user:         JSON.parse(el.dataset.user || '{}'),
        csrfToken:    el.dataset.csrf || '',
        flashSuccess: el.dataset.flashSuccess || '',
        flashError:   el.dataset.flashError || '',
        flashField:   el.dataset.flashField || '',
    }).mount(el);
}
