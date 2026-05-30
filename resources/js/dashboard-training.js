import { createApp } from 'vue';
import DashboardTrainingPage from './components/DashboardTrainingPage.vue';

const el = document.getElementById('dashboard-training-app');
if (el) {
    createApp(DashboardTrainingPage, {
        user:      JSON.parse(el.dataset.user      || '{}'),
        csrfToken: el.dataset.csrf                 || '',
        trainings: JSON.parse(el.dataset.trainings || '[]'),
    }).mount(el);
}
