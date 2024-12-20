@extends('layouts.invoice_full')

@section('title', 'Invoice')

@section('content')
    <div class="p-2">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-5">
                <img src="{{ asset('assets/images/logos/logo_invoice.png') }}" alt="Logo" width="250">
                <p style="margin-top: 5%"><strong>OFFICE:</strong></p>
                <p>Perum GPM Blok Rambutan No. 8<br> Genteng, Banyuwangi - Jawa Timur, 68465<br> Telepon: 081 216 416 437
                </p>
            </div>
            <div class="col-7 text-end">
                <h1 class="invoice-title">INVOICE</h1>
                <br>
                <table class="table table-bordered mt-16" style="background-color: #ffffff; table-layout: auto;">
                    <thead style="background-color: #2e2e2e; color: #fff;">
                        <tr>
                            <th><strong>INVOICE #</strong></th>
                            <th><strong>TANGGAL</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>INV-240{{ $invoice->pelanggan->kd_pelanggan }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->tgl_akhir)->locale('id')->translatedFormat('d F Y') }}                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <p class="text-end"><strong>Jenis Billing :</strong> <a href="#">Prabayar</a></p>
            </div>
        </div>

        <!-- Payment Status Section -->
        <div class="mt-4 row">
            <div class="col-12">
                <h3><strong>STATUS:</strong>
                    @if ($invoice->pembayaran->sum('jml_bayar') > 0)
                        <span style="color: green;">PAID</span>
                    @else
                        <span style="color: red;">UNPAID</span>
                    @endif
                </h3>
                @if ($invoice->pembayaran->sum('jml_bayar') > 0)
                    <p>Invoice Date Paid:
                        {{ \Carbon\Carbon::parse($invoice->pembayaran->first()->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}</p>
                @else
                @endif
            </div>
        </div>

        <!-- Customer Info Section -->
        <div class="row mb-1">
            <div class="col-12">
                <th style="width: 50%;"><strong>Kepada Yth:</strong></th>
                </tr>
                <p>{{ $invoice->pelanggan->nm_pelanggan }}<br>{{ $invoice->pelanggan->alamat }}</p>
            </div>
        </div>

        <!-- Invoice Details Section -->
        <table class="table table-bordered">
            <thead style="background-color: #2e2e2e; color:#ffff">
                <tr>
                    <th>DESKRIPSI</th>
                    <th class="text-end">JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 20px;"> {{ $invoice->pelanggan->paket->nm_paket }} <br>
                        ( Periode : {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->locale('id')->translatedFormat('d F Y') }} &nbsp s/d &nbsp {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->locale('id')->translatedFormat('d F Y') }} )
                        <p style="height: 80px"></p>
                        <ul>
                            <li ><strong>Pembayaran Via Bank Transfer</strong></li>
                            <li><strong>BANK BRI</strong><br>
                                No. Rekening 05770100118565<br>
                                a.n PT. Semesta Multitekno Indonesia
                            </li>
                            <li><strong>BANK JATIM</strong><br>
                                No. Rekening 0290458470<br>
                                a.n PT. Semesta Multitekno Indonesia
                            </li>
                        </ul>
                    </td>
                    <td style="padding: 20px;" class="text-end">Rp {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding:20px;">
                        <p><strong>Total</strong></p>
                    </td>
                    <td style="padding:20px;">
                        <p class="text-end"><strong>Rp
                                {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}</strong></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <ul>
            <li>Sesuai dengan ketentuan yang berlaku, PT. Semesta Multitekno Indonesia mengatur bahwa dokumen ini telah
                ditandatangani
                secara elektronik sehingga tidak diperlukan tanda tangan basah pada dokumen ini.</li>
        </ul>

        <!-- Footer Section -->
        <div class="footer-signature">
            <p>Banyuwangi, {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->locale('id')->translatedFormat('d F Y') }}
                <br>
                PT. SEMESTA MULTITEKNO INDONESIA</p>
                <br>
                <br>
            <p>Erma Khoirun Nisa, A.Md</p>
        </div>

        <!-- Footer Section -->
        <div class="text-center mt-3">
            <p style="margin-top: 2%">www.semesta.co.id | NIB : 0277010100057 | NPWP: 80.586.086.3-627.000</p>
        </div>
        <!-- Print Button -->
        <div class="text-center mt-3">
            <button onclick="window.print()" class="btn btn-primary" id="print-btn">Print Invoice</button>
        </div>
    </div>

    <style>
        @media print {
            #print-btn {
                display: none;
            }
        }

        @page {
            size: A4;
            margin-left: 25px;
            margin-right: 25px;
            margin-top: 25px;
            margin-bottom: 0px;
            margin: 25;
            -webkit-print-color-adjust: exact;
        }
    </style>
@endsection
