@extends('layouts.backend')

@section('title', 'Cabang')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Cabang</h5>
                </div>
                <div class="p-4 card-body">
                    @role('super-admin|isp')
                        <a href="{{ route('cabang.create') }}" class="mb-4 btn btn-primary" data-bs-toggle="tooltip"
                            title="Create">Tambah Data Cabang</a>
                    @endrole
                    <div>
                        <table class="table border table-striped" style="width: 100%;" id="dataTable" style=" width: 100%;">
                            <thead class="mb-0 align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold" style="width: 5%">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Nama Cabang</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Alamat Cabang</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Ip Mikrotik</h6>
                                    </th>

                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Nama ISP</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cabangs as $value => $cabang)
                                    <tr>
                                        <td class="align-middle border-bottom-0">
                                            <h6 class="mb-0 fw-normal">{{ $value + 1 }}</h6>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $cabang->nm_cabang }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $cabang->alamat_cabang }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $cabang->ip_mikrotik }}</p>
                                        </td>

                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $cabang->isp->nm_isp ?? ' ' }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <div class="gap-2 d-flex align-items-center">
                                                <!-- Tombol Edit -->
                                                @role('super-admin|isp')
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#editCabangModal{{ $cabang->kd_cabang }}"
                                                        class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                                        Edit
                                                    </button>
                                                @endrole
                                                @role('super-admin|isp')
                                                    <!-- Formulir Hapus -->
                                                    <form
                                                        action="{{ route('cabang.destroy', Crypt::encryptString($cabang->kd_cabang)) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="{{ route('cabang.destroy', Crypt::encryptString($cabang->kd_cabang)) }}"
                                                            class="btn btn-danger"
                                                            onclick="notificationBeforeDelete(event, this)"
                                                            data-bs-toggle="tooltip" title="Hapus">Hapus</a>
                                                    </form>
                                                @endrole
                                                <form action="{{ route('mikrotik.login') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="kd_cabang"
                                                        value="{{ $cabang->kd_cabang }}">
                                                    <button type="submit" class="btn btn-success">Hubungkan</button>
                                                </form>

                                                {{-- edit cabang --}}
                                                @include('pages.cabang.edit')

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
