import './echo.js';

console.log('[app] loaded');

if (window.Echo) {
  window.Echo.channel('notifications')
    .subscribed(() => console.log('Subscribed to notifications'))
    .listen('.create', e => {
      console.log('Incoming event:', e);
      if (typeof window.showToast === 'function') {
        window.showToast(e.message);
      }
    });
} else {
  console.log('Echo missing');
}