@extends('layouts.backend')

@section('title', 'Pelanggan Tidak Aktif')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Pelanggan Tidak Aktif</h5>
                </div>
                <div class="p-4 card-body">
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
                                        <h6 class="mb-0 fw-semibold">Nama Pelanggan</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">ISP</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Cabang</h6>
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
                                            <p class="mb-0 fw-normal">{{ $pelanggan->nm_pelanggan }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $pelanggan->cabang->nm_cabang }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <!-- <p class="mb-0 fw-normal">{{ $pelanggan->isp->nm_isp }}</p> -->
                                            <p class="mb-0 fw-normal">{{ $pelanggan->paket->nm_paket }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a class=""
                                                    href="{{ route('pelangganTidakAktif.edit', Crypt::encryptString($pelanggan->kd_pelanggan)) }}"
                                                    title="Edit" data-bs-toggle="tooltip">
                                                    <span>
                                                        <i style="font-size: 20px;" class="ti ti-edit text-warning"></i>
                                                    </span>
                                                </a>

                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#showPelangganTidakAktifModal{{ $pelanggan->kd_pelanggan }}"
                                                    class="border-0" title="Show" data-bs-toggle="tooltip">
                                                        <span>
                                                            <i style="font-size: 20px;" class="ti ti-eye text-primary"></i>
                                                        </span>
                                                </a>
                                                    @include('pages.pelanggantidakaktif.show')


                                                <form
                                                    action="{{ route('pelangganTidakAktif.destroy', Crypt::encryptString($pelanggan->kd_pelanggan)) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="{{ route('pelangganTidakAktif.destroy', Crypt::encryptString($pelanggan->kd_pelanggan)) }}"
                                                        class="" onclick="notificationBeforeDelete(event, this)"
                                                        data-bs-toggle="tooltip" class="" title="Hapus"><i style="font-size: 20px;"
                                                            class="ti ti-trash-x text-danger"></i></a>
                                                </form>
                                                <a class=""
                                                    href="{{ route('pelangganTidakAktif.editScreet', Crypt::encryptString($pelanggan->kd_pelanggan)) }}"
                                                    title="secreet" data-bs-toggle="tooltip">
                                                    <span>
                                                        <i style="font-size: 20px;" class="ti ti-user-plus text-success"></i>
                                                    </span>
                                                </a>
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
