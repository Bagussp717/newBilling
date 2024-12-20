@extends('layouts.backend')

@section('title', 'Tambah Data Pembayaran')

@section('content')
    <div class="mb-5 card">
        <div class="card-header">
            <h5 class="card-title">Detail Data Pelanggan</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="py-2" style="width: 20%">Nama Pelanggan</th>
                        <td class="py-2" style="width: 1%">:</td>
                        <td class="py-2" style="width: 79%">{{ $invoice->pelanggan->nm_pelanggan }}</td>
                    </tr>
                    <tr>
                        <th class="py-2" style="width: 20%">Tempat, Tanggal Lahir</th>
                        <td class="py-2" style="width: 1%">:</td>
                        <td class="py-2" style="width: 79%"> {{ $invoice->pelanggan->tgl_lahir ?? ' - ' }}</td>
                    </tr>
                    <tr>
                        <th class="py-2" style="width: 20%">Alamat</th>
                        <td class="py-2" style="width: 1%">:</td>
                        <td class="py-2" style="width: 79%"> {{ $invoice->pelanggan->t_lahir ?? ' - ' }}</td>
                    </tr>
                    <tr>
                        <th class="py-2" style="width: 20%">Pekerjaan</th>
                        <td class="py-2" style="width: 1%">:</td>
                        <td class="py-2" style="width: 79%">{{ $invoice->pelanggan->pekerjaan }}</td>
                    </tr>
                    <tr>
                        <th class="py-2" style="width: 20%">Harga Paket</th>
                        <td class="py-2" style="width: 1%">:</td>
                        <td class="py-2" style="width: 79%">
                            @if ($invoice->pelanggan && $invoice->pelanggan->paket)
                                Rp {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-5 card">
        <div class="card-header">
            <h5 class="card-title">Tambah Data Pembayaran</h5>
        </div>
        <div id="preloader">
            <div id="loader"></div>
        </div>          
        <div class="card-body">
            <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- <div class="mb-3 col-md-6">
                        <label for="kd_loket" class="form-label">Nama Loket</label>
                        <select name="kd_loket" class="form-select @error('kd_loket') is-invalid @enderror" id="kd_loket">
                            <option value="">Opsional </option>
                            @foreach ($lokets as $loket)
                                <option value="{{ $loket->kd_loket }}"
                                    {{ old('kd_loket', $pelanggan->kd_loket) == $loket->kd_loket ? 'selected' : '' }}>
                                    {{ $loket->nm_loket }}
                                </option>
                            @endforeach
                        </select>
                        @error('kd_cabang')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div> --}}
                    <div class="mb-3 col-md-6">
                        <label for="kd_loket" class="form-label">Nama Loket</label>
                        <select name="kd_loket" class="form-select @error('kd_loket') is-invalid @enderror" id="kd_loket">
                            <option value="">Opsional</option>
                            @foreach ($lokets as $loket)
                                <option value="{{ $loket->kd_loket }}"
                                    {{ old('kd_loket', $pelanggan->kd_loket) == $loket->kd_loket ? 'selected' : '' }}>
                                    {{ $loket->nm_loket }}
                                </option>
                            @endforeach
                        </select>
                        @error('kd_cabang')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>                    
                    <div class="mb-3 col-md-6">
                        <label for="kd_invoice" class="form-label">Invoice</label>
                        <select name="kd_invoice" class="form-select @error('kd_invoice') is-invalid @enderror" readonly
                            id="kd_invoice" onchange="updateInvoiceDetails()">
                            <option value="">Pilih Invoice</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->kd_invoice }}"
                                    {{ $kd_invoice == $invoice->kd_invoice ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('kd_invoice')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                        <input type="date" id="tgl_bayar" class="form-control @error('tgl_bayar') is-invalid @enderror"
                            name="tgl_bayar" value="{{ old('tgl_bayar', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                            placeholder="Tanggal Bayar">
                        @error('tgl_bayar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="jml_bayar" class="form-label">Jumlah Bayar</label>
                        <!-- Input untuk tampilan -->
                        <input type="text" id="jml_bayar_view"
                            class="form-control @error('jml_bayar') is-invalid @enderror" placeholder="Jumlah Bayar"
                            oninput="formatRupiah(this)">

                        <!-- Input hidden untuk nilai asli -->
                        <input type="hidden" id="jml_bayar" name="jml_bayar" value="{{ old('jml_bayar') }}">

                        @error('jml_bayar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="gap-2 d-flex">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    @role('super-admin|isp|teknisi')
                        <a id="kembaliButton"
                            href="{{ route('search.invoice', [
                                'kd_loket' => Crypt::encryptString($pelanggan->kd_loket),
                                'tgl_invoice' => $invoices->where('kd_invoice', $kd_invoice)->first()->tgl_invoice ?? null,
                            ]) }}"
                            class="btn btn-danger">Kembali</a>
                    @endrole
                    @role('loket')
                        <a href="{{ route('search.invoice', ['kd_loket' => Crypt::encryptString(session('kd_loket')), 'username' => $pelanggan->username_pppoe, 'tgl_invoice' => $invoices->where('kd_invoice', $kd_invoice)->first()->tgl_invoice ?? null]) }}"
                            class="btn btn-danger">Kembali</a>
                    @endrole
                </div>
            </form>
        </div>
    </div>
    <div class="mb-5 card">
        <div class="card-header">
            <h5 class="card-title">Detail Pembayaran Pelanggan</h5>
        </div>
        <div class="card-body">
            <table class="table border table-striped" id="dataTable"
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
                            <h6 class="mb-0 fw-semibold">Nama Loket</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="mb-0 fw-semibold">Tanggal Bayar</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="mb-0 fw-semibold">Invoice</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="mb-0 fw-semibold">Jumlah Bayar</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="mb-0 fw-semibold">Harga Paket</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="mb-0 fw-semibold">Aksi</h6>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembayarans as $index => $pembayaran)
                        <tr>
                            <td class="align-middle border-bottom-0">
                                <h6 class="mb-0 fw-normal">{{ $index + 1 }}</h6>
                            </td>
                            <td class="align-middle border-bottom-0">
                                <p class="mb-0 fw-normal">{{ $pembayaran->loket->nm_loket }}</p>
                            </td>
                            <td class="align-middle border-bottom-0">
                                <p class="mb-0 fw-normal">
                                    {{ \Carbon\Carbon::parse($pembayaran->tgl_bayar)->format('d M Y') }}</p>
                            </td>
                            <td class="align-middle border-bottom-0">
                                @php
                                    // Cari invoice berdasarkan kd_invoice yang terpilih
                                    $selectedInvoice = $invoices->firstWhere('kd_invoice', $pembayaran->kd_invoice);
                                @endphp
                                <p class="mb-0 fw-normal">
                                    {{ $pembayaran->kd_invoice }}
                                    @if ($selectedInvoice)
                                        - {{ \Carbon\Carbon::parse($selectedInvoice->tgl_invoice)->format('d M Y') }}
                                    @endif
                                </p>
                            </td>
                            <td class="align-middle border-bottom-0">
                                <p class="mb-0 fw-normal">Rp {{ number_format($pembayaran->jml_bayar, 0, ',', '.') }}</p>
                            </td>
                            <td class="align-middle border-bottom-0">
                                <p class="mb-0 fw-normal"> - </p>
                            </td>
                            <td class="align-middle border-bottom-0">
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#editPembayaranModal{{ $pembayaran->kd_pembayaran }}"
                                    class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                    Edit
                                </button>
                                {{-- edit cabang --}}
                                @include('pages.pembayaran.edit')

                                <form action="{{ route('pembayaran.destroy', Crypt::encryptString($pembayaran->kd_pembayaran)) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <a class="btn btn-danger" href="{{ route('pembayaran.destroy', Crypt::encryptString($pembayaran->kd_pembayaran)) }}"
                                        onclick="notificationBeforeDelete(event, this)" data-bs-toggle="tooltip"
                                        title="Hapus">Hapus</a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="align-middle border-top border-top-2">
                            <p class="mb-0 fw-semibold">Total Pembayaran</p>
                        </td>
                        <td colspan="1" class="border-top border-top-2">
                            <p class="mb-0 fw-semibold"> Rp
                                {{ number_format($pembayarans->sum('jml_bayar'), 0, ',', '.') }}</p>
                        </td>
                        <td colspan="1" class="border-top border-top-2">
                            <p class="mb-0 fw-semibold">
                                @if ($invoice->pelanggan && $invoice->pelanggan->paket)
                                    Rp {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}
                                @else
                                    <span>-</span>
                                @endif
                            </p>
                        </td>
                </tfoot>
            </table>
        </div>
    </div>
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
        #loader:before, #loader:after {
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
