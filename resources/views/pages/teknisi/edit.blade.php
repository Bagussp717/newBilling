@extends('layouts.backend')

@section('title', 'Edit Data Teknisi')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Edit Data Teknisi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('teknisi.update', Crypt::encryptString($teknisi->kd_teknisi)) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="nm_teknisi" class="form-label">Nama Teknisi</label>
                        <input type="text" name="nm_teknisi"
                            class="form-control @error('nm_teknisi') is-invalid @enderror"
                            value="{{ old('nm_teknisi', $teknisi->nm_teknisi) }}" id="nm_teknisi"
                            aria-describedby="nm_teknisi" placeholder="Nama Teknisi">
                        @error('nm_teknisi')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email Teknisi</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $teknisi->user->email) }}" id="email" aria-describedby="email"
                            placeholder="Email Teknisi">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="password" class="form-label">Password Teknisi</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            value="{{ old('password') }}" id="password" aria-describedby="password"
                            placeholder="Password Teknisi (Kosongkan jika tidak diubah)">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="t_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="t_lahir" class="form-control @error('t_lahir') is-invalid @enderror"
                            value="{{ old('t_lahir', $teknisi->t_lahir) }}" id="t_lahir" aria-describedby="t_lahir"
                            placeholder="Tempat Lahir">
                        @error('t_lahir')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror"
                            value="{{ old('tgl_lahir', $teknisi->tgl_lahir) }}" id="tgl_lahir" aria-describedby="tgl_lahir"
                            placeholder="Tanggal Lahir">
                        @error('tgl_lahir')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tgl_aktif" class="form-label">Tanggal Aktif</label>
                        <input type="date" name="tgl_aktif" class="form-control @error('tgl_aktif') is-invalid @enderror"
                            value="{{ old('tgl_aktif', $teknisi->tgl_aktif) }}" id="tgl_aktif" aria-describedby="tgl_aktif"
                            placeholder="Tanggal Aktif">
                        @error('tgl_aktif')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="alamat_teknisi" class="form-label">Alamat Teknisi</label>
                        <textarea name="alamat_teknisi" class="form-control @error('alamat_teknisi') is-invalid @enderror" id="alamat_teknisi"
                            aria-describedby="alamat_teknisi" placeholder="Alamat Teknisi">{{ old('alamat_teknisi', $teknisi->alamat_teknisi) }}</textarea>
                        @error('alamat_teknisi')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror"
                            value="{{ old('no_telp', $teknisi->no_telp) }}" id="no_telp" aria-describedby="no_telp"
                            placeholder="No. Telepon">
                        @error('no_telp')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="kd_cabang" class="form-label">Cabang</label>
                        <select class="form-select @error('kd_cabang') is-invalid @enderror" name="kd_cabang[]"
                            data-placeholder="Pilih Cabang" id="multiple-select-field" multiple>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->kd_cabang }}"
                                    {{ in_array($cabang->kd_cabang, old('kd_cabang', $selectedCabangs)) ? 'selected' : '' }}>
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
                    <div class="mb-3 col-md-6">
                        <label for="kd_role" class="form-label">Role</label>
                        <select class="form-select @error('kd_role') is-invalid @enderror" name="kd_role" id="kd_role"
                            readonly>
                            <option value="teknisi" selected>teknisi</option>
                        </select>
                        @error('kd_role')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="kd_isp" class="form-label">Pilih ISP</label>
                        <select name="kd_isp" class="form-select @error('kd_isp') is-invalid @enderror">
                            <option value="" disabled>Pilih ISP</option>
                            @foreach ($isps as $isp)
                                <option value="{{ $isp->kd_isp }}"
                                    {{ $teknisi->kd_isp == $isp->kd_isp ? 'selected' : '' }}>
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
                <div class="gap-2 d-flex">
                    <a href="{{ route('teknisi.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
