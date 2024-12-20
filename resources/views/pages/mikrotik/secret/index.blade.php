@extends('layouts.mikrotik')

@section('title', 'Pelanggan')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Secreet Mikrotik</h5>
                </div>
                <div class="p-4 card-body">
                    <div class="mb-5 row">
                        <div class="mb-2 col-md-6">
                            <form action="{{ route('isolir') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <select name="tgl_akhir" class="form-select @error('tgl_akhir') is-invalid @enderror"
                                        required>
                                        <option value="" selected>pilih tanggal</option>
                                        @foreach ($uniqueInvoices as $invoice)
                                            <option value="{{ $invoice->tgl_akhir }}"
                                                {{ old('tgl_akhir') == $invoice->tgl_akhir ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->format('d M Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tgl_akhir')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <button type="submit" class="btn btn-danger">Isolir Semua Pelanggan</button>
                                </div>
                            </form>
                        </div>
                        <div class="mb-2 col-md-6">
                            <form action="{{ route('recover') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <select name="tgl_akhir" class="form-select @error('tgl_akhir') is-invalid @enderror"
                                        required>
                                        <option value="" selected>pilih tanggal</option>
                                        @foreach ($uniqueInvoices as $invoice)
                                            <option value="{{ $invoice->tgl_akhir }}"
                                                {{ old('tgl_akhir') == $invoice->tgl_akhir ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->format('d M Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tgl_akhir')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <button type="submit" class="btn btn-success">Pulihkan Semua Pelanggan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('secretMicrotik.create') }}" class="mb-4 btn btn-primary" data-bs-toggle="tooltip"
                        title="Create">Tambah Secret Mikrotik</a>
                    @role('super-admin|isp')
                        <a href="#" class="mb-4 btn btn-primary" data-bs-toggle="modal" data-bs-target="#syncSecretModal"
                            title="Sync">Sinkron Secret Mikrotik</a>
                    @endrole

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
                                        <h6 class="mb-0 fw-semibold">Nama Secret</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Last Logged Out</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Disabled</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Password</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Profile</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Service</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 text-center fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($secret as $value => $items)
                                    <tr>
                                        <div hidden>{{ $username_pppoe = $items['name'] }}</div>
                                        <td class="align-middle border-bottom-0">
                                            <h6 class="mb-0 fw-normal">{{ $value + 1 }}</h6>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $items['name'] }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (array_key_exists('last-logged-out', $items))
                                                    {{ $items['last-logged-out'] }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (array_key_exists('disabled', $items))
                                                    {{ $items['disabled'] }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (array_key_exists('password', $items))
                                                    {{ $items['password'] }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (array_key_exists('profile', $items))
                                                    {{ $items['profile'] }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (array_key_exists('service', $items))
                                                    {{ $items['service'] }}
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="{{ route('secretMikrotik.edit', $username_pppoe) }}">
                                                    <span>
                                                        <i style="font-size: 20px;" class="text-black ti ti-edit"></i>
                                                    </span>
                                                    </i>
                                                </a>

                                                {{-- <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#showPelangganModal{{ $username_pppoe }}"
                                                    title="Show" data-bs-toggle="tooltip">
                                                    <span>
                                                        <i style="font-size: 20px;" class="ti ti-eye text-primary"></i>
                                                    </span>
                                                </a> --}}

                                                @role('super-admin|isp')
                                                    {{-- <a href="#" class="btn-delete" data-nm-pppoe="{{ $username_pppoe }}"
                                                        data-action-url="{{ route('secretMikrotik.destroy', $username_pppoe) }}"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <span>
                                                            <i style="font-size: 20px;" class="ti ti-file-x text-secondary"></i>
                                                        </span>
                                                    </a> --}}
                                                    <a href="#" class="border-0" data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ str_replace(' ', '-', $username_pppoe) }}" 
                                                        title="Hapus">
                                                            <span>
                                                                <i style="font-size: 20px;" class="ti ti-file-x text-secondary"></i>
                                                            </span>
                                                    </a>


                                                    {{-- <a href="#" class="btn-delete1" data-nm-pppoe="{{ $username_pppoe }}"
                                                        data-action-url="{{ route('secretMikrotik.destroy1', $username_pppoe) }}"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <span>
                                                            <i style="font-size: 20px;" class="ti ti-trash-x text-danger"></i>
                                                        </span>
                                                    </a> --}}
                                                    <a href="#" class="border-0" data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal1{{ str_replace(' ', '-', $username_pppoe) }}" 
                                                        title="Hapus">
                                                            <span>
                                                                <i style="font-size: 20px;" class="ti ti-trash-x text-danger"></i>
                                                            </span>
                                                    </a>
                                                @endrole
                                                @include('pages.mikrotik.secret.delete')
                                                @include('pages.mikrotik.secret.delete1')
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

    <!-- Modal Sinkronisasi Secret Mikrotik -->
    <div class="modal fade" id="syncSecretModal" tabindex="-1" aria-labelledby="syncSecretModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="syncSecretModalLabel">Sinkronisasi Secret Mikrotik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin melakukan sinkronisasi Secret dari Mikrotik ke database? <br> Data yang ada
                        di Mikrotik akan ditambahkan ke database jika belum ada.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('secretMikrotik.sync') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Sinkron Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
