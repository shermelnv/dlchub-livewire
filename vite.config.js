// import {
//     defineConfig
// } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import tailwindcss from "@tailwindcss/vite";

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//         tailwindcss(),
//     ],
//     server: {
//     host: '0.0.0.0',            // Allow external devices to connect
//     port: 5173,                 // Default Vite port
//     hmr: {
//       host: '192.168.100.6',    // Your local IP (same as in APP_URL)
//     },
//   },
// });

import { defineConfig } from 'vite';
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
  host: '0.0.0.0',
  port: 5173,
  hmr: {
    host: 'dlchub-livewire-production-650b.up.railway.app',
    protocol: 'wss'
  }
}

  // Remove server config for production
});
