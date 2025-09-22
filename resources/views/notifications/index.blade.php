<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Realtime Notifications</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        #toast {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #16a34a;
            color: #fff;
            padding: 12px 18px;
            border-radius: 6px;
        }
    </style>
    @vite('resources/js/app.js')
</head>

<body>
    <h2>Realtime Notification Demo</h2>
    <button id="notifyBtn">Click Me</button>

    <div id="toast"></div>

    <script>
        $('#notifyBtn').click(function() {
            $.post("{{ route('notifications.send') }}", {
                _token: $('meta[name="csrf-token"]').attr('content')
            });
        });

        function showToast(message) {
            let toast = document.getElementById("toast");
            toast.innerText = message;
            toast.style.display = "block";
            setTimeout(() => {
                toast.style.display = "none";
            }, 3000);
        }
    </script>    

    <script type="module">
        window.Echo.channel('notifications')
            .subscribed(() => console.log('Subscribed to notifications'))
            .listen('.create', (e) => {
                console.log('Incoming event:', e);
                showToast(e.message);
            });
    </script>
</body>

</html>
