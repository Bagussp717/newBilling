@extends('layouts.backend')

@section('title', 'Edit Pengaduan')

@section('content')
    <div class="row">
            <div class="mb-5 card">
                <div class="card-header">
                    <h5 class="card-title">Detail Data Pelanggan</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="py-2" style="width: 20%">Nama Pelanggan</th>
                                <td class="py-2" style="width: 1%">:</td>
                                <td class="py-2" style="width: 79%">{{ $tiket->pelanggan->nm_pelanggan }}</td>
                            </tr>
                            <tr>
                                <th class="py-2" style="width: 20%">Alamat</th>
                                <td class="py-2" style="width: 1%">:</td>
                                <td class="py-2" style="width: 79%"> {{ $tiket->pelanggan->t_lahir ?? ' - ' }}</td>
                            </tr>
                            <tr>
                                <th class="py-2" style="width: 20%">Lokasi</th>
                                <td class="py-2" style="width: 1%">:</td>
                                <td class="py-2" style="width: 79%">
                                    @if($tiket->pelanggan->lat && $tiket->pelanggan->long)
                                        <a class="btn btn-sm btn-primary"
                                           href="https://www.google.com/maps?q={{ $tiket->pelanggan->lat }},{{ $tiket->pelanggan->long }}"
                                           target="_blank">
                                            <i class="ti ti-map-pin" style="font-size: 15px"></i> Lihat Lokasi
                                        </a>
                                    @else
                                        <span>Tidak ada lokasi tersedia</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="py-2" style="width: 20%">Whatsapp</th>
                                <td class="py-2" style="width: 1%">:</td>
                                <td class="py-2" style="width: 79%">
                                    <p>
                                        @if($pelanggan && $pelanggan->no_telp)
                                            <a href="https://wa.me/{{ $pelanggan->no_telp }}" class="btn btn-success btn-sm">
                                                <i class="ti ti-brand-whatsapp" style="font-size: 15px"></i>
                                                {{ $pelanggan->no_telp }}
                                            </a>
                                        @else
                                            <span>Tidak ada nomor telepon tersedia</span>
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            </p>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mb-5 card">
                <div class="card-header">
                    <h5 class="card-title">Edit Pengaduan</h5>
                </div>
                <div class="p-4 card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tiket.update', Crypt::encryptString($tiket->kd_tiket)) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 d-none">
                            <label for="kd_user" class="form-label">Kode User</label>
                            <input type="text" class="form-control" id="kd_user" name="kd_user"
                                value="{{ old('kd_user', $tiket->kd_user) }}" required>
                        </div>

                        <div class="mb-3 d-none">
                            <label for="kd_pelanggan" class="form-label">Kode Pelanggan</label>
                            <input type="text" class="form-control" id="kd_pelanggan" name="kd_pelanggan"
                                value="{{ old('kd_pelanggan', $tiket->kd_pelanggan) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_tiket" class="form-label" readonly>Deskripsi</label>
                            <textarea class="form-control" id="deskripsi_tiket" readonly name="deskripsi_tiket" rows="4" required>{{ old('deskripsi_tiket', $tiket->deskripsi_tiket) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <br>
                            {{-- <input type="file" class="form-control" id="foto" name="foto" accept="image/*"> --}}
                            @if ($tiket->foto)
                                <img src="{{ asset("storage/".$tiket->foto) }}" alt="Foto Pengaduan" class="mt-2"
                                    style="width: 150px; height: auto;">
                            @else
                                <p>Tidak ada foto saat ini.</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label" >Keterangan</label>
                            <textarea class="form-control" id="keterangan"  name="keterangan" rows="4" required>{{ old('deskripsi_tiket', $tiket->keterangan) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status_tiket" class="form-label">Status</label>
                            <select class="form-control" id="status_tiket" name="status_tiket" required>
                                <option value="Pending" {{ old('status_tiket') === 'Pengajuan' ? 'selected' : '' }}>
                                    Pengajuan</option>
                                <option value="Diterima" {{ old('status_tiket') === 'Diterima' ? 'selected' : '' }}>
                                    Diterima
                                </option>
                                <option value="Diproses" {{ old('status_tiket') === 'Diproses' ? 'selected' : '' }}>
                                    Diproses
                                </option>
                                <option value="Selesai" {{ old('status_tiket') === 'Selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </select>
                        </div>

                        <!-- Tombol Kirim -->
                        <div class="gap-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Pengaduan</button>
                        <a href="{{ route('tiket.index') }}" class="btn btn-secondary">Batal</a>
                        </div>


                    </form>
                </div>
            </div>
    </div>
@endsection
