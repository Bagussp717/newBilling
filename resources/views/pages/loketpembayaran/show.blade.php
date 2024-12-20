@extends('layouts.backend')

@section('title', 'Data Invoice')

@section('content')
    <div class="row">
        <div id="preloader">
            <div id="loader"></div>
        </div>
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Invoice</h5>
                </div>
                <div class="p-4 card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card" style="background-color: #ccffcc">
                                <div class="card-body">
                                    <p class="mb-1 fw-semibold">Total Bayar</p>
                                    <h5 class="mb-1 fw-semibold">
                                        {{ 'Rp ' . number_format($totalBayar, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="background-color: #cceeff">
                                <div class="card-body">
                                    <p class="mb-1 fw-semibold">Komisi</p>
                                    <h5 class="mb-1 fw-semibold">
                                        {{ 'Rp ' . number_format($jml_komisi, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="background-color: #e5ccff">
                                <div class="card-body">
                                    <p class="mb-1 fw-semibold">Total Setoran</p>
                                    <h5 class="mb-1 fw-semibold">
                                        {{ 'Rp ' . number_format($totalBayar - $jml_komisi, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <a class="mb-4 btn btn-sm btn-success"
                            href="{{ route('cetakDaftarTagihan.invoice', ['kd_loket' => $kd_loket, 'tgl_invoice' => $tgl_invoice]) }}">
                            Cetak Daftar Tagihan
                        </a>
                        <a href="{{ route('cetakAllInvoice.invoice', ['kd_loket' => $kd_loket, 'tgl_invoice' => $tgl_invoice]) }}"
                            class="mb-4 btn btn-sm btn-warning">Cetak Semua Invoice</a>
                    </div>
                    @role('super-admin|isp')
                        <a href="{{ route('loketPembayaran.index') }}" class="mb-4 btn btn-danger">Kembali</a>
                    @endrole
                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTableloket"
                            style=" width: 100%;
                                                min-width: 0;
                                                max-width: 200px;
                                                white-space: nowrap;">
                            <thead class="mb-0 align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold" style="width: 20px">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">User Mikrotik</h6>
                                    </th>
                                    {{-- <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Nama</h6>
                                    </th> --}}
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Status Mikrotik</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Tanggal Invoice</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Paket</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rowNumber = 1;
                                @endphp

                                @foreach ($invoices as $value => $invoice)
                                    @if ($invoice->status_pppoe != 'Gratis' && optional($invoice->pelanggan->paket)->keterangan !== 'uji coba')
                                        <!-- Kondisi untuk menyembunyikan status "Gratis" -->
                                        <tr class="clickable-row hoverable-row"
                                            data-href="{{ route('pembayaran.create', Crypt::encrypt($invoice->kd_invoice)) }}">
                                            <!-- Only change the "No" column value -->
                                            <td class="align-middle border-bottom-0">
                                                <h6 class="mb-0 fw-normal">{{ $rowNumber }}</h6>
                                            </td>
                                            @php
                                                $totalPembayaran = $invoice->pembayaran->sum('jml_bayar');
                                                $hrgPaket = optional($invoice->pelanggan->paket)->hrg_paket ?? 0;
                                                $badgeClass = 'badge-primary';

                                                if ($totalPembayaran == $hrgPaket) {
                                                    $badgeClass = 'badge-success';
                                                } elseif ($totalPembayaran < $hrgPaket && $totalPembayaran > 0) {
                                                    $badgeClass = 'badge-warning';
                                                } elseif ($totalPembayaran <= 0) {
                                                    $badgeClass = 'badge-danger';
                                                }
                                            @endphp
                                            <td class="align-middle border-bottom-0">
                                                <div class="d-flex align-items-center justify-center gap-2">
                                                    <div>
                                                        <p class="mb-0 fw-normal">{{ $invoice->pelanggan->username_pppoe }}
                                                        </p>
                                                        <span class="badge {{ $badgeClass }}">
                                                            @if ($totalPembayaran > 0)
                                                                Rp {{ number_format($totalPembayaran) }}
                                                            @else
                                                                Belum Bayar
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <span class="separator"></span>
                                                    <div class="d-block d-sm-none">
                                                        <a href="{{ route('pembayaran.create', Crypt::encrypt($invoice->kd_invoice)) }}"
                                                            title="tambah" data-bs-toggle="tooltip" class="btn btn-primary">
                                                            Tambah Pembayaran
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                @if ($invoice->status_pppoe == 'isolir')
                                                    <span class="badge badge-danger">Isolir</span>
                                                @else
                                                    <p class="mb-0 fw-normal badge badge-success">
                                                        {{ $invoice->status_pppoe }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">
                                                    {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->format('d M Y') }}
                                                </p>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-semibold badge badge-success">
                                                    Rp {{ number_format($hrgPaket) }}
                                                </p>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <div class="gap-2 d-flex align-items-center justify-content-center">
                                                    @role('isp|super-admin')
                                                        <a href="{{ route('invoice.full', Crypt::encryptString($invoice->kd_invoice)) }}"
                                                            title="invoice 1" data-bs-toggle="tooltip" class="trigger-print">
                                                            <span><i style="font-size: 20px;"
                                                                    class="text-secondary ti ti-printer"></i></span>
                                                        </a>
                                                        <a href="{{ route('invoice.small', Crypt::encryptString($invoice->kd_invoice)) }}"
                                                            title="invoice 2" data-bs-toggle="tooltip" class="trigger-print">
                                                            <span><i style="font-size: 20px;"
                                                                    class="text-danger ti ti-layers-intersect"></i></span>
                                                        </a>
                                                    @endrole

                                                    @if ($totalPembayaran <= 0)
                                                        <!-- Tombol Isolir -->
                                                        @if ($invoice->status_pppoe == 'isolir')
                                                            <a href="{{ route('invoice.pulihkan', $invoice->kd_pelanggan) }}"
                                                                title="Pulihkan" data-bs-toggle="tooltip" id="pulihkanBtn"
                                                                onclick="toggleButton()">
                                                                <span><i style="font-size: 20px;"
                                                                        class="text-secondary ti ti-lock-open"></i></span>
                                                            </a>

                                                            <!-- Tombol Isolir tidak muncul karena statusnya isolir -->
                                                        @else
                                                            <!-- Tombol Isolir (default) -->
                                                            <a href="{{ route('invoice.isolir', $invoice->kd_pelanggan) }}"
                                                                title="Isolir" data-bs-toggle="tooltip" id="isolirBtn"
                                                                onclick="toggleButton()">
                                                                <span><i style="font-size: 20px;"
                                                                        class="text-danger ti ti-cloud-lock"></i></span>
                                                            </a>

                                                            <!-- Tombol Pulihkan (awalnya disembunyikan) -->
                                                            <a href="{{ route('invoice.pulihkan', $invoice->kd_pelanggan) }}"
                                                                title="Pulihkan" data-bs-toggle="tooltip"
                                                                id="pulihkanBtn" onclick="toggleButton()"
                                                                style="display: none;">
                                                                <span><i style="font-size: 20px;"
                                                                        class="text-secondary ti ti-lock-open"></i></span>
                                                            </a>
                                                        @endif
                                                    @endif
                                                    <div class="d-none d-md-block">
                                                        <a href="{{ route('pembayaran.create', Crypt::encrypt($invoice->kd_invoice)) }}"
                                                            title="tambah" data-bs-toggle="tooltip">
                                                            <span><i style="font-size: 20px;"
                                                                    class="text-blue ti ti-square-plus"></i></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <script type="text/javascript">
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        // Function to check if the device is a laptop or tablet
                                                        function isDesktopOrTablet() {
                                                            // Check if the screen width is greater than 768px (commonly used breakpoint)
                                                            return window.innerWidth > 768;
                                                        }

                                                        // Function to handle removing the .trigger-print class on mobile devices
                                                        function handleMobileBehavior() {
                                                            if (!isDesktopOrTablet()) {
                                                                // Select all elements with the 'trigger-print' class
                                                                const printButtons = document.querySelectorAll('.trigger-print');

                                                                // Remove the class from each element
                                                                printButtons.forEach(function(button) {
                                                                    button.classList.remove('trigger-print');
                                                                });
                                                            }
                                                        }

                                                        // Initial check on page load
                                                        handleMobileBehavior();

                                                        // Re-check if the window is resized (optional)
                                                        window.addEventListener('resize', handleMobileBehavior);

                                                        // Proceed with print functionality only if on laptop or tablet
                                                        if (isDesktopOrTablet()) {
                                                            // Select all elements with the 'trigger-print' class
                                                            const printButtons = document.querySelectorAll('.trigger-print');

                                                            printButtons.forEach(function(button) {
                                                                button.addEventListener('click', function(event) {
                                                                    event.preventDefault(); // Prevent default link behavior

                                                                    // Get the URL directly from the href attribute
                                                                    const url = this.getAttribute('href');

                                                                    // Open the print page in a new window/tab
                                                                    const printWindow = window.open(url, '_blank');

                                                                    // Check if the new window opened successfully
                                                                    if (printWindow) {
                                                                        // Trigger the print dialog once the content is loaded
                                                                        printWindow.onload = function() {
                                                                            printWindow.print();

                                                                            // Close the window after printing
                                                                            printWindow.onafterprint = function() {
                                                                                printWindow.close();
                                                                            };
                                                                        };
                                                                    } else {
                                                                        console.error(
                                                                            "Failed to open the print window. Please ensure pop-ups are enabled."
                                                                        );
                                                                    }
                                                                });
                                                            });
                                                        }
                                                    });
                                                </script>

                                                <!-- Script to highlight row by username parameter -->
                                                <script type="text/javascript">
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const urlParams = new URLSearchParams(window.location.search);
                                                        const username = urlParams.get('username');

                                                        if (username) {
                                                            // Find the table row corresponding to the specific username
                                                            const tableRows = document.querySelectorAll('#dataTableloket tbody tr');
                                                            tableRows.forEach(function(row) {
                                                                const usernameCell = row.querySelector('td:nth-child(2) p');
                                                                if (usernameCell && usernameCell.textContent.trim() === username) {
                                                                    // Scroll the row into view and highlight it
                                                                    row.scrollIntoView({
                                                                        behavior: 'smooth',
                                                                        block: 'center'
                                                                    });
                                                                    row.style.backgroundColor = '#ffff99'; // Highlight with a yellow color
                                                                }
                                                            });
                                                        }
                                                    });
                                                </script>

                                            </td>
                                        </tr>
                                        @php
                                            $rowNumber++; // Increment row counter
                                        @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <link href="{{ asset('assets/libs/datatables2/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <!-- DataTables2 -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables2/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables2/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables2/datatables-demo.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#dataTableloket").DataTable({
                "pageLength": -1,
            });
        });
    </script>
    <!-- JavaScript to handle row click -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.clickable-row');
            rows.forEach(function(row) {
                row.addEventListener('click', function() {
                    const href = this.getAttribute('data-href');
                    window.location.href = href;
                });

                row.addEventListener('mousedown', function() {
                    this.classList.add('row-active');
                });

                row.addEventListener('mouseup', function() {
                    this.classList.remove('row-active');
                });
            });
        });
    </script>

    <!-- CSS Styles for hover and active click effect -->
    <style>
        .hoverable-row:hover {
            background-color: #D8D8D8FF;
            /* Light gray on hover */
            cursor: pointer;
        }

        .row-active {
            background-color: #D8D8D8FF;
            /* Darker gray when clicked */
        }

        #dataTableloket_wrapper {
            position: relative !important;
            overflow: hidden;
        }

        #dataTableloket_wrapper table {
            width: 100% !important;
            overflow-x: auto;
            white-space: nowrap;
        }

        #dataTableloket_length {
            float: left;
            margin-bottom: 20px;
        }

        #dataTableloket_filter {
            float: left;
        }

        #dataTableloket_filter input[type="search"] {
            width: auto !important;
        }

        @media (max-width: 767px) {
            #dataTableloket_wrapper table {
                display: block;
                overflow-x: auto;
            }

            #dataTableloket_filter input[type="search"] {
                width: 15rem !important;
                margin-left: 0 !important;
                margin-bottom: 5px;
            }

            #dataTableloket_filter label {
                margin-left: 0 !important;
            }
        }

        @media (min-width: 767px) {
            #dataTableloket_length {
                float: none;
                margin-bottom: 0px;
            }

            #dataTableloket_filter {
                float: right;
            }
        }
    </style>
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #222;
            z-index: 9999;
        }

        #loader {
            display: block;
            position: relative;
            left: 50%;
            top: 50%;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #9370DB;
            animation: spin 2s linear infinite;
        }

        #loader:before,
        #loader:after {
            content: "";
            position: absolute;
            border-radius: 50%;
            border: 3px solid transparent;
        }

        #loader:before {
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-top-color: #BA55D3;
            animation: spin 3s linear infinite;
        }

        #loader:after {
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border-top-color: #FF00FF;
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .separator {
            position: relative;
            display: inline-block;
            width: 100%;
            /* Menentukan panjang garis */
            height: 1px;
            /* Garis horizontal tipis */
            background-color: transparent;
            /* Warna garis */
        }

        .separator::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 0;
            width: 1px;
            /* Lebar garis vertikal */
            height: 100%;
            /* Panjang garis vertikal */
            background-color: #e2e2e2;
            /* Warna garis vertikal */
            transform: translateX(-50%);
            /* Menyeimbangkan garis di tengah */
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const preloader = document.getElementById('preloader');
            window.addEventListener('load', () => {
                preloader.style.display = 'none';
            });
        });
    </script>
@endsection
