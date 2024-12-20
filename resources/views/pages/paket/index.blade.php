@extends('layouts.backend')

@section('title', 'Paket')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Paket</h5>
                </div>
                <div class="card-body p-4">
                    <a href="{{ route('paket.create') }}" class="btn btn-primary mb-4 d-none" data-bs-toggle="tooltip"
                        title="Create">Tambah Data Paket</a>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTable"
                            style=" width: 100%;
                            min-width: 0;
                            max-width: 200px;
                            white-space: nowrap;">
                            <thead class="text-dark fs-4 mb-0 align-middle">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0" style="width: 20px">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Nama Paket</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Harga Paket</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Kode ISP</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Kode Cabang</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Keterangan</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0 text-center">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pakets as $value => $paket)
                                    <tr>
                                        <td class="border-bottom-0 align-middle">
                                            <h6 class="fw-normal mb-0">{{ $value + 1 }}</h6>
                                        </td>
                                        <td class="border-bottom-0 align-middle">
                                            <p class="mb-0 fw-normal">{{ $paket->nm_paket }}</p>
                                        </td>
                                        <td class="border-bottom-0 align-middle">
                                            <p class="mb-0 fw-normal">Rp {{ number_format($paket->hrg_paket, 0, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="align-middle">{{ $paket->isp->nm_isp ?? ' ' }}</td>
                                        <td class="border-bottom-0 align-middle">
                                            <p class="mb-0 fw-normal">{{ $paket->cabang->nm_cabang }}</p>
                                            <!-- Menampilkan nm_cabang -->
                                        </td>
                                        <td class="border-bottom-0 align-middle">
                                            <p class="mb-0 fw-normal">{{ $paket->keterangan }}</p>
                                            <!-- Menampilkan keterangan -->
                                        </td>
                                        <td class="border-bottom-0 align-middle">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#editPaketModal{{ $paket->kd_paket }}" class="btn"
                                                    title="Edit" data-bs-toggle="tooltip">
                                                    <span>
                                                        <i style="font-size: 20px;" class="ti ti-edit text-black"></i>
                                                    </span>
                                                </button>
                                                {{-- <form action="{{ route('paket.destroy', $paket->kd_paket) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="{{ route('paket.destroy', $paket->kd_paket) }}"
                                                        class="btn "
                                                        onclick="notificationBeforeDelete(event, this)"
                                                        data-bs-toggle="tooltip" title="Hapus"><span>
                                                            <i style="font-size: 20px;" class="ti ti-trash text-danger"></i>
                                                        </span></a>

                                                </form> --}}
                                                @include('pages.paket.edit')
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
