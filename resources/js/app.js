import './echo.js';

function showToast(message) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.innerText = message;
  toast.style.display = 'block';
  setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
window.showToast = showToast;

(function waitForEcho(attempt = 0) {
  if (window.Echo) {
    console.log('[app] Echo ready');
    window.Echo.channel('notifications')
      .subscribed(() => console.log('Subscribed to notifications'))
      .listen('.create', e => {
        console.log('Incoming event:', e);
        showToast(e.message);
      });
  } else if (attempt < 50) {
    setTimeout(() => waitForEcho(attempt + 1), 100);
  } else {
    console.log('Echo not ready');
  }
})();