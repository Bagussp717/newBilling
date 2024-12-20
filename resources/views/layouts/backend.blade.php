<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.meta')

    <title>@yield('title') | Billing</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @stack('before-style')

    @include('includes.style')

    @stack('after-style')
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        @include('includes.sidebar')

        <div class="body-wrapper">
            @include('includes.navbar')

            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>

    @stack('before-script')

    @include('includes.script')

    @stack('after-script')
</body>

</html>
