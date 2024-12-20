@extends('layouts.backend')

@section('title', 'Tambah Data ISP')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tambah Data ISP</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('isp.create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="nm_isp" class="form-label">Nama ISP</label>
                        <input type="text" name="nm_isp" class="form-control @error('nm_isp') is-invalid @enderror"
                            value="{{ old('nm_isp') }}" id="nm_isp" aria-describedby="nm_isp" placeholder="Nama ISP">
                        @error('nm_isp')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" id="email" aria-describedby="email" placeholder="Email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            value="{{ old('password') }}" id="password" aria-describedby="password" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="nm_brand" class="form-label">Nama Brand</label>
                        <input type="text" name="nm_brand" class="form-control @error('nm_brand') is-invalid @enderror"
                            value="{{ old('nm_brand') }}" id="nm_brand" aria-describedby="nm_brand"
                            placeholder="Nama Brand">
                        @error('nm_brand')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="alamat" class="form-label">Alamat</label>
                        <Textarea type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                            aria-describedby="alamat" placeholder="Alamat">{{ old('alamat') }}</Textarea>
                        @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 form-group col-md-6">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                <svg width="24" height="16" viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="24" height="8" fill="#FF0000" />
                                    <rect y="8" width="24" height="8" fill="#FFFFFF" />
                                </svg>
                            </span>
                            <input type="text" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror"
                                value="{{ old('no_telp', '62') }}" id="no_telp" aria-describedby="basic-addon1"
                                placeholder="No. Telepon" oninput="formatTelepon(this)" maxlength="15">
                        </div>
                        @error('no_telp')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                            value="{{ old('logo') }}" id="logo" aria-describedby="logo">
                        @error('logo')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="kd_role" class="form-label">Role</label>
                        <select class="form-select @error('kd_role') is-invalid @enderror" name="kd_role" id="kd_role"
                            readonly>
                            <option value="isp" selected>isp</option>
                        </select>
                        @error('kd_role')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="gap-2 d-flex">
                    <a href="{{ route('isp.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
