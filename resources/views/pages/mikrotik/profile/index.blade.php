@extends('layouts.mikrotik')

@section('title', 'Profile Mikrotik')

@section('content')

    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Profile Mikrotik </h5>
                </div>
                <div class="p-4 card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: relative;">
                            <ul class="mt-2 mb-0">
                                <li>{{ $errors->first() }}</li>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                style="position: absolute; top: 5px; right: 10px;">
                            </button>
                        </div>
                    @endif
                    {{-- <a href="{{ route('profilemikrotik.create') }}" class="mb-4 btn btn-primary"
                    data-bs-toggle="tooltip" title="Create">Tambah Profile Mikrotik</a> --}}
                    <a href="#" class="mb-4 btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfileModal"
                        title="Create">Tambah Profile Mikrotik</a>
                    <a href="#" class="mb-4 btn btn-primary" data-bs-toggle="modal" data-bs-target="#syncProfileModal"
                        title="Sync">Sinkron Profile Mikrotik</a>
                    @include('pages.mikrotik.profile.create')
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
                                        <h6 class="mb-0 fw-semibold">Nama Paket</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Local Address</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Remote Address</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Rate Limit</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profile as $key => $items)
                                    <tr>
                                        <div hidden>{{ $nm_paket = $items['name'] }}</div>
                                        <td class="align-middle border-bottom-0">
                                            <h6 class="mb-0 fw-normal">{{ $key + 1 }}</h6>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $items['name'] }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (array_key_exists('local-address', $items))
                                                    {{ $items['local-address'] }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (array_key_exists('remote-address', $items))
                                                    {{ $items['remote-address'] }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (array_key_exists('rate-limit', $items))
                                                    {{ $items['rate-limit'] }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <div class="gap-2 d-flex align-items-center">
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('profilemikrotik.edit', $nm_paket) }}" title="Edit"
                                                    data-bs-toggle="tooltip">
                                                    <span>
                                                        <i style="font-size: 20px;" class="ti ti-edit text-black"></i>
                                                    </span>
                                                </a>
                                                <a href="#" class="border-0" data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModalProfile{{ str_replace(' ', '-', $nm_paket) }}" 
                                                    title="Hapus">
                                                        <span>
                                                            <i style="font-size: 20px;" class="ti ti-trash-x text-danger"></i>
                                                        </span>
                                                </a>
                                                {{-- <a href="{{ route('profilemikrotik.destroy', $nm_paket) }}"
                                                    data-nm-paket="{{ $nm_paket }}"
                                                    data-action-url="{{ route('profilemikrotik.destroy', $nm_paket) }}"
                                                    data-bs-toggle="tooltip" title="Hapus"><span>
                                                        <i style="font-size: 20px;" class="ti ti-trash text-danger"></i>
                                                    </span></a> --}}
                                                @include('pages.mikrotik.profile.delete')
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

    <!-- Modal Sinkronisasi Profile Mikrotik -->
    <div class="modal fade" id="syncProfileModal" tabindex="-1" aria-labelledby="syncProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="syncProfileModalLabel">Sinkronisasi Profile Mikrotik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin melakukan sinkronisasi profile dari Mikrotik ke database? <br> Data yang ada
                        di Mikrotik akan ditambahkan ke database jika belum ada.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('profilemikrotik.sync') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Sinkron Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
