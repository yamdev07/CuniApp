import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        https: true,  // ← Activer HTTPS
        host: 'cuniapp.loca.lt',  // ← Correspondre à APP_URL
        port: 5173,
        hmr: {
            host: 'cuniapp.loca.lt',  // ← Important pour le hot-reload
        },
    },
});