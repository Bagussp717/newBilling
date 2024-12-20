@foreach ($pakets as $paket)
    <!-- Modal Edit Paket -->
    <div class="modal fade" id="editPaketModal{{ $paket->kd_paket }}" tabindex="-1"
        aria-labelledby="editPaketLabel{{ $paket->kd_paket }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom-border-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaketLabel{{ $paket->kd_paket }}">Edit Data Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('paket.update', $paket->kd_paket) }}" method="POST">
                        @csrf
                        @method('PUT')
                    
                        <!-- Input Nama Paket -->
                        <div class="mb-3">
                            <label for="nm_paket{{ $paket->kd_paket }}" class="form-label">Nama Paket</label>
                            <input type="text" name="nm_paket" class="form-control @error('nm_paket') is-invalid @enderror" readonly
                                value="{{ old('nm_paket', $paket->nm_paket) }}" id="nm_paket{{ $paket->kd_paket }}" placeholder="Nama Paket">
                            @error('nm_paket')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <!-- Input Harga Paket -->
                        <div class="mb-3">
                            <label for="hrg_paket{{ $paket->kd_paket }}" class="form-label">Harga Paket</label>
                            <input type="text" class="form-control @error('hrg_paket') is-invalid @enderror"
                                value="{{ old('hrg_paket', $paket->hrg_paket) }}" id="hrg_paket{{ $paket->kd_paket }}" placeholder="Harga Paket"
                                oninput="formatRupiahedit(this, 'hidden_hrg_paket{{ $paket->kd_paket }}')">
                    
                            <!-- Input hidden dengan ID yang unik -->
                            <input type="hidden" id="hidden_hrg_paket{{ $paket->kd_paket }}" name="hrg_paket" value="{{ old('hrg_paket') }}">
                    
                            @error('hrg_paket')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <!-- Input Kode Cabang -->
                        <div class="mb-3">
                            <label for="kd_cabang{{ $paket->kd_paket }}" class="form-label">Kode Cabang</label>
                            <input type="text" name="nm_cabang" class="form-control @error('kd_cabang') is-invalid @enderror" readonly
                                value="{{ $paket->cabang->nm_cabang }}" id="nm_cabang{{ $paket->kd_paket }}" placeholder="Nama Cabang">
                            <input type="hidden" name="kd_cabang" value="{{ $paket->kd_cabang }}">
                            @error('kd_cabang')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <!-- Input Keterangan -->
                        <div class="mb-3">
                            <label for="keterangan{{ $paket->kd_paket }}" class="form-label">Keterangan</label>
                            <select name="keterangan" class="form-select @error('keterangan') is-invalid @enderror"
                                id="keterangan{{ $paket->kd_paket }}">
                                <option value="default" {{ old('keterangan', $paket->keterangan) == 'default' ? 'selected' : '' }}>Default</option>
                                <option value="uji coba" {{ old('keterangan', $paket->keterangan) == 'uji coba' ? 'selected' : '' }}>Uji Coba</option>
                            </select>
                            @error('keterangan')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <!-- Tombol Simpan dan Kembali -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('paket.index') }}" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
@endforeach
