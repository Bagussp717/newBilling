@extends('layouts.backend')

@section('title', 'Edit Pelanggan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Edit Pelanggan</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: relative;">
                        <ul class="mt-2 mb-0">
                            <li>{{ $error }}</li>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                            style="position: absolute; top: 5px; right: 10px;">
                        </button>
                    </div>
                @endforeach
            @endif
            <!-- Form Wrapper -->
            <form action="{{ route('pelanggan.update', Crypt::encryptString($pelanggan->kd_pelanggan)) }}" method="POST"
                enctype="multipart/form-data" class="my-5">
                @csrf
                @method('PUT')

                {{-- disable di hilangkan dulu --}}
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pelanggan-tab" data-bs-toggle="tab" href="#pelanggan" role="tab"
                            aria-controls="pelanggan" aria-selected="true">Form Pelanggan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="teknisi-tab" data-bs-toggle="tab" href="#teknisi" role="tab"
                            aria-controls="teknisi" aria-selected="false">Form Secret</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="lokasi-tab" data-bs-toggle="tab" href="#lokasi" role="tab"
                            aria-controls="lokasi" aria-selected="false">Form Lokasi</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="myTabContent">
                    <!-- Form Pelanggan -->
                    <div class="tab-pane fade show active" id="pelanggan" role="tabpanel" aria-labelledby="pelanggan-tab">
                        <div class="my-4 row">
                            <div class="mb-3 col-md-6">
                                <label for="nm_pelanggan" class="form-label">Nama Pelanggan</label>
                                <input type="text" class="form-control @error('nm_pelanggan') is-invalid @enderror"
                                    name="nm_pelanggan" id="nm_pelanggan"
                                    value="{{ old('nm_pelanggan', $pelanggan->nm_pelanggan) }}"
                                    placeholder="Nama Pelanggan">
                                @error('nm_pelanggan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email', $pelanggan->user->email ?? ' ') }}" placeholder="Email">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Password Pelanggan (Kosongkan jika tidak diubah)">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="t_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" id="t_lahir"
                                    class="form-control @error('t_lahir') is-invalid @enderror" name="t_lahir"
                                    value="{{ old('t_lahir', $pelanggan->t_lahir) }}" placeholder="Tempat Lahir">
                                @error('t_lahir')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" id="tgl_lahir"
                                    class="form-control @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir"
                                    value="{{ old('tgl_lahir', $pelanggan->tgl_lahir) }}" placeholder="Tanggal Lahir">
                                @error('tgl_lahir')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="tgl_pemasangan" class="form-label">Tanggal Pemasangan</label>
                                <input type="date" id="tgl_pemasangan"
                                    class="form-control @error('tgl_pemasangan') is-invalid @enderror"
                                    name="tgl_pemasangan" placeholder="Tanggal Pemasangan"
                                    value="{{ old('tgl_pemasangan', $pelanggan->tgl_pemasangan) }}">
                                @error('tgl_pemasangan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" placeholder="Alamat">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3 form-group col-md-6">
                                <label for="no_telp" class="form-label">No. Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <svg width="24" height="16" viewBox="0 0 24 16"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect width="24" height="8" fill="#FF0000" />
                                            <rect y="8" width="24" height="8" fill="#FFFFFF" />
                                        </svg>
                                    </span>
                                    <input type="text" name="no_telp"
                                        class="form-control @error('no_telp') is-invalid @enderror"
                                        value="{{ old('no_telp', $pelanggan->no_telp) }}" aria-describedby="basic-addon1"
                                        placeholder="No. Telepon" oninput="formatTelepon(this)" maxlength="15">
                                </div>
                                @error('no_telp')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                <input type="text" id="pekerjaan"
                                    class="form-control @error('pekerjaan') is-invalid @enderror" name="pekerjaan"
                                    placeholder="Pekerjaan" value="{{ old('pekerjaan', $pelanggan->pekerjaan) }}">
                                @error('pekerjaan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="jenis_identitas" class="form-label">Jenis Identitas</label>
                                <select class="form-select @error('jenis_identitas') is-invalid @enderror"
                                    name="jenis_identitas">
                                    <option value="KTP"
                                        {{ old('jenis_identitas', $pelanggan->jenis_identitas) == 'KTP' ? 'selected' : '' }}>
                                        KTP
                                    </option>
                                    <option value="SIM"
                                        {{ old('jenis_identitas', $pelanggan->jenis_identitas) == 'SIM' ? 'selected' : '' }}>
                                        SIM
                                    </option>
                                    <option value="Paspor"
                                        {{ old('jenis_identitas', $pelanggan->jenis_identitas) == 'Paspor' ? 'selected' : '' }}>
                                        Paspor</option>
                                </select>
                                @error('jenis_identitas')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="no_identitas" class="form-label">Nomor Identitas</label>
                                <input type="text" id="no_identitas"
                                    class="form-control @error('no_identitas') is-invalid @enderror" name="no_identitas"
                                    value="{{ old('no_identitas', $pelanggan->no_identitas) }}"
                                    placeholder="Nomor Identitas">
                                @error('no_identitas')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- <div class="mb-3 col-md-6">
                            <label for="kd_paket" class="form-label">Paket Pelanggan</label>
                            <select name="kd_paket" class="form-select @error('kd_paket') is-invalid @enderror"
                                id="kd_paket">
                                <option value="">Pilih Paket</option>
                                @foreach ($pakets as $paket)
                                <option value="{{ $paket->kd_paket }}" {{ old('kd_paket', $pelanggan->kd_paket) ==
                                    $paket->kd_paket ? 'selected'
                                    : '' }}>
                                    {{ $paket->nm_paket }}
                                </option>
                                @endforeach
                            </select>
                            @error('kd_paket')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div> --}}
                            <div class="mb-3 col-md-6">
                                <label for="kd_paket" class="form-label">Paket Pelanggan</label>
                                <select name="kd_paket" class="form-select @error('kd_paket') is-invalid @enderror"
                                    id="kd_paket">
                                    <option value="">Pilih Paket</option>
                                    @foreach ($pakets as $paket)
                                        <option value="{{ $paket->kd_paket }}"
                                            {{ old('kd_paket', $pelanggan->kd_paket) == $paket->kd_paket ? 'selected' : '' }}>
                                            {{ $paket->nm_paket }} - {{ $paket->cabang->nm_cabang }}
                                            <!-- Display paket and related cabang name -->
                                        </option>
                                    @endforeach
                                </select>
                                @error('kd_paket')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="kd_loket" class="form-label">Loket Pembayaran</label>
                                <select name="kd_loket" class="form-select @error('kd_loket') is-invalid @enderror"
                                    id="kd_loket">
                                    <option value="">Pilih Loket</option>
                                    @foreach ($lokets as $loket)
                                        <option value="{{ $loket->kd_loket }}"
                                            {{ old('kd_loket', $pelanggan->kd_loket) == $loket->kd_loket ? 'selected' : '' }}>
                                            {{ $loket->nm_loket }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kd_loket')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6 @if (Auth::user()->hasRole('isp') || Auth::user()->hasRole('teknisi')) d-none @endif">
                                <label for="kd_isp" class="form-label">Kode ISP</label>
                                <select name="kd_isp" class="form-select @error('kd_isp') is-invalid @enderror"
                                    id="kd_isp" readonly>
                                    <option value="" disabled>Pilih ISP</option>
                                    @foreach ($isps as $isp)
                                        <option value="{{ $isp->kd_isp }}"
                                            {{ old('kd_isp', $pelanggan->kd_isp) == $isp->kd_isp ? 'selected' : '' }}>
                                            @if (Auth::user()->hasRole('isp') || Auth::user()->hasRole('teknisi'))
                                                {{ $isp->kd_isp }}
                                            @else
                                                {{ $isp->nm_isp }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('kd_isp')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="kd_odp" class="form-label">ODP</label>
                                <select name="kd_odp" class="form-select @error('kd_odp') is-invalid @enderror"
                                    id="kd_odp">
                                    <option value="">Pilih ODP</option>
                                    @foreach ($odps as $odp)
                                        <option value="{{ $odp->kd_odp }}"
                                            {{ old('kd_odp', $odp->kd_odp) == $odp->kd_odp ? 'selected' : '' }}>
                                            {{ $odp->nm_odp }}</option>
                                        </option>
                                    @endforeach
                                </select>
                                @error('kd_odp')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- Tombol Selanjutnya -->
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-warning d-flex align-items-center" id="nextToTeknisi">
                                Selanjutnya
                                <i class="ti ti-arrow-narrow-right ms-2" style="font-size: 1.5rem;"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Form Teknisi -->
                    <div class="tab-pane fade" id="teknisi" role="tabpanel" aria-labelledby="teknisi-tab">
                        <div class="my-4 row">
                            <div class="mb-3">
                                <label for="username_pppoe" class="form-label">Username</label>
                                <input type="text" id="username_pppoe"
                                    class="form-control @error('username_pppoe') is-invalid @enderror"
                                    placeholder="Username" name="username_pppoe"
                                    value="{{ old('username_pppoe', $pelanggan->username_pppoe) }}" readonly>
                                @error('username_pppoe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_pppoe" class="form-label">Password</label>
                                <input type="text" id="password_pppoe"
                                    class="form-control @error('password_pppoe') is-invalid @enderror"
                                    placeholder="Password" name="password_pppoe"
                                    value="{{ old('password_pppoe', $pelanggan->password_pppoe) }}" readonly>
                                @error('password_pppoe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="service_pppoe" class="form-label">Service</label>
                                <select id="service_pppoe"
                                    class="form-select @error('service_pppoe') is-invalid @enderror" name="service_pppoe"
                                    readonly>
                                    <option value="pppoe"
                                        {{ old('service_pppoe', $pelanggan->service_pppoe) == 'pppoe' ? 'selected' : '' }}>
                                        pppoe
                                    </option>
                                </select>
                                @error('service_pppoe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="profile_pppoe" class="form-label">Profile</label>
                                <input type="text" id="profile_pppoe"
                                    class="form-control @error('profile_pppoe') is-invalid @enderror"
                                    placeholder="Profile pppoe" name="profile_pppoe"
                                    value="{{ old('profile_pppoe', $pelanggan->profile_pppoe) }}" readonly>
                                @error('profile_pppoe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @error('profile_pppoe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                        <!-- Tombol Kembali dan Selanjutnya -->
                        <div class="gap-2 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary d-flex align-items-center"
                                id="backToPelanggan"><i class="ti ti-arrow-narrow-left me-2"
                                    style="font-size: 1.5rem;"></i>Kembali</button>
                            <button type="button" class="btn btn-warning d-flex align-items-center" id="nextToLokasi">
                                Selanjutnya
                                <i class="ti ti-arrow-narrow-right ms-2" style="font-size: 1.5rem;"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Form Lokasi -->
                    <div class="tab-pane fade" id="lokasi" role="tabpanel" aria-labelledby="lokasi-tab">
                        <div class="p-3">
                            <div id="map" style=""></div>
                        </div>
                        <div class="mb-3 d-flex justify-content-end">
                            <a id="get-location" class="btn btn-primary">Dapatkan Lokasi Saat Ini</a>
                        </div>
                        <div class="row">

                            <div class="mb-3 col-md-6">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control bg-light" name="lat" id="latitude"
                                    readonly value="{{ old('lat', $pelanggan->lat) }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control bg-light" name="long" id="longitude"
                                    readonly value="{{ old('long', $pelanggan->long) }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="kd_role" class="form-label">Role</label>
                                <select class="form-select @error('kd_role') is-invalid @enderror" name="kd_role"
                                    id="kd_role" readonly>
                                    <option value="pelanggan"
                                        {{ old('kd_role', $pelanggan->kd_role) == 'pelanggan' ? 'selected' : '' }}>
                                        pelanggan
                                    </option>
                                </select>
                                @error('kd_role')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="kd_cabang" class="form-label">Kode Cabang</label>
                                <select name="kd_cabang" class="form-select @error('kd_cabang') is-invalid @enderror"
                                    id="kd_cabang" readonly>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabangs as $cabang)
                                        <option value="{{ $cabang->kd_cabang }}"
                                            {{ old('kd_cabang', $pelanggan->kd_cabang) == $cabang->kd_cabang ? 'selected' : '' }}>
                                            {{ $cabang->nm_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kd_cabang')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            {{-- <div class="mb-3 col-md-6">
                            <label for="foto_rumah" class="form-label">Foto Rumah</label>
                            <input type="file" name="foto_rumah"
                                class="form-control @error('foto_rumah') is-invalid @enderror" id="foto_rumah"
                                aria-describedby="foto_rumah">
                            @if ($pelanggan->foto_rumah)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $pelanggan->foto_rumah) }}" alt="Foto Rumah"
                                    class="img-thumbnail" style="max-height: 200px;">
                            </div>
                            @endif
                            @error('foto_rumah')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div> --}}
                            <div class="mb-3 col-md-6">
                                <label for="foto_rumah" class="form-label">Foto Rumah</label>
                                <input type="file" name="foto_rumah"
                                    class="form-control @error('foto_rumah') is-invalid @enderror" id="foto_rumah"
                                    aria-describedby="foto_rumah">

                                <!-- Menampilkan gambar jika sudah diinput sebelumnya -->
                                @if ($pelanggan->foto_rumah)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $pelanggan->foto_rumah) }}"
                                            alt="Foto Rumah" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                @endif

                                @error('foto_rumah')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- Tombol Kembali dan Submit -->
                        <div class="gap-2 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary d-flex align-items-center"
                                id="backToTeknisi"><i class="ti ti-arrow-narrow-left me-2"
                                    style="font-size: 1.5rem;"></i>Kembali</button>
                            <button type="submit" class="btn btn-warning d-flex align-items-center"
                                id="submitBtn">Daftar</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nextToTeknisi = document.getElementById('nextToTeknisi');
            const nextToLokasi = document.getElementById('nextToLokasi');

            // Navigasi ke Tab Teknisi
            nextToTeknisi.addEventListener('click', function() {
                var nextTab = new bootstrap.Tab(document.querySelector('#teknisi-tab'));
                nextTab.show();
            });

            // Navigasi kembali ke Tab Pelanggan
            document.getElementById('backToPelanggan').addEventListener('click', function() {
                var prevTab = new bootstrap.Tab(document.querySelector('#pelanggan-tab'));
                prevTab.show();
            });

            // Navigasi ke Tab Lokasi
            nextToLokasi.addEventListener('click', function() {
                var nextTab = new bootstrap.Tab(document.querySelector('#lokasi-tab'));
                nextTab.show();
            });

            // Navigasi kembali ke Tab Teknisi
            document.getElementById('backToTeknisi').addEventListener('click', function() {
                var prevTab = new bootstrap.Tab(document.querySelector('#teknisi-tab'));
                prevTab.show();
            });
        });
    </script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var lat = -8.2117;
        var lng = 114.3676;

        // Inisialisasi peta dan set peta untuk terpusat di Banyuwangi
        var map = L.map('map').setView([lat, lng], 15);

        // Tambahkan tile layer (misalnya OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://semesta.co.id">Semesta Multitekno</a>'
        }).addTo(map);

        // Tambahkan marker untuk menunjukkan lokasi Banyuwangi
        var marker = L.marker([lat, lng]).addTo(map)
            .bindPopup('Banyuwangi, Indonesia')
            .openPopup();

        document.getElementById('lokasi-tab').addEventListener('click', function() {
            setTimeout(function() {
                map.invalidateSize();
            }, 100); // Beri sedikit delay untuk memastikan tab sudah benar-benar ditampilkan
        });

        document.getElementById('get-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var long = position.coords.longitude;

                    // Update peta dengan koordinat terkini
                    map.setView([lat, long], 20);

                    // Jika marker sudah ada, hapus marker lama
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    // Tambahkan marker baru di lokasi terkini
                    marker = L.marker([lat, long]).addTo(map)
                        .bindPopup('Lokasi Terkini Anda')
                        .openPopup();

                    // Update input tersembunyi
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = long;

                    // // Tampilkan koordinat di console (untuk debugging)
                    // console.log('Latitude: ' + lat);
                    // console.log('Longitude: ' + long);

                    // alert('Lokasi Terkini: \nLatitude: ' + lat + '\nLongitude: ' + long);
                }, function(error) {
                    console.error('Error: ' + error.message);
                    alert('Gagal mendapatkan lokasi. Pastikan lokasi diaktifkan di browser Anda.');
                });
            } else {
                alert('Geolocation tidak didukung oleh browser ini.');
            }
        });

        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    </script>
@endsection
