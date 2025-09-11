<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <link rel="icon" type="image/png" href="{{ asset('assets/logos/favicon.ico') }}">

    <title>@yield('title', 'Flyertrade Admin Dashboard')</title>
    @include('admin.partials.head')
    @stack('styles')
    @livewireStyles
    <style>
        div:where(.swal2-icon).swal2-success [class^=swal2-success-line] {
            background-color: #17A55A !important;
        }

        /* Custom success animation */
        .swal2-success-circular-line {
            border-radius: 50%;
            position: absolute;
            width: 60px;
            height: 120px;
            transform: rotate(45deg);
        }

        .swal2-success-circular-line-left {
            border-radius: 120px 0 0 120px;
            top: -7px;
            left: -33px;
            transform: rotate(-45deg);
            transform-origin: 60px 60px;
            background-color: #17A55A;
        }

        .swal2-success-circular-line-right {
            border-radius: 0 120px 120px 0;
            top: -11px;
            left: 30px;
            transform: rotate(-45deg);
            transform-origin: 0 60px;
            background-color: #17A55A;
        }

        .swal2-success-fix {
            background-color: white;
            position: absolute;
            height: 7px;
            transform: rotate(-45deg);
        }

        .swal2-success-line-tip,
        .swal2-success-line-long {
            background-color: #17A55A;
            border-radius: 2px;
            position: absolute;
            z-index: 2;
        }

        .swal2-success-line-tip {
            height: 5px;
            left: 14px;
            top: 46px;
            transform: rotate(45deg);
            width: 25px;
        }

        .swal2-success-line-long {
            height: 5px;
            right: 8px;
            top: 38px;
            transform: rotate(-45deg);
            width: 47px;
        }
    </style>
</head>

<body>

    @include('admin.partials.sidebar')
    <div class="wrapper">
        <!-- ========== MAIN CONTENT ========== -->
        <main class="main">
            <div class="container">
                @include('admin.partials.header')

                @yield('content')


            </div>
        </main>
    </div>

    @include('admin.partials.foot')
    {{-- <livewire:components.toastr-notifier /> --}}
    @stack('scripts')
    @livewireScripts

    <script>
        // Swal.fire({
        //     title: 'Success!',
        //     position: 'top-end',
        //     text: 'Service category deleted successfully.',
        //     icon: 'success',

        //     toast: true,
        //     background: '#FFFFFF',
        //     position: 'top-end',






        //     customClass: {
        //         icon: 'swal2-success-animate'
        //     },


        // });

        // document.addEventListener('livewire:init', () => {
        //     Livewire.on('toastrNotification', (data) => {
        //         // console.log("Toastr data:", data);

        //         // unwrap if Livewire wraps in array
        //         if (Array.isArray(data) && data.length > 0) {
        //             data = data[0];
        //         }

        //         const validTypes = ['success', 'error', 'info', 'warning'];
        //         const type = validTypes.includes(data.type) ? data.type : 'info';

        //         // toastr[type](data.message, data.title);


        //         Swal.fire({
        //             toast: true,
        //             background: '#FFFFFF',
        //             position: 'top-end',
        //             icon: data.type, // success | error | info | warning
        //             title: data.title,
        //             text: data.message,
        //             showConfirmButton: false,

        //             timerProgressBar: false,
        //         });
        //     });
        // });
    </script>

</body>

</html>
