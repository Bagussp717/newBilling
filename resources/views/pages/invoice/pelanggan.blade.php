@extends('layouts.backend')

@section('title', 'Data Invoice')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Semua Invoice</h5>
                </div>
                <div class="p-4 card-body">
                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTable"
                            style=" width: 100%; min-width: 0; max-width: 200px; white-space: nowrap;">
                            <thead class="mb-0 align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold" style="width: 20px">No</h6></th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Nama</h6></th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">profile</h6></th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Tanggal Akhir</h6></th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Jumlah Bayar</h6></th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Harga Paket</h6></th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Aksi</h6></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $value => $invoice)
                                @php
                                        $totalPembayaran = $invoice->pembayaran->sum('jml_bayar');
                                        $hrgPaket = optional($invoice->pelanggan->paket)->hrg_paket ?? 0;
                                        $badgeClass = 'badge-primary';
                                        $profile = $invoice->pelanggan->profile_pppoe;

                                        if ($totalPembayaran == $hrgPaket) {
                                            $badgeClass = 'badge-success';
                                        } elseif ($totalPembayaran < $hrgPaket && $totalPembayaran > 0) {
                                            $badgeClass = 'badge-warning';
                                        } elseif ($totalPembayaran <= 0) {
                                            $badgeClass = 'badge-danger';
                                            // $profile = 'isolir';
                                            $profile = $invoice->status_pppoe;
                                        }
                                    @endphp
                                    <tr>
                                        <td class="align-middle border-bottom-0">
                                            <h6 class="mb-0 fw-normal">{{ $value + 1 }}</h6>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                {{ optional($invoice->pelanggan)->nm_pelanggan ?? 'Tidak Ada Pelanggan' }}
                                            </p>
                                        </td>

                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $profile }}</p>
                                        </td>
                                        {{-- <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                {{ $invoice->pelanggan->profile_pppoe}}
                                            </p>
                                        </td> --}}
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->format('d M Y') }}
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
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

                                            <span class="badge {{ $badgeClass }}">
                                                @if ($totalPembayaran > 0)
                                                    Rp {{ number_format($totalPembayaran) }}
                                                @else
                                                    Belum Bayar
                                                @endif
                                            </span>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-semibold badge badge-success">
                                                Rp {{ number_format($hrgPaket) }}
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <div class="gap-2 d-flex align-items-center justify-content-center">
                                                <a href="{{ route('invoice.small', Crypt::encryptString($invoice->kd_invoice)) }}" title="Invoice 1"
                                                    data-bs-toggle="tooltip">
                                                    <span><i style="font-size: 20px;" class="text-blue ti ti-printer"></i></span>
                                                </a> 
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
