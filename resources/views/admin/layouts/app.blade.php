<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <title>@yield('title', 'Flyertrade Admin Dashboard')</title>
    @include('admin.partials.head')
    @stack('styles')
    @livewireStyles
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
    @stack('scripts')
    @livewireScripts

</body>

</html>
