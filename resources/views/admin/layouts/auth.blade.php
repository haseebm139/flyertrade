<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/authentication-style.css') }}">

    <link href="https://fonts.cdnfonts.com/css/clash-display" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <title>{{ $title ?? 'Auth' }}</title>
    @livewireStyles
</head>

<body>


    <div class="section">
        <div class="container-fluid h-100">
            <div class="row row-about">
                <!-- Left Image -->
                <div class="col-md-6 left-img">
                    <img src="{{ asset('assets/images/icons/authentication-foam-image.svg') }}"
                        alt="Authentication Illustration" loading="lazy">
                </div>

                <!-- Right Login Form -->
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <div class="login-form-wrapper">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        // Toggle password visibility
        document.querySelectorAll(".toggle-password").forEach(icon => {
            icon.addEventListener("click", () => {
                const targetId = icon.getAttribute("data-target");
                const input = document.getElementById(targetId);
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });
        });
    </script>
    <livewire:components.swal-handler />
    @livewireScripts
</body>

</html>
