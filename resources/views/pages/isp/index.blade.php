@extends('layouts.backend')

@section('title', 'Data ISP')

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-header">
                <h5 class="card-title">Data ISP</h5>
            </div>
            <div class="p-4 card-body">
                <a href="{{ route('isp.create') }}" class="mb-4 btn btn-primary" data-bs-toggle="tooltip"
                    title="Create">Tambah Data ISP</a>
                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTable" style=" width: 100%;
                                                    min-width: 0;
                                                    max-width: 200px;
                                                    white-space: nowrap;">
                            <thead class="mb-0 align-middle text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">No. </h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Nama ISP</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Nama Brand</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Whatsapp</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Alamat</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">Aksi</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($isp as $value => $itemIsp)
                            <tr>
                                <td class="align-middle border-bottom-0">
                                    <h6 class="mb-0 fw-normal">{{ $value + 1 }}</h6>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <h6 class="mb-0 fw-normal">{{ $itemIsp->user->name }}</h6>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <h6 class="mb-0 fw-normal">{{ $itemIsp->nm_brand}}</h6>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $itemIsp->no_telp }}</p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <p class="mb-0 fw-normal">{{ $itemIsp->alamat }}</p>
                                </td>
                                <td class="align-middle border-bottom-0">
                                    <div class="gap-2 d-flex align-items-center">
                                        <a href="{{ route('isp.edit', Crypt::encryptString($itemIsp->kd_isp)) }}" data-bs-toggle="tooltip"
                                            title="Edit">
                                            <span>
                                                <i style="font-size: 20px;" class="text-black ti ti-edit"></i>
                                            </span>
                                        </a>

                                        <form action="{{ route('isp.destroy', Crypt::encryptString($itemIsp->kd_isp)) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('isp.destroy', Crypt::encryptString($itemIsp->kd_isp)) }}"
                                                onclick="notificationBeforeDelete(event, this)" data-bs-toggle="tooltip"
                                                title="Hapus"><span>
                                                    <i style="font-size: 20px; color: #A02334;" class="ti ti-trash"></i>
                                                </span></a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
