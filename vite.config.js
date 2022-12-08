import { defineConfig, splitVendorChunkPlugin } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import path from 'path'

// https://vitejs.dev/config/
export default defineConfig({

  plugins: [
    vue(),
    liveReload([
      // edit live reload paths according to your source code
      // for example:
      __dirname + '/(app|config|resources)/**/*.php',
      // using this for our example:
      __dirname + '/index.php',
    ]),
    splitVendorChunkPlugin(),
  ],

  // config
  root: 'resources',
  base: process.env.APP_ENV === 'development'
    ? '/'
    : '/public/build/',

  build: {
    // output dir for production build
    outDir: './../public/build',
    emptyOutDir: true,

    // emit manifest so PHP can find the hashed files
    manifest: true,

    // our entry
    rollupOptions: {
      // input: path.resolve(__dirname, 'resources/main.js'),
      input: path.resolve(__dirname, 'resources/main.js')
    }
  },

  server: {
    // we need a strict port to match on PHP side
    // change freely, but update on PHP to match the same port
    // tip: choose a different port per project to run them at the same time
    strictPort: true,
    port: 5134,
  },

  // required for in-browser template compilation
  // https://vuejs.org/guide/scaling-up/tooling.html#note-on-in-browser-template-compilation
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js'
    }
  }
})
