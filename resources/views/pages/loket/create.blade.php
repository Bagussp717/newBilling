@extends('layouts.backend')

@section('title', 'Tambah Data Loket')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Tambah Data Loket</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('loket.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Nama Loket -->
                <div class="mb-3 col-md-6">
                    <label for="nm_loket" class="form-label">Nama Loket</label>
                    <input type="text" name="nm_loket" class="form-control @error('nm_loket') is-invalid @enderror"
                        value="{{ old('nm_loket') }}" id="nm_loket" placeholder="Masukkan Nama Loket">
                    @error('nm_loket')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

               <!-- ISP -->
                <div class="mb-3 col-md-6">
                    <label for="kd_isp" class="form-label">ISP</label>
                    <select class="form-select @error('kd_isp') is-invalid @enderror" name="kd_isp" id="kd_isp">
                        <option value="" disabled selected>Pilih ISP</option>
                        @foreach ($isps as $isp)
                        <option value="{{ $isp->kd_isp }}" {{ old('kd_isp') == $isp->kd_isp ? 'selected' : '' }}>
                            {{ $isp->nm_isp }}
                        </option>
                        @endforeach
                    </select>
                    @error('kd_isp')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <!-- Cabang -->
                <div class="mb-3 col-md-6">
                    <label for="kd_cabang" class="form-label">Cabang</label>
                    <select class="form-select @error('kd_cabang') is-invalid @enderror" name="kd_cabang" id="kd_cabang">
                        <option value="" disabled selected>Pilih Cabang</option>
                        @foreach ($cabangs as $cabang)
                        <option value="{{ $cabang->kd_cabang }}" {{ old('kd_cabang') == $cabang->kd_cabang ? 'selected' : '' }}>
                            {{ $cabang->nm_cabang }}
                        </option>
                        @endforeach
                    </select>
                    @error('kd_cabang')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Alamat Loket -->
                <div class="mb-3 col-md-6">
                    <label for="alamat_loket" class="form-label">Alamat Loket</label>
                    <textarea name="alamat_loket" class="form-control @error('alamat_loket') is-invalid @enderror"
                        id="alamat_loket" rows="3" placeholder="Masukkan Alamat Loket">{{ old('alamat_loket') }}</textarea>
                    @error('alamat_loket')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" id="email" placeholder="Masukkan Email">
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3 col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        id="password" placeholder="Masukkan Password">
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Jenis Komisi -->
                <div class="mb-3 col-md-6">
                    <label for="jenis_komisi" class="form-label">Jenis Komisi</label>
                    <select class="form-select @error('jenis_komisi') is-invalid @enderror" name="jenis_komisi" id="jenis_komisi">
                        <option value="" disabled selected>Pilih Jenis Komisi</option>
                        <option value="fixed" {{ old('jenis_komisi') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        <option value="dynamic" {{ old('jenis_komisi') == 'dynamic' ? 'selected' : '' }}>Dynamic</option>
                    </select>
                    @error('jenis_komisi')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Jumlah Komisi -->
                <div class="mb-3 col-md-6">
                    <label for="jml_komisi" class="form-label">Jumlah Komisi</label>
                    <input type="number" name="jml_komisi" class="form-control @error('jml_komisi') is-invalid @enderror"
                        value="{{ old('jml_komisi', $loket->jml_komisi ?? 0) }}" id="jml_komisi" placeholder="Masukkan Jumlah Komisi">
                    @error('jml_komisi')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-3 col-md-6">
                    <label for="kd_role" class="form-label">Role</label>
                    <select class="form-select @error('kd_role') is-invalid @enderror" name="kd_role" id="kd_role" readonly>
                        <option value="loket" selected>loket</option>
                    </select>
                    @error('kd_role')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('loket.index') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
