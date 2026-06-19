import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss({
            sourceMap: true,
        }),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        cors: true,
        // Keine Fallbacks mehr – muss über ENV gesetzt werden
        origin: process.env.VITE_ORIGIN,
        allowedHosts: true,
        hmr: {
            host: process.env.VITE_HMR_HOST,
            clientPort: parseInt(process.env.VITE_HMR_CLIENT_PORT || '5173'),
            protocol: process.env.VITE_HMR_PROTOCOL || 'ws',
            path: process.env.VITE_HMR_PATH || '/vite-ws',
        },
        watch: {
            usePolling: true
        }
    },
});
