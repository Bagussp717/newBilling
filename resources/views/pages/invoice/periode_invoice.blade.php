@extends('layouts.backend')

@section('title', 'Data Invoice Periode')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Invoice Periode</h5>
                </div>
                <div class="p-4 card-body">
                    <div class="gap-2 mt-3 mb-3 d-flex">
                        <a href="{{ route('invoice.index') }}" class="btn btn-danger">Kembali</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTable"
                            style=" width: 100%; min-width: 0; max-width: 200px; white-space: nowrap;">
                            <thead class="mb-0 align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">No</th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Nama Pelanggan</th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Profile</th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Tanggal Akhir</th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Jumlah Bayar</th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Harga Paket</th>
                                    <th class="border-bottom-0"><h6 class="mb-0 fw-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $index => $invoice)
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
                                            $profile = $invoice->status_pppoe;
                                        }
                                    @endphp
                            
                                    {{-- Hanya tampilkan baris jika profile bukan "Gratis" dan keterangan bukan "uji coba" --}}
                                    @if ($profile !== 'Gratis' && optional($invoice->pelanggan->paket)->keterangan !== 'uji coba')
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ optional($invoice->pelanggan)->username_pppoe ?? 'Tidak Ada Pelanggan' }}</td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">{{ $profile }}</p>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($invoice->tgl_akhir)->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge {{ $badgeClass }}">
                                                    @if ($totalPembayaran > 0)
                                                        Rp {{ number_format($totalPembayaran) }}
                                                    @else
                                                        Belum Bayar
                                                    @endif
                                                </span>
                                            </td>
                                            <td>Rp {{ number_format($hrgPaket) }}</td>
                                            <td>
                                                <div class="gap-2 d-flex align-items-center justify-content-center">
                                                    <a href="{{ route('invoice.full', Crypt::encryptString($invoice->kd_invoice)) }}"
                                                        title="invoice 1" data-bs-toggle="tooltip" class="trigger-print"
                                                        data-target="{{ route('invoice.full', Crypt::encryptString($invoice->kd_invoice)) }}">
                                                        <span><i style="font-size: 20px;" class="text-secondary ti ti-printer"></i></span>
                                                    </a>
                            
                                                    <!-- Small Invoice Print -->
                                                    <a href="{{ route('invoice.small', Crypt::encryptString($invoice->kd_invoice)) }}"
                                                        title="invoice 2" data-bs-toggle="tooltip" class="trigger-print"
                                                        data-target="{{ route('invoice.small', Crypt::encryptString($invoice->kd_invoice)) }}">
                                                        <span><i style="font-size: 20px;" class="text-danger ti ti-layers-intersect"></i></span>
                                                    </a>
                                                    @if ($totalPembayaran <= 0)
                                                        <a href="{{ route('invoice.isolir', $invoice->kd_pelanggan) }}" title="Isolir"
                                                            data-bs-toggle="tooltip">
                                                            <span><i style="font-size: 20px;" class="text-danger ti ti-cloud-lock"></i></span>
                                                        </a>
                                                        <a href="{{ route('invoice.pulihkan', $invoice->kd_pelanggan) }}" title="Pulihkan"
                                                            data-bs-toggle="tooltip">
                                                            <span><i style="font-size: 20px;" class="text-secondary ti ti-lock-open"></i></span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
