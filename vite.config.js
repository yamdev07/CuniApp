// vite.config.js
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // 🔹 Disable HTTPS for local development
        https: false,
        host: 'localhost',
        port: 5173,
        hmr: {
            // 🔹 Use simple hostname, not full URL
            host: 'localhost',
            protocol: 'ws',
            clientPort: 5173, // Match Vite server port
        },
        cors: {
            origin: [
                'http://localhost:8000',
                'http://localhost:5173',
                'https://localhost:5173'
            ],
            credentials: true
        }
    },
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
})