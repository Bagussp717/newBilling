@extends('layouts.backend')

@section('title', 'Tambah Secret')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tambah Data Secret</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pelangganTidakAktif.updateScreet', Crypt::encryptString($pelanggan->kd_pelanggan)) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3">
                        <label for="username_pppoe" class="form-label">Username</label>
                        <input type="text" id="username_pppoe"
                            class="form-control @error('username_pppoe') is-invalid @enderror" placeholder="Username"
                            name="username_pppoe" value="{{ old('username_pppoe') }}">
                        @error('username_pppoe')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_pppoe" class="form-label">Password</label>
                        <input type="text" id="password_pppoe"
                            class="form-control @error('password_pppoe') is-invalid @enderror" placeholder="Password"
                            name="password_pppoe" value="{{ old('password_pppoe') }}">
                        @error('password_ppoe')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="service_pppoe" class="form-label">Service</label>
                        <select id="service_pppoe" class="form-control @error('service_pppoe') is-invalid @enderror"
                            name="service_pppoe">
                            <option value="pppoe" selected>pppoe</option>
                        </select>
                        @error('service_pppoe')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="profile_pppoe" class="form-label">Profile</label>
                        <select id="profile_pppoe" class="form-select @error('profile_pppoe') is-invalid @enderror"
                            name="profile_pppoe">
                            <option value="" disabled selected>Pilih Profile</option>
                            @foreach ($profile as $data)
                                <option>{{ $data['name'] }}</option>
                            @endforeach
                        </select>
                        @error('profile_pppoe')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="gap-2 d-flex">
                    <a href="{{ route('pelangganTidakAktif.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
