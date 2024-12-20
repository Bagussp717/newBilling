@extends('layouts.mikrotik')

@section('title', 'Dashboard Mikrotik')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">Selamat Datang di Cabang {{ session('cabang_nama') }}</h4>
                        <p class="text-center">IP Mikrotik: {{ session('ip') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-semibold">Router Board Name : {{ $identity }}</h4>
    <div class="mt-5 row">
        <div class="col-12 col-md-4 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> CPU </h5>
                            <div id="cpu-load-placeholder"></div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-cpu fs-6"></i>
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
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> Memory </h5>
                            <div id="memory-load-placeholder"></div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-device-sd-card fs-6"></i>
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
                    <div class="row alig n-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold"> HDD </h5>
                            <div id="hdd-load-placeholder"></div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-database fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row alig n-items-start">
                        <div class="col-12">
                            <h5 class="card-title mb-9 fw-semibold"> Traffict Monitor </h5>
                            <div class="row">
                                <!-- Kolom untuk dropdown select -->
                                <div class="col-lg-4">
                                    <select name="interface" id="interface" class="mb-2 form-select">
                                        @foreach ($interface as $data)
                                            <option value="{{ $data['name'] }}"
                                                {{ $data['name'] == 'ether 1' ? 'selected' : '' }}>
                                                {{ $data['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-8">
                                    <div class="d-flex flex-column align-items-end">
                                        <p id="txValue" class="mt-2 mb-1 text-sm text-uppercase" style="font-size: 10px">
                                        </p>
                                        <p id="rxValue" class="mt-2 mb-1 text-sm text-uppercase" style="font-size: 10px">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div id="trafficMonitor"></div>
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
                            <h5 class="card-title mb-9 fw-semibold">Uptime</h5>
                            <div id="uptime-load-placeholder">
                                <div id="spinner1" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div
                                    class="p-6 text-white bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-clock-hour-10 fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 row">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-4 fw-semibold">Pelanggan</p>
                            <p class="fw-semibold">{{ $totalscreet }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-4 fw-semibold">Pelanggan Aktif</p>
                            <p class="fw-semibold">{{ $secretactive }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-4 fw-semibold">Pelanggan Tidak Aktif</p>
                            <p class="fw-semibold">{{ $totalnonactive }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>

    <script src="{{ asset('assets/libs/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>
@endsection
