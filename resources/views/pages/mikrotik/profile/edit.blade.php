@extends('layouts.mikrotik')

@section('title', 'Edit Profile Mikrotik')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Edit Profile Mikrotik</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('profilemikrotik.update', $paket->nm_paket) }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="nm_paket" class="form-label">Nama Paket</label>
                    <input type="text" name="nm_paket" class="form-control @error('nm_paket') is-invalid @enderror"
                        value="{{ old('nm_paket', $paket->nm_paket) }}" id="nm_paket" placeholder="Nama Paket">
                    @error('nm_paket')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="mb-3 col-md-6">
                    <label for="hrg_paket" class="form-label">Harga Paket</label>
                    <input type="number" name="hrg_paket" class="form-control @error('hrg_paket') is-invalid @enderror"
                        value="{{ old('hrg_paket', $paket->hrg_paket) }}" id="hrg_paket" placeholder="Harga Paket">
                    @error('hrg_paket')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="local_address" class="form-label">Local Address</label>
                    <input type="text" name="local_address"
                        class="form-control @error('local_address') is-invalid @enderror"
                        value="{{ old('local_address', $paket->local_address) }}" id="local_address"
                        placeholder="Local Address">
                    @error('local_address')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="mb-3 col-md-6">
                    <label for="remote_address" class="form-label">Remote Address</label>
                    <select name="remote_address" class="form-select @error('remote_address') is-invalid @enderror"
                        id="remote_address">
                        <option disabled selected value="">Pilih Profile</option>
                        @foreach ($ippool as $data)
                        <option value="{{ $data['name'] }}" {{ old('remote_address', $paket->remote_address) ==
                            $data['name'] ? 'selected' : '' }}>
                            {{ $data['name'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('remote_address')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="rate_limit" class="form-label">Rate Limit</label>
                    <input type="text" name="rate_limit" class="form-control @error('rate_limit') is-invalid @enderror"
                        value="{{ old('rate_limit', $paket->rate_limit) }}" id="rate_limit" placeholder="Rate Limit">
                    @error('rate_limit')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="mb-3 col-md-6 d-none">
                    <label for="kd_cabang" class="form-label">Kode Cabang</label>
                    <select name="kd_cabang" class="form-select @error('kd_cabang') is-invalid @enderror"
                        id="kd_cabang">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabangs as $cabang)
                        <option value="{{ $cabang->kd_cabang }}" {{ old('kd_cabang', $paket->kd_cabang) ==
                            $cabang->kd_cabang ? 'selected' : '' }}>
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
            </div>
            <div class="gap-2 d-flex">
                <a href="{{ route('profilemikrotik.index') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
