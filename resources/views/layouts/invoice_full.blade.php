<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.meta')

    <title>@yield('title') | Billing</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @stack('before-style')

    @include('includes.style')

    @stack('after-style')

    <style>
        .invoice-header {
            font-size: 14px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
        }

        .footer-signature {
            text-align: right;
            margin-top: 10px;
        }

        .footer-signature img {
            height: 50px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table thead {
                background-color: #2e2e2e !important;
                /* Pastikan warna diterapkan saat mencetak */
                color: #fff !important;
            }
        }
    </style>

</head>

<body>


    @yield('content')


    @stack('before-script')

    @include('includes.script')

    @stack('after-script')
</body>

</html>
