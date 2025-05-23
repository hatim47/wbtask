import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
       define: {
    __WS_TOKEN__: '""', // or `"fake-token"` if needed
  },resolve: {
    alias: {
      'vue': 'vue/dist/vue.esm-bundler.js' // Ensure this points to the full build of Vue
    }
  },
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
