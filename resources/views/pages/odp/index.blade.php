@extends('layouts.backend')

@section('title', 'Data ODP')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data ODP</h5>
                </div>
                <div class="p-4 card-body">
                    <a href="{{ route('odp.create') }}" class="mb-4 btn btn-primary" data-bs-toggle="tooltip"
                        title="Tambah Data ODP">
                        Tambah Data ODP
                    </a>
                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTable" style="width: 100%; white-space: nowrap;">
                            <thead class="align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Nama</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Isp</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Cabang</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Lokasi Maps</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($odps as $index => $odp)
                                    <tr>
                                        <td class="align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">{{ $odp->nm_odp ?? ' - ' }}</td>
                                        <td class="align-middle">{{ $odp->isp->nm_isp ?? ' ' }}</td>
                                        <td class="align-middle">{{ $odp->cabang->nm_cabang }}</td>
                                        <td class="justify-center align-middle">
                                            <a class="btn btn-sm btn-primary"
                                                href="https://www.google.com/maps?q={{ $odp->lat }},{{ $odp->long }}"
                                                target="_blank">
                                                <i class="ti ti-map-pin" style="font-size: 12px"></i> Lihat Lokasi
                                            </a>
                                        </td>

                                        <td class="align-middle border-bottom-0">
                                            <div class="gap-2 d-flex align-items-center justify-content-center">
                                                <!-- Tombol Show -->
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#showodpModal{{ $odp->kd_odp }}" class="border-0"
                                                    title="Show" data-bs-toggle="tooltip">
                                                    <span>
                                                        <i style="font-size: 20px;" class="ti ti-eye text-primary"></i>
                                                    </span>
                                                </button>
                                                @include('pages.odp.show')

                                                <!-- Tombol Edit -->
                                                <a href="{{ route('odp.edit', Crypt::encryptString($odp->kd_odp)) }}" title="Edit"
                                                    data-bs-toggle="tooltip">
                                                    <i style="font-size: 20px;" class="text-black ti ti-edit"></i>
                                                </a>

                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('odp.destroy', Crypt::encryptString($odp->kd_odp)) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="{{ route('odp.destroy', Crypt::encryptString($odp->kd_odp)) }}"
                                                        onclick="notificationBeforeDelete(event, this)"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <span>
                                                            <i style="font-size: 20px; color: #A02334;"
                                                                class="ti ti-trash"></i>
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
