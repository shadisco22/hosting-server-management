import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['public/css/app.4c4f97f9.css', 'public/js/app.3ee4842e.js'],
            refresh: true,
        }),
    ],
});
