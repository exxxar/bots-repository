import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'
import mkcert from 'vite-plugin-mkcert'
import basicSsl from '@vitejs/plugin-basic-ssl'

export default defineConfig({
    server: {https: false},
    plugins: [
        laravel({

            input: [
                'resources/js/AdminPanel/app.js',
                'resources/js/ClientTg/app.js',
                'resources/js/Landing/app.js',
                'resources/js/Mobile/app.js',


                'resources/css/AdminPanel/app.css',
                'resources/css/ClientTg/app.css',
                'resources/css/Landing/app.css',
                'resources/css/Mobile/app.css',

            ],

            ssr: ['resources/js/ClientTg/ssr.js',
                'resources/js/Landing/ssr.js',

            ],
            refresh: true,
        }),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            'AdminPanel@':path.resolve(__dirname, './resources/js/AdminPanel'),
            'ClientTg@': path.resolve(__dirname, './resources/js/ClientTg'),
            'Landing@': path.resolve(__dirname, './resources/js/Landing'),
            'Mobile@': path.resolve(__dirname, './resources/js/Mobile'),
        },
    },
});
