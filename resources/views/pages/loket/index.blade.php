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
                <a href="{{ route('loket.create') }}" class="mb-4 btn btn-primary" data-bs-toggle="tooltip" title="Tambah Data Loket">
                    Tambah Data Loket
                </a>
                <div class="table-responsive">
                    <table class="table border table-striped" id="dataTable" style="width: 100%; white-space: nowrap;">
                        <thead class="align-middle text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Nama Loket</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Alamat Loket</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Jenis Komisi</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Jumlah Komisi</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Aksi</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lokets as $index => $loket)
                            <tr>
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">{{ $loket->nm_loket }}</td>
                                <td class="align-middle">{{ $loket->alamat_loket }}</td>
                                <td class="align-middle">{{ ucfirst($loket->jenis_komisi) }}</td>
                                <td class="align-middle">Rp {{ number_format($loket->jml_komisi, 0, ',', '.')  }}</td>
                                <td class="align-middle border-bottom-0">
                                    <div class="gap-2 d-flex align-items-center">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('loket.edit', Crypt::encryptString($loket->kd_loket)) }}"  title="Edit" data-bs-toggle="tooltip">
                                            <i style="font-size: 20px;" class="text-black ti ti-edit"></i>
                                        </a>
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('loket.destroy', Crypt::encryptString($loket->kd_loket)) }}" method="POST" >
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('loket.destroy', Crypt::encryptString($loket->kd_loket)) }}"  onclick="notificationBeforeDelete(event, this)"
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
