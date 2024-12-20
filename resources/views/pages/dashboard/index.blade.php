@extends('layouts.backend')

@section('title', 'Dashboard')

@section('content')
    @role('super-admin|isp|teknisi')
        {{-- Dashboard --}}
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="justify-center row align-items-center">
                        @if (isset($error))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: relative;">
                                <strong class="font-bold">Error:</strong>
                                <span class="mt-2 mb-0">{{ $error }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    style="position: absolute; top: 2px; right: 10px;">
                                </button>
                            </div>
                        @endif

                        <div class="col-12 col-md-5">
                            <h4>Hi, {{ Auth::user()->name }}</h4>
                        </div>
                        <div class="col-12 col-md-7">
                            <form action="{{ route('dashboard.login') }}" method="POST">
                                @csrf
                                <p for="kd_cabang" class="mb-0" style="font-size: 0.7rem; color: red"><em>*Pilih cabang
                                        mikrotik untuk memunculkan data di halaman dashboard</em></p>
                                <div class="input-group">
                                    <select class="form-select @error('kd_cabang') is-invalid @enderror" name="kd_cabang"
                                        id="kd_cabang" required>
                                        <option value="" disabled selected>Pilih Cabang</option>
                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->kd_cabang }}"
                                                {{ session('kd_cabang') == $cabang->kd_cabang ? 'selected' : '' }}>
                                                {{ $cabang->nm_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-success">Hubungkan</button>
                                </div>
                                @error('kd_cabang')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @role('teknisi')
            <div class="mt-3 row">
                <div class="col-12 col-md-4 col-xl-4">
                    <a href="{{ route('tiket.index') }}" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-8">
                                        <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem"> Tiket Aktif </h5>
                                        <h5 class="mb-3 fw-semibold">{{ $activeTicketsCount }}</h5>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-end">
                                            <div
                                                class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="ti ti-mail-forward fs-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-4 col-xl-4">
                    <a href="{{ route('tiket.index') }}" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-8">
                                        <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem"> Dalam Proses </h5>
                                        <h5 class="mb-3 fw-semibold">{{ $inProgressTicketsCount }}</h5>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-end">
                                            <div
                                                class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="ti ti-mail-opened fs-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-4 col-xl-4">
                    <a href="{{ route('tiket.doneindex') }}" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-8">
                                        <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem"> Selesai </h5>
                                        <h5 class="mb-3 fw-semibold">{{ $completedTicketsCount }}</h5>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-end">
                                            <div
                                                class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="ti ti-mail fs-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endrole
        <div class="mt-3 row">
            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('nonActiveDashboard') }}" class="text-decoration-none">
                    <div class="card" style="background-color: #ffcccc">
                        <!-- Warna merah muda untuk pelanggan tidak aktif -->
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem">Pelanggan tidak aktif</h5>
                                    <h4 class="mb-3 fw-semibold">{{ $secretnonactiveCount ?? 0 }} Pelanggan</h4>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ti-user-x fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('activeDashboard') }}" class="text-decoration-none">
                    <div class="card" style="background-color: #ccffcc"> <!-- Warna hijau muda untuk pelanggan aktif -->
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem">Pelanggan aktif</h5>
                                    <h4 class="mb-3 fw-semibold">{{ $activeCount ?? 0 }} Pelanggan</h4>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ti-user-check fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('secretDashboard') }}" class="text-decoration-none">
                    <div class="card" style="background-color: #cceeff"> <!-- Warna biru muda untuk semua pelanggan -->
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem">Semua pelanggan</h5>
                                    <h4 class="mb-3 fw-semibold">{{ $secretCount ?? 0 }} Pelanggan</h4>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ti-users fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        @role('isp')
            <div class="mt-3 row">
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card" style="background-color: #ccdbff"> <!-- Warna merah muda untuk pelanggan tidak aktif -->
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem">Semua pelanggan</h5>
                                    <h4 class="mb-3 fw-semibold">{{ $jumlahSemuaPelanggan ?? 0 }}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ti-users fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card" style="background-color: #ecffcc"> <!-- Warna merah muda untuk pelanggan tidak aktif -->
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem">Pelanggan baru</h5>
                                    <h4 class="mb-3 fw-semibold">{{ $jumlahPelangganBaruBulanSekarang ?? 0 }}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ti-users fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card" style="background-color: #fcffcc"> <!-- Warna merah muda untuk pelanggan tidak aktif -->
                        <div class="card-body">
                            <div class="row align-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem">Berhenti berlangganan</h5>
                                    <h4 class="mb-3 fw-semibold">{{ $jumlahPelangganBerhenti ?? 0 }}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ti-users fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endrole

        {{-- End Dashboard --}}

        {{-- Elementw --}}

        @php
            function convertMikrotikDate($dateString)
            {
                $months = [
                    'jan' => '01',
                    'feb' => '02',
                    'mar' => '03',
                    'apr' => '04',
                    'may' => '05',
                    'jun' => '06',
                    'jul' => '07',
                    'aug' => '08',
                    'sep' => '09',
                    'oct' => '10',
                    'nov' => '11',
                    'dec' => '12',
                ];

                $parts = explode('/', $dateString);

                if (count($parts) == 3) {
                    $monthName = strtolower($parts[0]);
                    $day = $parts[1];
                    $yearAndTime = explode(' ', $parts[2]);
                    $year = $yearAndTime[0];
                    $time = $yearAndTime[1];

                    if (array_key_exists($monthName, $months)) {
                        $month = $months[$monthName];
                        return "{$year}-{$month}-{$day} {$time}";
                    }
                }

                return null;
            }

            // Fungsi untuk mengurutkan berdasarkan tanggal 'last-logged-out'
            function sortByLastLoggedOut($a, $b)
            {
                $dateA = isset($a['last-logged-out']) ? strtotime(convertMikrotikDate($a['last-logged-out'])) : 0;
                $dateB = isset($b['last-logged-out']) ? strtotime(convertMikrotikDate($b['last-logged-out'])) : 0;

                return $dateB <=> $dateA; // Urutkan descending
            }

            // Urutkan data berdasarkan 'last-logged-out'
            usort($data, 'sortByLastLoggedOut');
        @endphp

        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5 class="card-title">Status Pelanggan</h5>
                    </div>
                    <div class="p-4 card-body">
                        <ul class="gap-3 mb-3 nav">
                            <li class="nav-item">
                                <a class="btn btn-primary" href="{{ route('secretDashboard') }}">Secret</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="btn btn-primary" href="{{ route('activeDashboard') }}">Active</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="btn btn-primary" href="{{ route('nonActiveDashboard') }}">Non Active</a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table class="table border table-striped" id="dataTable" style="width: 100%;">
                                <thead class="mb-0 align-middle text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0">
                                            <h6 class="mb-0 fw-semibold">No</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="mb-0 fw-semibold">Nama</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="mb-0 fw-semibold">
                                                {{ isset($data[0]['last-logged-out']) ? 'Terakhir Keluar' : 'Service' }}
                                            </h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="mb-0 fw-semibold">
                                                {{ isset($data[0]['disabled']) ? 'Status' : 'Waktu Aktif' }}
                                            </h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="mb-0 fw-semibold">
                                                {{ isset($data[0]['password']) ? 'Password' : 'Caller ID' }}
                                            </h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="mb-0 fw-semibold">
                                                {{ isset($data[0]['profile']) ? 'Profile' : 'Address' }}
                                            </h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $items)
                                        <tr>
                                            <td class="align-middle border-bottom-0">
                                                <h6 class="mb-0 fw-normal">{{ $key + 1 }}</h6>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">{{ $items['name'] ?? '-' }}</p>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">
                                                    @php
                                                        $parsedDate = isset($items['last-logged-out'])
                                                            ? convertMikrotikDate($items['last-logged-out'])
                                                            : null;
                                                    @endphp
                                                    {{ $parsedDate ? \Carbon\Carbon::parse($parsedDate)->format('d-m-Y H:i') : $items['service'] }}
                                                </p>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">
                                                    @if (isset($items['disabled']) && $items['disabled'] == 'true')
                                                        <span class="badge badge-danger">{{ $items['disabled'] }}</span>
                                                    @elseif(isset($items['disabled']) && $items['disabled'] == 'false')
                                                        <span class="badge badge-success">{{ $items['disabled'] }}</span>
                                                    @else
                                                        {{ $items['uptime'] }}
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">
                                                    {{ $items['password'] ?? $items['caller-id'] }}
                                                </p>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <P class="mb-0 fw-normal">{{ $items['profile'] ?? $items['address'] }}</P>
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

        {{-- End Element --}}
    @endrole
    {{-- Dashboard pelanggan --}}

    @role('pelanggan')
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="justify-center row align-items-center">

                        <div class="col-12 col-md-5">
                            <h4>Hi, {{ Auth::user()->name }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 row">
            <div class="col-12 col-md-4 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem"> email </h5>
                                <h5 class="mb-3 fw-semibold">{{ $user->email }}</h5>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div
                                        class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ti ti-users fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem"> username mikrotik </h5>
                                <h5 class="mb-3 fw-semibold">{{ $user->username_pppoe }}</h5>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div
                                        class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ti ti-user-check fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-12 col-md-4 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold" style="font-size: 1rem"> Role </h5>
                                <h5 class="mb-3 fw-semibold">{{ $user->kd_role }}</h5>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div
                                        class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ti ti-user-x fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endrole
    @role('loket')
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-center">Maaf Data loket tidak tersedia</h4>
                            <p class="text-center">Silahkan hubungi isp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endrole
@endsection
