import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import basicSsl from '@vitejs/plugin-basic-ssl'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.tsx',
            ],
            refresh: true,
        }),
        react(),
        basicSsl(),
    ],
    server: {
        https: true,
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        },
    },
});