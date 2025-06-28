import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin'
import { resolve } from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'src/js/app.js',
                'src/css/app.css'
            ],
            publicDirectory: '../public',
            buildDirectory: 'build-mary',
            refresh: true,
            detectTls: 'filament-tenancy.test',
            manifest: 'build-mary/manifest.json'
        }),
        tailwindcss(),
    ],
    build: {
        outDir: resolve('../public/build-mary'),
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: 'js/[name].[hash].js',
                chunkFileNames: 'js/[name].[hash].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/[name].[hash][extname]'
                    }
                    return 'assets/[name].[hash][extname]'
                },
                manualChunks: {}
            }
        }
    },
    server: {
        host: 'filament-tenancy.test',
        watch: {
            usePolling: true,
        },
    }
})
