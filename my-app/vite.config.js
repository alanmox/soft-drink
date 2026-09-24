import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
        // Forward page requests to Laravel so the Vite URL renders the app too.
        // Vite keeps serving its own assets (/@vite, /resources, /node_modules, ...).
        proxy: {
            '^/(?!@|resources/|node_modules/|__|build/).*': {
                target: 'http://127.0.0.1:8000',
                changeOrigin: false,
            },
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
