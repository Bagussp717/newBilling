@extends('layouts.backend')

@section('title', 'Edit Data Admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Edit Data Admin</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.update', Crypt::encryptString($admin->id)) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $admin->name) }}" id="name" aria-describedby="name"
                        placeholder="Nama Teknisi">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $admin->email) }}" id="email" aria-describedby="email"
                        placeholder="Email Teknisi">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        value="{{ old('password') }}" id="password" aria-describedby="password"
                        placeholder="Password Super Admin (Kosongkan jika tidak diubah)">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kd_role" class="form-label">Role</label>
                    <select class="form-select @error('kd_role') is-invalid @enderror" name="kd_role" id="kd_role"
                        readonly>
                        <option value="super-admin" selected>super-admin</option>
                    </select>
                    @error('kd_role')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>
            <div class="gap-2 d-flex">
                <a href="{{ route('dashboard.index') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
