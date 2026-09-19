import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/inertia.tsx',
            ],

            refresh: true,

            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),

        react(),

        tailwindcss(),
    ],

    server: {
        host: '127.0.0.1',
        port: 5176,
        hmr: {
            host: '127.0.0.1',
            port: 5176,
        },
        watch: {
            ignored: [
                '**/storage/framework/views/**',
            ],
        },
    },
});