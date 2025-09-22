import './echo.js';
if (window.Echo) {
  window.Echo.channel('notifications')
    .subscribed(() => console.log('Subscribed to notifications'))
    .listen('.create', e => {
      console.log('Incoming event:', e);
      // calls your Blade function
      if (typeof window.showToast === 'function') {
        window.showToast(e.message);
      }
    });
} else {
  console.log('Echo missing');
}