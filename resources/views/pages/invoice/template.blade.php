@extends('layouts.invoice_full')

@section('title', 'Invoice')

@section('content')

    <div class="p-2">
        <!-- Header Section -->
        <div class="row">
            <div class="col-6">
                <img src="{{ asset('assets/images/logos/LogoMySemesta.png') }}" alt="Logo" width="110">
                <img src="{{ asset('assets/images/logos/amanet.png') }}" alt="Logo" width="110" class="ms-2">
                <p><strong>Support ISP:</strong></p>
                <p class="m-0 mt-2"> <strong>OFFICE :</strong></p>
                <p>Perum GPM Blok Rambutan No. 8<br> Genteng, Banyuwangi - Jawa Timur, 68465<br> Telepon: 081 216 416 437
                </p>
            </div>
            <div class="col-6 text-end">
                <h1 class="mb-5 invoice-title">INVOICE</h1>
                <p class="mt-2 mb-0"><strong>INVOICE #:</strong> INV-24078</p>
                <p><strong>TANGGAL:</strong> 01 Januari 2025</p>
            </div>
        </div>

        <!-- Detail Section -->
        <div class="mt-4 row">
            <div class="col-6">
                <p class="m-0"><strong>Kepada Yth:</strong></p>
                <p>Pelanggan Semesta Multitekno</p>
            </div>

        </div>

        <!-- Invoice Details Section -->
        <table class="table mt-3 table-bordered">
            <thead style="background-color: #2e2e2e; color:#ffff">
                <tr>
                    <th>DESKRIPSI</th>
                    <th class="text-end">JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>INTERNET PENDIDIKAN 20Mbps SDN 2 Sumbergondo</td>
                    <td class="text-end">Rp 400.000,-</td>
                </tr>
                <tr style="height: 300px">
                    <td style="vertical-align: bottom;">
                        <!-- Payment Info Section -->
                        <p><strong>Pembayaran Via Bank Transfer</strong></p>
                        <ul>
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
                    <td>
                        {{-- Jangan diisi --}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Total</strong></p>
                    </td>
                    <td>
                        <p class="text-end"><strong>Rp 400.000,-</strong></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h5 class="text-center"><em><strong>Terbilang: Empat Ratus Ribu Rupiah</strong></em></h5>
                    </td>
                </tr>
            </tbody>
        </table>



        <!-- Footer Section -->
        <div class="footer-signature">
            <p>Banyuwangi, 1 September 2024<br>
                PT. SEMESTA MULTITEKNO INDONESIA</p>
            <img src="signature.png" alt="Signature">
            <p>Erma Khoirun Nisa’, A.Md</p>
        </div>
    </div>

@endsection
