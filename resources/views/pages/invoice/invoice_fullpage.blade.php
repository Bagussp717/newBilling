<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INV{{ $invoice->kd_invoice }} - {{ $invoice->pelanggan->nm_pelanggan }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        * {
            color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
            /* print-color-adjust: exact; */
        }

        body {
            font-family: 'Figtree', sans-serif;
            line-height: 25px;
            width: 793.7107869076px;
            margin: 10mm auto;
            padding-right: 10px;
            padding-left: 10px;
            background-color: #FFF;
            color: #000;
            font-size: 10pt;
        }

        h1 {
            font-size: 22pt;
            font-weight: normal;
        }

        h2 {
            font-size: 20pt;
            font-weight: normal;
        }

        h3 {
            font-size: 18pt;
            font-weight: normal;
        }

        h4 {
            font-size: 16pt;
            font-weight: normal;
        }

        h5 {
            font-size: 14pt;
            font-weight: normal;
        }

        .tabel {
            min-width: 100%;
            border-collapse: collapse;
        }

        .tabel th,
        .tabel td {
            vertical-align: top;
        }

        .tabel th {
            text-align: center;
            font-size: 10pt;
        }

        .tabel td {
            vertical-align: top;
        }

        .tabel-border {
            /* border: 1px solid#000; */
            border-collapse: collapse;
            width: 100%;
        }

        .tabel-border th,
        .tabel-border td {
            vertical-align: top;
            /* border: 1px solid #000000; */
        }

        .tabel-border th {
            text-align: center;
            font-size: 10pt;
        }

        .tabel-border td {
            vertical-align: top;
            padding: 0px 5px 0px 5px;
        }

        @media print {
            input.noPrint {
                display: none;
            }

            .no-print {
                display: none;
            }

            /* Mengatur footer agar berada di bagian bawah */
            .footer {
                position: absolute;
                bottom: 15px;
                width: 793.7107869076px;
                background-color: white;
            }

            /* Garis yang muncul di atas footer */
            .garis-footer {
                position: absolute;
                bottom: 100px;
            }
        }

        @page {
            size: auto;
            /* auto is the initial value */
            margin-right: 18mm;
            /* this affects the margin in the printer settings */
            margin-left: 18mm;
            /* this affects the margin in the printer settings */
            margin-top: 0mm;
            /* this affects the margin in the printer settings */
            margin-bottom: 5mm;
            /* this affects the margin in the printer settings */
        }

        .grid-container1 {
            display: grid;
            grid-template-columns: auto auto;
            grid-gap: 10px;
        }

        .grid-container2 {
            display: grid;
            grid-template-columns: auto auto auto;
            grid-gap: 10px;
            vertical-align: middle;
        }

        .ttd {
            width: 50%;
            text-align: center;
        }

        .ttd_left {
            display: inline-block;
            left: 0px;
            width: 50%;
            text-align: center;
            text-transform: capitalize;
        }

        .ttd_right {
            display: inline-block;
            float: right;
            width: 38%;
            text-align: center;
            margin-top: 50px;
            text-transform: capitalize;
        }

        .ttd label {
            display: block;
            text-transform: capitalize;
        }

        .footer {
            font-size: 10pt;
            line-height: 1.5;
        }

        @media print and (color) {
            .formulir-header {
                color: #FFF !important;
            }

            .data-header {
                color: #FFF !important;
            }
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0px 10px 0px;
        }

        .head h1 {
            margin: 0px;
            font-size: 40pt;
            font-weight: 1000;
        }

        .garis {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0px 10px 0px;
        }


        .garis hr {
            border: 2px solid;
            width: 540px;
        }

        .garis h5 {
            margin: 0px;
            font-size: 10pt;
            font-weight: 600;
        }

        .informasi {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0px 0px 10px 0px;
        }

        .informasi h5 {
            margin: 0px;
            font-size: 13pt;
            font-weight: 600;
        }

        .informasi h3 {
            margin: 0px;
            font-size: 20pt;
            font-weight: 1000;
        }

        .informasi p {
            margin: 0px;
            font-size: 12pt;
            font-weight: 300;
        }

        .footer {
            display: flex;
            align-items: center;
            margin: 0px 0px 10px 0px;
            gap: 10px;
        }

        .content {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
        }

        .icon {
            width: 40px;
            height: 40px;
            color: #68b7e8;
            margin-top: -30px;
        }

        .content p {
            margin: 0;
            font-size: 12pt;
            max-width: 250px;
        }

        .content.center {
            justify-content: center;
            margin: auto;
        }

        .content.right {
            justify-content: flex-end;
            margin-left: auto;
        }

        .btn-primary {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            color: #ffffff;
            background-color: #007bff;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-primary:focus,
        .btn-primary:active {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-success {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            color: #ffffff;
            background-color: #28a745;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-success:focus,
        .btn-success:active {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="no-print">
            <div class="gap-2 d-flex" style="[object Object]">
                <a href="#" class="btn btn-success" id="printButton1">Cetak</a>
            </div>
            <hr>
        </div>
        <div class="head">
            <h2 style="margin: 0px;">
                <div>
                    <img width="200" src="{{ asset('assets/images/LogoMySemesta.png') }}" alt="logo">
                    <img width="200" src="{{ asset('assets/images/logos/amanet.png') }}" alt="logo">
                </div>
            </h2>
            @if ($invoice->pembayaran->sum('jml_bayar') >= $invoice->pelanggan->paket->hrg_paket)
                <h1>KWITANSI</h1>
            @else
                <h1>INVOICE</h1>
            @endif
        </div>
        <div class="garis">
            <div>
                <hr>
            </div>
            <h5>PT. SEMESTA MULTITEKNO INDONESIA</h5>
        </div>
        <div class="informasi">
            <div>
                @if ($invoice->pembayaran->sum('jml_bayar') >= $invoice->pelanggan->paket->hrg_paket)
                    <p>Telah Terima Dari :</p>
                @else
                    <p>Tagihan Kepada :</p>
                @endif
                <h5>{{ $invoice->pelanggan->nm_pelanggan }}</h5>
                <p>{{ $invoice->pelanggan->alamat }}</p>
                <p>Telp. {{ $invoice->pelanggan->no_telp ?? ' - ' }}</p>
            </div>
            <div style="text-align: right;">
                <h5>#INV-{{ $invoice->kd_invoice }}</h5>
                <p>Tanggal Billing :
                    {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->locale('id')->translatedFormat('d F Y') }}</p>
                <P>Jenis Billing : <u>Prabayar</u></P>
                <h4>
                    <strong>
                        STATUS :
                        @if ($invoice->pembayaran->sum('jml_bayar') >= $invoice->pelanggan->paket->hrg_paket)
                            <span style="color: green;">PAID</span>
                        @else
                            <span style="color: red;">UNPAID</span>
                        @endif
                    </strong>
                </h4>
            </div>
        </div>
        <table class="tabel-border highlight-table">
            <thead>
                <tr style="background-color: #6257e3; color: #FFF">
                    <th style="width: 30px">N0</th>
                    <th>DESKRIPSI</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td style="width: 70%;">
                        <p style="margin: 0px; font-size: 12pt; font-weight: 300;">
                            {{ $invoice->pelanggan->paket->nm_paket }}</p>
                        <p style="margin: 0px; font-size: 12pt; font-weight: 300;">
                            (Periode :
                            {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->locale('id')->translatedFormat('d F Y') }}
                            &nbsp; s/d &nbsp;
                            {{ \Carbon\Carbon::now()->endOfMonth()->locale('id')->translatedFormat('d F Y') }})
                        </p>
                    </td>
                    <td style="text-align: right;">Rp
                        {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }},-</td>
                </tr>
                <!-- Add empty row with padding here -->
                <tr>
                    <td colspan="3" style="height: 20px;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 20px;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 20px;"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="background-color: #6257e3; color: #FFF">
                    <th colspan="2" style="text-align: right; padding: 1px; font-size: 12px; border: none;">
                        TOTAL :
                    </th>
                    <th style="text-align: right; font-size: 14px; border: none; padding: 1px; padding-right: 5px;">Rp
                        {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }},-</th>
                </tr>
            </tfoot>

        </table>
        @if ($invoice->pembayaran->sum('jml_bayar') >= $invoice->pelanggan->paket->hrg_paket)
            <div style="margin: 30px 0px 10px 0px;">
                <table style="width: 100%; border: 1px dashed #000; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th
                                style="padding: 8px; font-size: 10pt; font-weight: 600; text-align: center; border-bottom: 1px dashed #000;">
                                TANGGAL PEMBAYARAN
                            </th>
                            <th
                                style="padding: 8px; font-size: 10pt; font-weight: 600; text-align: center; border-bottom: 1px dashed #000;">
                                METODE PEMBAYARAN
                            </th>
                            <th
                                style="padding: 8px; font-size: 10pt; font-weight: 600; text-align: center; border-bottom: 1px dashed #000;">
                                TOTAL
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 12px 10px; font-size: 12pt; font-weight: 400; text-align: center;">
                                {{ \Carbon\Carbon::parse($invoice->pembayaran->max('tgl_bayar'))->locale('id')->translatedFormat('d F Y') }}
                            </td>
                            <td style="padding: 12px 10px; font-size: 12pt; font-weight: 400; text-align: center;">
                                {{ $invoice->pelanggan->loket->nm_loket }}
                            </td>
                            <td style="padding: 12px 10px; font-size: 12pt; font-weight: 400; text-align: center;">
                                Rp {{ number_format($invoice->pembayaran->sum('jml_bayar'), 0, ',', '.') }},-
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div style="margin: 10px 0px 10px 0px;">
                <table>
                    <thead>
                        <tr style="background-color: #6257e3; color: #FFF">
                            <th style="margin: 10px 0px 10px 0px; font-size: 12pt; font-weight: 600; text-align: left">
                                METODE PEMBAYARAN :
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding-right: 20px;">
                                <p style="margin: 0px; font-size: 12pt; font-weight: 600;">BANK BRI</p>
                                <p style="margin: 0px; font-size: 12pt; font-weight: 300;">
                                    No. Rekening : 057701001180565
                                </p>
                                <p style="margin: 0px; font-size: 12pt; font-weight: 300;">a.n PT. Semesta Multitekno
                                    Indonesia</p>
                            </td>
                            <td style="padding-left: 20px;">
                                <p style="margin: 0px; font-size: 12pt; font-weight: 600;">BANK JATIM</p>
                                <p style="margin: 0px; font-size: 12pt; font-weight: 300;">No. Rekening 0023056470
                                </p>
                                <p style="margin: 0px; font-size: 12pt; font-weight: 300;">a.n PT. Semesta Multitekno
                                    Indonesia</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr style="border: 2px solid #b9b9b9; width: 250px; margin-left: 0px;">
        @endif
        <div>
            <div class="ttd_right">
                @if ($invoice->pembayaran->sum('jml_bayar') >= $invoice->pelanggan->paket->hrg_paket)
                    <p style="margin: 0px; font-size: 12pt; font-weight: 300;">Banyuwangi,
                        {{ \Carbon\Carbon::parse($invoice->pembayaran->max('tgl_bayar'))->locale('id')->translatedFormat('d F Y') }}
                    </p>
                @else
                    <p style="margin: 0px; font-size: 12pt; font-weight: 300;">Banyuwangi,
                        {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->locale('id')->translatedFormat('d F Y') }}
                    </p>
                @endif
                <p style="margin: 0px; font-size: 12pt; font-weight: 600;">PT. SEMESTA MULTITEKNO INDONESIA</p>
                <div style="position: relative;">
                    <!-- Stempel -->
                    <img src="{{ asset('assets/images/stampel.png') }}" width="200" alt="stampel"
                        style="position: absolute; opacity: 0.7; right: 50px;">
                    <!-- Tanda Tangan -->
                    <img src="{{ asset('assets/images/erma.png') }}" width="100" alt="ttd" style="">
                </div>
                <p style="margin: 0px; font-size: 12pt; font-weight: 600;">ERMA KHOIRUN NISA', A.Md</p>
                <div style="border-bottom: 2px solid #000; width: 70%; margin: 0 auto;"></div>
                <p style="margin: 0px; font-size: 12pt; font-weight: 600;">Finance</p>
            </div>
        </div>
        <div style="clear: both;"></div>
        <hr class="garis-footer" style="border: #000 solid 2px; width:  793.7107869076px; margin: 10px 0px 10px 0px;">
        <div class="footer">
            <div class="content">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                    <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                </svg>
                <p>
                    Perum GPM Blok Rambutan No.8 Genteng-Banyuwangi, Jawa Timur Indonesia 68465
                </p>
            </div>
            <div class="content center">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                </svg>
                <p style="margin-top: -40px;">
                    081-5527-8988
                </p>
            </div>
            <div class="content right">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                    <path d="M3 7l9 6l9 -6" />
                </svg>
                <p style="margin-top: -40px; margin-right: 8px;">
                    info@semesta.co.id
                </p>
            </div>
        </div>

        <div class="no-print">
            <hr>
            <div class="gap-2 d-flex">
                <a href="#" class="btn btn-success" id="printButton2">Cetak</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('printButton1').addEventListener('click', function() {
            window.print(); // Memanggil fungsi pencetakan bawaan browser saat tombol cetak ditekan
        });

        document.getElementById('printButton2').addEventListener('click', function() {
            window.print(); // Memanggil fungsi pencetakan bawaan browser saat tombol cetak ditekan
        });
    </script>
</body>

</html>
