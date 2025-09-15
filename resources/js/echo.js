// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: 'ogrjdpliov87qeaaw9gc',
//     wsHost: 'plchubreverb-dokuv.ondigitalocean.app',
//     wsPort: 6001,
//     wssPort: 6001,
//     forceTLS: true,
//     enabledTransports: ['ws', 'wss'],
// });


import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: '20e244587fcb3abb6850',
    cluster: 'ap1',
    forceTLS: true,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    enabledTransports: ["ws", "wss"],
});
