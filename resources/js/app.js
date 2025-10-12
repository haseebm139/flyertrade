import './echo.js';

function showToast(message) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.innerText = message;
  toast.style.display = 'block';
  setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
window.showToast = showToast;

const attach = () => {
  if (!window.Echo) {
    console.log('[app] Echo missing');
    return;
  }
  console.log('[app] Echo ready');

  const ch = window.Echo.channel('notifications');

  ch.subscribed(() => console.log('Subscribed to notifications'));

  // Your explicit broadcastAs name
  ch.listen('.create', e => {
    console.log('[event] .create', e);
    showToast(e.message);
  });

  // Class-based names (catch-all)
  ch.listen('UserNotification', e => {
    console.log('[event] UserNotification', e);
    showToast(e.message);
  });

  ch.listen('App\\Events\\UserNotification', e => {
    console.log('[event] App\\Events\\UserNotification', e);
    showToast(e.message);
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', attach);
} else {
  attach();
}