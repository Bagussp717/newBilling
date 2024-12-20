@extends('layouts.backend')

@section('title', 'Tambah Cabang')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tambah Data Cabang</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('cabang.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nm_cabang" class="form-label">Nama Cabang</label>
                    <input type="text" name="nm_cabang"
                        class="form-control @error('nm_cabang') is-invalid
                    @enderror"
                        value="{{ old('nm_cabang') }}" id="nm_cabang" aria-describedby="nm_cabang"
                        placeholder="Nama Cabang">
                    @error('nm_cabang')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="alamat_cabang" class="form-label">Alamat Cabang</label>
                    <input type="text" name="alamat_cabang"
                        class="form-control @error('alamat_cabang') is-invalid
                    @enderror"
                        value="{{ old('alamat_cabang') }}" id="alamat_cabang" aria-describedby="alamat_cabang"
                        placeholder="Alamat Cabang">
                    @error('alamat_cabang')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="username_mikrotik" class="form-label">Username Mikrotik</label>
                    <input type="text" name="username_mikrotik"
                        class="form-control @error('username_mikrotik') is-invalid @enderror"
                        value="{{ old('username_mikrotik') }}" id="username_mikrotik" aria-describedby="username_mikrotik"
                        placeholder="Username Mikrotik">
                    @error('username_mikrotik')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="ip_mikrotik" class="form-label">Ip Mikrotik</label>
                    <input type="text" name="ip_mikrotik" class="form-control @error('ip_mikrotik') is-invalid @enderror"
                        value="{{ old('ip_mikrotik') }}" id="ip_mikrotik" aria-describedby="ip_mikrotik"
                        placeholder="Ip Mikrotik">
                    @error('ip_mikrotik')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_mikrotik" class="form-label">Password Mikrotik</label>
                    <input type="password" name="password_mikrotik"
                        class="form-control @error('password_mikrotik') is-invalid @enderror"
                        value="{{ old('password_mikrotik') }}" id="password_mikrotik" aria-describedby="password_mikrotik"
                        placeholder="Password Mikrotik">
                    @error('password_mikrotik')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kd_isp" class="form-label">Kode ISP</label>
                    <select name="kd_isp" class="form-select @error('kd_isp') is-invalid @enderror" id="kd_isp">
                        <option value="">Pilih ISP</option>
                        @foreach ($isps as $isp)
                            <option value="{{ $isp->kd_isp }}" {{ old('kd_isp') == $isp->kd_isp ? 'selected' : '' }}>
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
                <div class="gap-2 d-flex">
                    <a href="{{ route('cabang.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
