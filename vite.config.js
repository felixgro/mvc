import {defineConfig, splitVendorChunkPlugin} from 'vite';
import vue from '@vitejs/plugin-vue';
import liveReload from 'vite-plugin-live-reload';
import path from 'path';

// https://vitejs.dev/config/
export default defineConfig({
    root: 'resources',

    base: process.env.APP_ENV === 'development'
        ? '/'
        : '/build/',

    plugins: [
        vue(),
        liveReload([
            __dirname + '/(app|config|resources)/**/*.php',
            __dirname + '/public/index.php',
        ]),
        splitVendorChunkPlugin(),
    ],

    build: {
        outDir: '../public/build',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: path.resolve(__dirname, 'resources/main.js')
        }
    },

    server: {
        strictPort: true,
        port: 5134,
    },

    resolve: {
        alias: {
            // https://vuejs.org/guide/scaling-up/tooling.html#note-on-in-browser-template-compilation
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    }
});
