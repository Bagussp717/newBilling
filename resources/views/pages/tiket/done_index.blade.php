@extends('layouts.backend')

@section('title', 'Data Pengaduan')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Pengaduan</h5>
                </div>
                <div class="p-4 card-body">
                    <div class="table-responsive">
                        <table class="table table-striped border" id="dataTable" style="width: 100%; white-space: nowrap;">
                            <thead class="align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Pelanggan</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Deskripsi</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">waktu</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Status</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengaduans as $key => $pengaduan)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $pengaduan->pelanggan->nm_pelanggan }}</td>
                                        <td>{{ implode(' ', array_slice(explode(' ', $pengaduan->deskripsi_tiket), 0, 5)) }}{{ strlen($pengaduan->deskripsi_tiket) > 5 ? '...' : '' }}
                                        </td>
                                        <td>{{ $pengaduan->created_at }}</td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'Pending' => 'badge bg-warning',
                                                    'Diterima' => 'badge bg-primary',
                                                    'Diproses' => 'badge bg-secondary',
                                                    'Selesai' => 'badge bg-success',
                                                ];
                                            @endphp

                                            <span class="{{ $statusClasses[$pengaduan->status_tiket] ?? 'badge bg-secondary' }}">
                                                {{ $pengaduan->status_tiket }}
                                            </span>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <div class="gap-2 d-flex align-items-center">
                                                <!-- Tombol Show -->
                                                <a href="#" title="show" data-bs-toggle="modal" data-bs-target="#modalShow{{ $pengaduan->kd_tiket }}">
                                                    <i style="font-size: 20px;" class="ti ti-eye text-primary"></i>
                                                </a>

                                                <!-- Tombol Hapus -->
                                                {{-- <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="" style="background: none; border: none;" data-bs-toggle="tooltip" title="Hapus">
                                                        <i style="font-size: 20px; color: #A02334;" class="ti ti-trash"></i>
                                                    </button>
                                                </form>                                                 --}}

                                                <!-- Modal Show -->
                                                <div class="modal fade" id="modalShow{{ $pengaduan->kd_tiket }}"
                                                    tabindex="-1" aria-labelledby="modalShowLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalShowLabel">Detail Pengaduan
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h6>Nama:</h6>
                                                                <p style="max-height: 200px; overflow-y: auto;">
                                                                    {{ $pengaduan->pelanggan->nm_pelanggan }}</p>
                                                                <h6>Alamat:</h6>
                                                                <p style="max-height: 200px; overflow-y: auto;">
                                                                    {{ $pengaduan->pelanggan->alamat }}</p>
                                                                <h6>Deskripsi:</h6>
                                                                <p style="max-height: 200px; overflow-y: auto;">
                                                                    {{ $pengaduan->deskripsi_tiket }}</p>
                                                                <h6>Status:</h6>
                                                                <p>
                                                                    @php
                                                                        $statusClasses = [
                                                                            'Pending' => 'badge bg-warning',
                                                                            'Diterima' => 'badge bg-primary',
                                                                            'Diproses' => 'badge bg-secondary',
                                                                            'Selesai' => 'badge bg-success',
                                                                        ];
                                                                    @endphp

                                                                    <span class="{{ $statusClasses[$pengaduan->status_tiket] ?? 'badge bg-secondary' }}">
                                                                        {{ $pengaduan->status_tiket }}
                                                                    </span>
                                                                </p>
                                                                <h6>Waktu Pengaduan:</h6>
                                                                <p>{{ $pengaduan->created_at }}</p>
                                                                <h6>Foto:</h6>
                                                                @if ($pengaduan->foto)
                                                                    <img src="{{ asset("storage/".$pengaduan->foto) }}"
                                                                        alt="Foto Pengaduan"
                                                                        style="width: 100%; height: auto;">
                                                                @else
                                                                    <p>Tidak ada foto</p>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
