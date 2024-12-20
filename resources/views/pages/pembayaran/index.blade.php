{{-- @extends('layouts.backend')

@section('title', 'Data Loket')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Loket</h5>
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
                                        <h6 class="mb-0 fw-semibold">Nama Loket</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembayarans as $value => $pembayaran)
                                    <tr>
                                        <td class="align-middle border-bottom-0">
                                            <h6 class="mb-0 fw-normal">{{ $value + 1 }}</h6>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $pembayaran->loket->nm_loket }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $pembayaran->tgl_bayar }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="{{ route('pembayaran.show', $pembayaran->kd_pembayaran) }}"
                                                    class="btn btn-warning" data-bs-toggle="tooltip" title="Show">Detail
                                                    Pembayaran</a>
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
@endsection --}}
