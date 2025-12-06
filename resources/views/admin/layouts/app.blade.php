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

        /* Custom Toastr Styles */
        .toast-success {
            background-color: #17A55A !important;
        }

        .toast-error {
            background-color: #dc3545 !important;
        }

        .toast-info {
            background-color: #17a2b8 !important;
        }

        .toast-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .toast-top-right {
            top: 80px !important;
            right: 20px !important;
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
        .main_content{
            padding:1.042vw;
        }
        #wrapper_mobile_display_none{
            display: none;
            background: #fff;
            background-color: #fff;
            position: fixed;
            top: 0px;
            left:0px;
            width: 100vw;
            height: 100vh;
            z-index: 111111111111111111111111111111111111111111111
            ;
        }
        @media(max-width:768px){
               #wrapper_mobile_display_none{
                display: block;
               }
        }
    </style>
        <style>
#rows, #perPage {
    width: 100%;
    padding: 0.5vw 0.8vw 0.5vw 0.8vw; 
    border: 1px solid #F1F1F1;
    border-radius: 4px;
    background-color: white;

    /* hide default arrow */
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;

    /* custom icon */
    background-image: url("{{ asset('assets/images/icons/icon_.svg') }}");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 6px;
    color: #393939 !important;
    background-clip: padding-box;
}

/* Firefox fix */
#rows::-ms-expand #perPage::-ms-expand {
    display: none;
}
.nav {
    --bs-nav-link-padding-x: 0.1041vw;
    --bs-nav-link-padding-y: 0.0521vw;
}

    </style>
</head>

<body>
    <div id="wrapper_mobile_display_none"></div>

    @include('admin.partials.sidebar')
    <div class="wrapper">
        <!-- ========== MAIN CONTENT ========== -->
        <main class="main">
  
                @include('admin.partials.header')
                <div class="main_content">
                         @yield('content')
                </div>
   


     
        </main>
    </div>

    @include('admin.partials.foot')
    {{-- <livewire:components.toastr-notifier /> --}}
    @stack('scripts')
    @livewireScripts

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JavaScript -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- Dashboard functionality -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <!-- Header functionality fix -->
    <script src="{{ asset('assets/js/header-fix.js') }}"></script>

    <script>
                $(document).ready(function(){
                    $('#filterModal .submit-btn').on('click',function(e){
                        e.preventDefault();
                          $('.filter_active_btna___').css('display','flex');
                    })
                     $('#filterModal .reset-btn').on('click',function(e){
                        e.preventDefault();
                          $('.filter_active_btna___').css('display','none');
                    })
                    $('.filter_active_btna___ .fa-xmark').on('click',function(e){
                          e.preventDefault();
                          $('.filter_active_btna___').css('display','none');
                    })
                })
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

        // Livewire event listeners for SweetAlert2 notifications
        document.addEventListener('livewire:init', () => {
            Livewire.on('showSweetAlert', (data) => {
                console.log('SweetAlert event received:', data);

                // Handle both old and new parameter formats
                let type, message, title;

                if (typeof data === 'object' && data.type) {
                    // New format with named parameters
                    type = data.type;
                    message = data.message;
                    title = data.title;
                } else if (Array.isArray(data) && data.length >= 3) {
                    // Old format with positional parameters
                    type = data[0];
                    message = data[1];
                    title = data[2];
                } else {
                    console.error('Invalid SweetAlert data format:', data);
                    return;
                }

                console.log('Showing SweetAlert:', {
                    type,
                    message,
                    title
                });

                // Map toastr types to SweetAlert2 types
                const alertType = type === 'error' ? 'error' :
                    type === 'warning' ? 'warning' :
                    type === 'info' ? 'info' : 'success';

                Swal.fire({


                    toast: true,
                    background: '#FFFFFF',
                    position: 'top-end',
                    title: title,
                    text: message,
                    icon: alertType,
                    toast: true,
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            });
        });
    </script>

</body>

</html>
