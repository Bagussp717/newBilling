<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.mikrotik.meta')

    <title>@yield('title') | Mikrotik</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @stack('before-style')

    @include('includes.mikrotik.style')

    @stack('after-style')
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        @include('includes.mikrotik.sidebar')

        <div class="body-wrapper">
            @include('includes.mikrotik.navbar')

            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>

    @stack('before-script')

    @include('includes.mikrotik.script')

    @stack('after-script')
</body>

</html>
