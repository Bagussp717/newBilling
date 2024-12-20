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
        body {
            font-size: 12px;
            /* Mengatur ukuran font dasar untuk seluruh halaman */
        }

        .border p,
        .border ul,
        .border table,
        .border th,
        .border td {
            font-size: 12px;
            /* Mengurangi ukuran font pada semua elemen teks di dalam border */
        }

        .table th,
        .table td {
            font-size: 12px;
            padding: 8px;
            /* Mengatur ukuran font di dalam tabel */
        }

        .text-end {
            font-size: 12px;
            /* Mengatur ukuran font khusus untuk elemen dengan kelas text-end */
        }

        .text-center {
            font-size: 12px;
            /* Mengatur ukuran font khusus untuk elemen dengan kelas text-center */
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
