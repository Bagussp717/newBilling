@extends('layouts.backend')

@section('title', 'Data Loket')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Loket</h5>
                </div>
                <div class="p-4 card-body">
                    <div class="table-responsive">
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
                                        <h6 class="mb-0 fw-semibold">Filter Periode</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lokets as $index => $loket)
                                    <tr>
                                        <td class="align-middle border-bottom-0">
                                            <h6 class="mb-0 fw-normal">{{ $index + 1 }}</h6>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $loket->nm_loket }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <form action="{{ route('search.invoice') }}" method="GET">
                                                @csrf
                                                <input type="hidden" name="kd_loket"
                                                    value="{{ Crypt::encryptString($loket->kd_loket) }}">
                                                <div class="row">
                                                    <div class="mb-2 col-md-8">
                                                        <select name="tgl_invoice"
                                                            class="form-select @error('tgl_invoice') is-invalid @enderror">
                                                            <option value="" selected>Semua Invoice</option>
                                                            @foreach ($invoiceDates as $tgl_invoice)
                                                                <option value="{{ $tgl_invoice }}"
                                                                    {{ request('tgl_invoice') == $tgl_invoice ? 'selected' : '' }}>
                                                                    {{ \Carbon\Carbon::parse($tgl_invoice)->format('d M Y') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('tgl_invoice')
                                                            <span class="invalid-feedback" role="alert">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    {{-- <div class="mb-2 col-md-4">
                                                        <select name="tgl_akhir" class="form-select">
                                                            <option value="" selected>Tanggal Akhir</option>
                                                            @foreach ($invoiceEnds as $tgl_akhir)
                                                                <option value="{{ $tgl_akhir }}"
                                                                    {{ request('tgl_akhir') == $tgl_akhir ? 'selected' : '' }}>
                                                                    {{ \Carbon\Carbon::parse($tgl_akhir)->format('d M Y') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}
                                                    <div class="col-md-2">
                                                        <button type="submit" name="search" class="btn btn-primary">
                                                            Detail Pembayaran
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
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
