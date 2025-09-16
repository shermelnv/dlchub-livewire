import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
    host: '0.0.0.0',            // Allow external devices to connect
    port: 5173,                 // Default Vite port
    hmr: {
      host: '127.0.0.1',   
      // host: '192.168.100.27', // Your local IP (same as in APP_URL)
    },
  },
});