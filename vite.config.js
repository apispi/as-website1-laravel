import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            publicDirectory: 'public_html',
            buildDirectory: 'build',
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js', 'resources/js/admin.js', 'resources/js/agent-detail.js', 'resources/js/agents-list.js', 'resources/js/catalog.js', 'resources/js/profile.js', 'resources/js/connectors-user.js', 'resources/js/dashboard-chat.js', 'resources/js/aria.js', 'resources/js/dashboard-training.js', 'resources/js/dashboard-catalog-agent.js', 'resources/js/dashboard-catalog-connector.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
