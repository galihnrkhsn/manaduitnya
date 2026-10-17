import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        VitePWA({
            outDir: 'public',
            buildBase: '/',
            injectRegister: false,
            manifest: {
                name: 'ManaDuitnya',
                short_name: 'ManaDuitnya',
                description: 'Mencatat pengeluaran bulanan, biar tahu miskinnya karena apa.',
                theme_color: '#ffffff',
                background_color: '#ffffff',
                display: 'standalone',
                icons: [
                    { src: '/icons/icon-192.png', sizes: '192x192', type: 'image/png' },
                    { src: '/icons/icon-512.png', sizes: '512x512', type: 'image/png' },
                ]
            },
            workbox: {
                navigateFallback: null,
                globPatterns: ['build/**/*.{js,css,html,ico,png,svg,woff2,woff}']
            }
        }),
    ],
});
