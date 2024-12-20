<div class="modal fade" id="editCabangModal{{ $cabang->kd_cabang }}" tabindex="-1"
    aria-labelledby="editCabangLabel{{ $cabang->kd_cabang }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-border-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="editCabangLabel{{ $cabang->kd_cabang }}">Edit Data
                    Cabang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cabang.update', $cabang->kd_cabang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Input Nama Cabang -->
                    <div class="mb-3">
                        <label for="nm_cabang{{ $cabang->kd_cabang }}" class="form-label">Nama Cabang</label>
                        <input type="text" name="nm_cabang"
                            class="form-control @error('nm_cabang') is-invalid @enderror"
                            value="{{ old('nm_cabang', $cabang->nm_cabang) }}" id="nm_cabang{{ $cabang->kd_cabang }}"
                            placeholder="Nama Cabang">
                        @error('nm_cabang')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <!-- Input Alamat Cabang -->
                    <div class="mb-3">
                        <label for="alamat_cabang{{ $cabang->kd_cabang }}" class="form-label">Alamat Cabang</label>
                        <input type="text" name="alamat_cabang"
                            class="form-control @error('alamat_cabang') is-invalid @enderror"
                            value="{{ old('alamat_cabang', $cabang->alamat_cabang) }}"
                            id="alamat_cabang{{ $cabang->kd_cabang }}" placeholder="Alamat Cabang">
                        @error('alamat_cabang')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username_mikrotik{{ $cabang->kd_cabang }}" class="form-label">Username
                            Mikrotik</label>
                        <input type="text" name="username_mikrotik"
                            class="form-control @error('username_mikrotik') is-invalid @enderror"
                            value="{{ old('username_mikrotik', $cabang->username_mikrotik) }}"
                            id="username_mikrotik{{ $cabang->kd_cabang }}" placeholder="Username Mikrotik">
                        @error('username_mikrotik')
                            <span class="invalid-feedback" id="username_mikrotik_feedback_1" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Input IP Mikrotik -->
                    <div class="mb-3">
                        <label for="ip_mikrotik{{ $cabang->kd_cabang }}" class="form-label">IP Mikrotik</label>
                        <input type="text" name="ip_mikrotik"
                            class="form-control @error('ip_mikrotik') is-invalid @enderror"
                            value="{{ old('ip_mikrotik', $cabang->ip_mikrotik) }}"
                            id="ip_mikrotik{{ $cabang->kd_cabang }}" placeholder="IP Mikrotik">
                        @error('ip_mikrotik')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <!-- Input Password Mikrotik -->
                    <div class="mb-3">
                        <label for="password_mikrotik{{ $cabang->kd_cabang }}" class="form-label">Password
                            Mikrotik</label>
                        <input type="password" name="password_mikrotik"
                            class="form-control @error('password_mikrotik') is-invalid @enderror"
                            value="{{ old('password_mikrotik', $cabang->password_mikrotik) }}"
                            id="password_mikrotik{{ $cabang->kd_cabang }}" placeholder="Password Mikrotik">
                        @error('password_mikrotik')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <!-- Input Kode ISP -->
                    <div class="mb-3">
                        <label for="kd_isp{{ $cabang->kd_cabang }}" class="form-label">Kode ISP</label>
                        <select name="kd_isp" class="form-control @error('kd_isp') is-invalid @enderror"
                            id="kd_isp{{ $cabang->kd_cabang }}">
                            @foreach ($isps as $isp)
                                <option value="{{ $isp->kd_isp }}"
                                    {{ old('kd_isp', $cabang->kd_isp) == $isp->kd_isp ? 'selected' : '' }}>
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
                    <!-- Tombol Simpan dan Kembali -->
                    <div class="gap-2 d-flex">
                        <a href="{{ route('cabang.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
