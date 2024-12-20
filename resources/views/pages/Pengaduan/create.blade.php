@extends('layouts.backend')

@section('title', 'Tambah Data Pengaduan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Buat Aduan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Pilihan User berdsarkan login -->
                <div class="mb-3">
                    <label for="kd_user" class="form-label">Pengguna</label>
                    <select name="kd_user" id="kd_user" readonly class="form-control @error('kd_user') is-invalid @enderror"> 
                        <option  value="{{ Auth::user()->id }}">{{ Auth::user()->email }}</option>
                    </select>
                    @error('kd_user')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Pilihan Pelanggan berdasakan login -->
                @role('pelanggan')
                    <div class="mb-3">
                        <label for="kd_pelanggan" class="form-label">Pengguna</label>
                        <select name="kd_pelanggan" id="kd_pelanggan" readonly class="form-control @error('kd_pelanggan') is-invalid @enderror"> 
                            @if (Auth::user()->pelanggan && Auth::user()->pelanggan->count() > 0)
                                @foreach (Auth::user()->pelanggan as $pelanggan)
                                    <option value="{{ $pelanggan->kd_pelanggan }}">
                                        {{ $pelanggan->nm_pelanggan }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">Pelanggan tidak ditemukan</option>
                            @endif
                        </select>
                        @error('kd_pelanggan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                @endrole
                
                @role('super-admin|isp|loket')
                <!-- Pilihan Pelanggan  -->
                    <div class="mb-3">
                        <label for="kd_pelanggan" class="form-label">Pelanggan</label>
                        <select name="kd_pelanggan" id="kd_pelanggan" class="form-control @error('kd_pelanggan') is-invalid @enderror">
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->kd_pelanggan }}">{{ $pelanggan->nm_pelanggan }}</option>
                            @endforeach
                        </select>
                        @error('kd_pelanggan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                @endrole


                <div class="mb-3">
                    <label for="deskripsi_tiket" class="form-label">Deskripsi Tiket</label>
                    <textarea name="deskripsi_tiket" id="deskripsi_tiket"
                        class="form-control @error('deskripsi_tiket') is-invalid @enderror" rows="5">{{ old('deskripsi_tiket') }}</textarea>
                    @error('deskripsi_tiket')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Upload Foto (Opsional)</label>
                    <input type="file" name="foto" id="foto"
                        class="form-control @error('foto') is-invalid @enderror">
                    @error('foto')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 d-none">
                    <label for="status_tiket" class="form-label">Status Tiket</label>
                    <select name="status_tiket" id="status_tiket" 
                        class="form-control @error('status_tiket') is-invalid @enderror">
                        <option value="Pending" {{ old('status_tiket') === 'Pengajuan' ? 'selected' : '' }}>Pending</option>
                        <option value="Diterima" {{ old('status_tiket') === 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Diproses" {{ old('status_tiket') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ old('status_tiket') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status_tiket')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>                

                <!-- Tombol Kirim -->
                <div class="gap-2 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">Kirim Pengaduan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
