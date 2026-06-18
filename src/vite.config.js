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
        host: '0.0.0.0', // Erlaubt Zugriffe von ausserhalb des Containers
        port: 5173,
        strictPort: true,
        cors: true,
        origin: 'https://dc-rezepte.duckdns.org',
        allowedHosts: true,
        hmr: {
            host: 'dc-rezepte.duckdns.org',
            clientPort: 443,
            protocol: 'wss',
            path: '/vite-ws',
        },
        watch: {
            usePolling: true // Wichtig fuer Docker unter LXC, damit Dateiaenderungen sofort erkannt werden
        }
    },
});
