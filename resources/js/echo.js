import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const scheme = (import.meta.env.VITE_REVERB_SCHEME || 'https');

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: 80,
  wssPort: 443,
  forceTLS: scheme === 'https',
  enabledTransports: ['ws', 'wss'],
});