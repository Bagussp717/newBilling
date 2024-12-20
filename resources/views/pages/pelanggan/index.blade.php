@extends('layouts.backend')

@section('title', 'Pelanggan Aktif')

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-header">
                <h5 class="card-title">Data Pelanggan Aktif</h5>
            </div>
            <div class="p-4 card-body">
                <div class="table-responsive">
                    <table class="table border table-striped" id="dataTable" style=" width: 100%;
                                min-width: 0;
                                max-width: 200px;
                                white-space: nowrap;">
                        <thead class="mb-0 align-middle text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold" style="width: 20px">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Username</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Paket</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Nama Pelanggan</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 text-center fw-semibold">Aksi</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggans as $value => $pelanggan)
                            <tr>
                                <td class="align-middle border-bottom-0">
                                    <h6 class="mb-0 fw-normal">{{ $value + 1 }}</h6>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $pelanggan->username_pppoe }}</p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $pelanggan->profile_pppoe }}</p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">
                                        {{ $pelanggan->nm_pelanggan }}
                                    </p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="{{ route('pelanggan.edit', Crypt::encryptString($pelanggan->kd_pelanggan)) }}"
                                            title="Edit" data-bs-toggle="tooltip">
                                            <span>
                                                <i style="font-size: 20px;" class="ti ti-edit text-warning"></i>
                                            </span>
                                        </a>

                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#showPelangganModal{{ $pelanggan->kd_pelanggan }}"
                                            class="btn" title="Show" data-bs-toggle="tooltip">
                                            <span>
                                                <i style="font-size: 20px;" class="ti ti-eye text-primary"></i>
                                            </span>
                                        </button>
                                        @include('pages.pelanggan.show')
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
