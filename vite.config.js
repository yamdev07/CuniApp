import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true
        })
    ],
    server: {
        https: true,
        host: '0.0.0.0', // ← Listen on all interfaces
        port: 5173,
        hmr: {
            host: 'https://occupations-engine-checkout-breaking.trycloudflare.com', // ← Your tunnel domain
            clientPort: 443 // ← Use HTTPS port for HMR
        },
        // ✅ Allow your tunnel domain
        cors: {
            origin: [
                'https://occupations-engine-checkout-breaking.trycloudflare.com',
                'http://localhost:5173',
                'https://localhost:5173'
            ],
            credentials: true
        }
    }
})
