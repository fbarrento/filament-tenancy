import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/theme.css',
            ],
            refresh: true,
            detectTls: 'filament-tenancy.test',
        }),
    ],
    server: {
        host: 'filament-tenancy.test',
    }
});
