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

    

// document.addEventListener("DOMContentLoaded", () => {
//   document.querySelectorAll('.scrollbar-auto-hide').forEach(el => {
//     let timeout;
//     el.addEventListener('scroll', () => {
//       el.classList.add('scrolling');
//       clearTimeout(timeout);
//       timeout = setTimeout(() => {
//         el.classList.remove('scrolling');
//       }, 800); // hide after 0.8s idle
//     });
//   });
// });
