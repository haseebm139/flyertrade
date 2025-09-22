<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Realtime Notifications</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <style>
    #toast {
      display: none; position: fixed; bottom: 20px; right: 20px;
      background: #16a34a; color: #fff; padding: 12px 18px; border-radius: 6px;
    }
  </style>
  @vite('resources/js/app.js')
</head>
<body>
  <h2>Realtime Notification Demo</h2>
  <button id="notifyBtn">Click Me</button>

  <div id="toast"></div>

  <script>
    $('#notifyBtn').on('click', function () {
      $.post("{{ route('notifications.send') }}", {
        _token: document.querySelector('meta[name="csrf-token"]').content
      });
    });
  </script>
</body>
</html>