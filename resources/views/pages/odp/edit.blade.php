@extends('layouts.backend')

@section('title', 'Edit Data ODP')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Edit Data ODP</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('odp.update', Crypt::encryptString($odp->kd_odp)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="nm_odp" class="form-label">Nama ODP</label>
                        <input type="text" name="nm_odp" class="form-control @error('nm_odp') is-invalid @enderror"
                            id="nm_odp" placeholder="Nama ODP" value="{{ old('nm_odp', $odp->nm_odp) }}">
                        @error('nm_odp')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="kd_isp{{ $odp->kd_odp }}" class="form-label">Kode ISP</label>
                        <select name="kd_isp" class="form-control @error('kd_isp') is-invalid @enderror"
                            id="kd_isp{{ $odp->kd_odp }}">
                            @foreach ($isps as $isp)
                                <option value="{{ $isp->kd_isp }}"
                                    {{ old('kd_isp', $odp->kd_isp) == $isp->kd_isp ? 'selected' : '' }}>
                                    {{ $isp->nm_isp }}
                                </option>
                            @endforeach
                        </select>
                        @error('kd_isp')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Cabang -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="kd_cabang" class="form-label">Kode Cabang</label>
                        <select name="kd_cabang" class="form-select @error('kd_cabang') is-invalid @enderror"
                            id="kd_cabang">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->kd_cabang }}"
                                    {{ old('kd_cabang', $odp->kd_cabang) == $cabang->kd_cabang ? 'selected' : '' }}>
                                    {{ $cabang->nm_cabang }} - {{ $cabang->isp->nm_isp }}
                                </option>
                            @endforeach
                        </select>
                        @error('kd_cabang')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="foto_odp" class="form-label">Foto Rumah</label>
                        <input type="file" name="foto_odp" class="form-control @error('foto_odp') is-invalid @enderror"
                            id="foto_odp">

                        @error('foto_odp')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="row">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-6">
                                @if ($odp->foto_odp)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/foto_odp/' . $odp->foto_odp) }}" alt="Foto ODP"
                                            style="max-width: 300px;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="mt-2" id="map" style="height: 300px;"></div>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end">
                        <a id="get-location" class="btn btn-primary">Dapatkan Lokasi Saat Ini</a>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control bg-light" name="lat" id="latitude"
                            value="{{ old('lat', $odp->lat) }}" readonly>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control bg-light" name="long" id="longitude"
                            value="{{ old('long', $odp->long) }}" readonly>
                    </div>
                </div>

                <!-- Tombol Kembali dan Submit -->
                <div class="gap-2 d-flex justify-content-end">
                    <a href="{{ route('odp.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leaflet Map JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var lat = {{ $odp->lat ?? -8.2117 }};
        var lng = {{ $odp->long ?? 114.3676 }};

        // Inisialisasi peta dan set peta untuk terpusat di lokasi yang ada
        var map = L.map('map').setView([lat, lng], 15);

        // Tambahkan tile layer (misalnya OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://semesta.co.id">Semesta Multitekno</a>'
        }).addTo(map);

        // Tambahkan marker untuk menunjukkan lokasi yang ada
        var marker = L.marker([lat, lng]).addTo(map)
            .bindPopup('Lokasi Saat Ini')
            .openPopup();

        document.getElementById('get-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var long = position.coords.longitude;

                    // Update peta dengan koordinat terkini
                    map.setView([lat, long], 13);

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

                    console.log('Latitude: ' + lat);
                    console.log('Longitude: ' + long);
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
