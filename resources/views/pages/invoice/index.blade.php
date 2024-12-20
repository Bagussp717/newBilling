@extends('layouts.backend')

@section('title', 'Data Invoice')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Semua Data Invoice</h5>
                </div>
                <div class="p-4 card-body">
                    <form action="{{ route('invoice.generateInvoices') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-3">
                                <input type="date" class="form-control me-2 @error('tgl_invoice') is-invalid @enderror"
                                    id="tgl_invoice" name="tgl_invoice" placeholder="Tanggal Invoice"
                                    value="{{ old('tgl_invoice') }}">
                                @error('tgl_invoice')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-3">
                                <input type="date" class="form-control me-2 @error('tgl_akhir') is-invalid @enderror"
                                    id="tgl_akhir" name="tgl_akhir" placeholder="Tanggal Akhir"
                                    value="{{ old('tgl_akhir') }}">
                                @error('tgl_akhir')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-3">
                                <select name="kd_isp" id="kd_isp" class="form-control me-2 @error('kd_isp') is-invalid @enderror">
                                    <option value="">-- Pilih ISP --</option>
                                    @foreach ($isps as $isp)
                                        <option value="{{ $isp->kd_isp }}">{{ $isp->nm_isp }}</option>
                                    @endforeach
                                </select>
                                @error('kd_isp')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-3">
                                <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip"
                                    title="Create">Generate Invoice</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTable">
                            <thead class="mb-0 align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Nama ISP</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Tanggal Invoice</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Tanggal Akhir</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Invoice</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $uniqueInvoices = $invoices->unique(function ($item) {
                                        return $item['tgl_invoice'] . '-' . $item['tgl_akhir'];
                                    });
                                @endphp
                                @php
                                    $sortedInvoices = $uniqueInvoices->sortBy('tgl_invoice')->values();
                                @endphp
                                @foreach ($sortedInvoices as $index => $invoice)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ optional($invoice->isp)->nm_isp ?? 'Tidak Ada ISP' }}</td> <!-- Added ISP Name column -->
                                        <td>{{ \Carbon\Carbon::parse($invoice->tgl_invoice)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->tgl_akhir)->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('invoice.showByPeriod', ['tgl_invoice' => $invoice->tgl_invoice, 'tgl_akhir' => $invoice->tgl_akhir]) }}"
                                                class="btn btn-warning" data-bs-toggle="tooltip" title="Show">
                                                Lihat Invoice
                                            </a>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <div class="gap-2 d-flex align-items-center">
                                                {{-- Formulir Hapus --}}
                                                <form action="{{ route('invoice.destroyByDate') }}" method="POST" id="deleteForm">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="tgl_invoice" id="tgl_invoice" value="{{ $invoice->tgl_invoice }}">
                                                    <a href="#"  onclick="notificationBeforeDelete(event, this)" onclick="document.getElementById('deleteForm').submit(); return false;"
                                                       data-bs-toggle="tooltip" title="Hapus">
                                                        <span>
                                                            <i style="font-size: 20px; color: #A02334;" class="ti ti-trash"></i>
                                                        </span>
                                                    </a>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
