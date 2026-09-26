import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const isSecure = window.location.protocol === 'https:';
const configuredPort = Number(import.meta.env.VITE_REVERB_PORT || 0);
const port = configuredPort || (isSecure ? 443 : 8080);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'tambora-realtime',
    wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
    wsPort: port,
    wssPort: port,
    forceTLS: isSecure,
    enabledTransports: ['ws', 'wss'],
});

window.dispatchEvent(new CustomEvent('TamboraEchoLoaded'));
