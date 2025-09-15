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
    broadcaster: 'reverb',
    key: 'ogrjdpliov87qeaaw9gc',
    wsHost: 'plchubreverb-dokuv.ondigitalocean.app',
    wsPort: 6001,
    wssPort: 6001,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});
