<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.meta')

    <title>@yield('title') | Billing</title>

    @stack('before-style')

    @include('includes.style')

    @stack('after-style')
</head>

<body>
    <div>
        @yield('content')
    </div>

    </div>

    @stack('before-script')

    @include('includes.script')

    @stack('after-script')
</body>

</html>
