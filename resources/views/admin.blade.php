<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('customer/css/bootstrap.min.css') }}" />

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="{{ asset('customer/css/slick.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('customer/css/slick-theme.css') }}" />

    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="{{ asset('customer/css/nouislider.min.css') }}" />

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('customer/css/font-awesome.min.css') }}">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('customer/css/style.css') }}" />
    <!-- jQuery Plugins -->
    <script src="{{ asset('customer/js/jquery.min.js') }}"></script>
    <script src="{{ asset('customer/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('customer/js/slick.min.js') }}"></script>
    <script src="{{ asset('customer/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('customer/js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('customer/js/main.js') }}"></script>
    <title>Document</title>
    @vite(['resources/js/app.js', 'resources/sass/app.scss', 'public/css/all.css'])
    <script>
        (function() {
            window.Laravel = {
                csrfToken: '{{ csrf_token() }}'
            };
        })();
    </script>
</head>

<body>
    <div id="app">
        @if (Auth::guard('admin')->check())
            <!-- Admin Sidebar -->
            <mainapp :user="{{ Auth::guard('admin')->user() }}"></mainapp>
        @else
            <!-- Customer Navbar -->
            <navbar :user="{{ Auth::guard('customer')->check() ? Auth::guard('customer')->user() : 'false' }}"></navbar>
        </router-view>
        @endif
    </div>
    

</body>

</html>
