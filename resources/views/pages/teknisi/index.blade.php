@extends('layouts.backend')

@section('title', 'Data Teknisi')

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-header">
                <h5 class="card-title">Data Teknisi</h5>
            </div>
            <div class="p-4 card-body">
                <a href="{{ route('teknisi.create') }}" class="mb-4 btn btn-primary" data-bs-toggle="tooltip"
                    title="Create">Tambah Data Teknisi</a>
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
                                    <h6 class="mb-0 fw-semibold">Nama Teknisi</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Tanggal Aktif</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Email</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Alamat Tekisi</h6>
                                </th>
                                <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teknisis as $value => $teknisi)
                            <tr>
                                <td class="align-middle border-bottom-0">
                                    <h6 class="mb-0 fw-normal">{{ $value + 1 }}</h6>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $teknisi->user->name }}</p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $teknisi->tgl_aktif }}</p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $teknisi->user->email }}</p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $teknisi->alamat_teknisi }}</p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <div class="gap-2 d-flex align-items-center">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('teknisi.edit', Crypt::encryptString($teknisi->kd_teknisi)) }}"  title="Edit"
                                            data-bs-toggle="tooltip">
                                            <span>
                                                <i style="font-size: 20px;" class="text-black ti ti-edit"></i>
                                            </span>
                                        </a>
                                        <!-- Formulir Hapus -->
                                        <form action="{{ route('teknisi.destroy', Crypt::encryptString($teknisi->kd_teknisi)) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('teknisi.destroy', Crypt::encryptString($teknisi->kd_teknisi)) }}"  onclick="notificationBeforeDelete(event, this)"
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
