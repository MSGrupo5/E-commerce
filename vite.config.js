import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/images/marketo_logo_final.svg',
                'resources/images/marketo_icono_solo.svg',
            ],
            refresh: true,
        }),
    ],
});
