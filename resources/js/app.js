import '../../vendor/masmerise/livewire-toaster/resources/js'; 


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
window.Echo.channel('dashboard.stats')
    .listen('.stats.updated', (e) => {
        window.dispatchEvent(new CustomEvent('stats-updated', {
            detail: e.stats
        }));
    });
