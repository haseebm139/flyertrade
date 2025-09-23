<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Reverb Event Debugger</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  @vite('resources/js/app.js')
  <style>
    body { font-family: system-ui, Arial, sans-serif; padding: 16px; }
    input { padding: 6px 8px; margin-right: 6px; }
    button { padding: 6px 12px; }
    .row { margin-bottom: 10px; }
    pre { background:#111;color:#eee;padding:10px;border-radius:6px;overflow:auto;max-height:300px }
  </style>
</head>
<body>
  <h3>Reverb Event Debugger</h3>

  <div class="row">
    <input id="userId" placeholder="userId for private-user.{id}">
    <input id="convId" placeholder="conversationId for private-conversation.{id}">
    <input id="bookingId" placeholder="bookingId for booking.{id}">
    <button id="subscribeBtn">Subscribe</button>
    <button id="subNotifBtn">Subscribe notifications</button>
  </div>

  <div class="row">
    <button id="testApiBtn">Hit test API (notifications.send)</button>
  </div>

  <div class="row">
    <strong>Last event:</strong>
    <pre id="log"></pre>
  </div>

  <script>
    toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-bottom-right', timeOut: 3000 };

    function logEvent(name, data) {
      console.log('[EVENT]', name, data);
      document.getElementById('log').textContent = JSON.stringify({ name, data }, null, 2);
      toastr.success(name);
    }

    function subscribeChannel(name, type='public') {
      if (!window.Echo) { toastr.error('Echo missing'); return; }
      let ch;
      if (type === 'private') ch = window.Echo.private(name);
      else if (type === 'presence') ch = window.Echo.join(name);
      else ch = window.Echo.channel(name);

      // Bind some known events explicitly (optional)
      ['.create','chat.message.sent','chat.attachment.sent','offer.created','offer.countered','offer.bargained','offer.accepted','offer.declined','offer.finalized','booking.status.updated']
        .forEach(ev => ch.listen(ev, e => logEvent(ev, e)));

      toastr.info('Subscribed: ' + name);
      return ch;
    }

    // Global catch-all (Pusher) – logs ANY event on any subscribed channel
    (function bindGlobal(){
      if (!window.Echo?.connector?.pusher) return;
      const p = window.Echo.connector.pusher;
      p.bind_global((eventName, data) => {
        // Ignore pusher internal events
        if (String(eventName).startsWith('pusher:')) return;
        logEvent(eventName, data);
      });
      p.connection.bind('state_change', s => console.log('[state]', s));
      p.connection.bind('error', e => console.log('[ws error]', e));
    })();

    // Buttons
    $('#subscribeBtn').on('click', () => {
      const userId = $('#userId').val().trim();
      const convId = $('#convId').val().trim();
      const bookingId = $('#bookingId').val().trim();

      if (userId) subscribeChannel(`private-user.${userId}`, 'private');
      if (convId) subscribeChannel(`private-conversation.${convId}`, 'private');
      if (bookingId) subscribeChannel(`booking.${bookingId}`, 'private');
    });

    $('#subNotifBtn').on('click', () => {
      subscribeChannel('notifications', 'public');
    });

    $('#testApiBtn').on('click', () => {
      $.post("{{ route('notifications.send') }}", {
        _token: document.querySelector('meta[name=\"csrf-token\"]').content
      })
      .done(res => { toastr.success('API OK'); console.log(res); })
      .fail(x => { toastr.error('API error'); console.error(x.responseText); });
    });
  </script>
</body>
</html>