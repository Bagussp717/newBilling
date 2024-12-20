@extends('layouts.backend')

@section('title', 'Tambah Paket')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tambah Data Paket</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('paket.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nm_paket" class="form-label">Nama Paket</label>
                    <input type="text" name="nm_paket" class="form-control @error('nm_paket') is-invalid @enderror"
                        value="{{ old('nm_paket') }}" id="nm_paket" placeholder="Nama Paket">
                    @error('nm_paket')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="hrg_paket" class="form-label">Harga Paket</label>
                    <input type="text" class="form-control @error('hrg_paket') is-invalid @enderror"
                        value="{{ old('hrg_paket') }}" id="jml_bayar_view" placeholder="Harga Paket"
                        oninput="formatRupiah(this)">
                    {{-- Hidden value input --}}
                    <input type="hidden" id="jml_bayar" name="hrg_paket" value="{{ old('hrg_paket') }}">
                    
                    @error('hrg_paket')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kd_cabang" class="form-label">Kode Cabang</label>
                    <select name="kd_cabang" class="form-select @error('kd_cabang') is-invalid @enderror" id="kd_cabang">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->kd_cabang }}"
                                {{ old('kd_cabang') == $cabang->kd_cabang ? 'selected' : '' }}>
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

                <div class="d-flex gap-2">
                    <a href="{{ route('paket.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
