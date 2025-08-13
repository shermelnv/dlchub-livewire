import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: 'gondola.proxy.rlwy.net',
    wsPort: 44208,
    wssPort: 44208,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});
