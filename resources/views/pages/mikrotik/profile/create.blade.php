<div class="modal fade" id="addProfileModal" tabindex="-1" aria-labelledby="addProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProfileModalLabel">Tambah Profile Mikrotik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profilemikrotik.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Input Nama Paket -->
                        <div class="mb-3 col-md-6">
                            <label for="nm_paket" class="form-label">Nama Paket</label>
                            <input type="text" name="nm_paket"
                                class="form-control @error('nm_paket') is-invalid @enderror"
                                value="{{ old('nm_paket') }}" id="nm_paket" placeholder="Nama Paket">
                            @error('nm_paket')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Input Harga Paket -->
                        <div class="mb-3 col-md-6">
                            <label for="hrg_paket" class="form-label">Harga Paket</label>
                            <input type="number" name="hrg_paket"
                                class="form-control @error('hrg_paket') is-invalid @enderror"
                                value="{{ old('hrg_paket') }}" id="hrg_paket" placeholder="Harga Paket">
                            @error('hrg_paket')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Input Local Address -->
                        <div class="mb-3 col-md-6">
                            <label for="local_address" class="form-label">Local Address</label>
                            <input type="text" name="local_address"
                                class="form-control @error('local_address') is-invalid @enderror"
                                value="{{ old('local_address') }}" id="local_address" placeholder="Local Address">
                            @error('local_address')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Input Remote Address -->
                        <div class="mb-3 col-md-6">
                            <label for="remote_address" class="form-label">Remote Address</label>
                            <select name="remote_address"
                                class="form-select @error('remote_address') is-invalid @enderror" id="remote_address">
                                <option disabled selected value="">Pilih Profile</option>
                                @foreach ($ippool as $data)
                                    <option>{{ $data['name'] }}</option>
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
                        <!-- Input Rate Limit -->
                        <div class="mb-3 col-md-6">
                            <label for="rate_limit" class="form-label">Rate Limit</label>
                            <input type="text" name="rate_limit"
                                class="form-control @error('rate_limit') is-invalid @enderror"
                                value="{{ old('rate_limit') }}" id="rate_limit" placeholder="Rate Limit">
                            @error('rate_limit')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Input Keterangan -->
                        <div class="mb-3 col-md-6">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <select name="keterangan" class="form-select @error('keterangan') is-invalid @enderror"
                                id="keterangan">
                                <option disabled selected value="">Pilih Keterangan</option>
                                <option value="default" {{ old('keterangan') == 'default' ? 'selected' : '' }}>Default
                                </option>
                                <option value="uji coba" {{ old('keterangan') == 'uji coba' ? 'selected' : '' }}>Uji
                                    Coba</option>
                            </select>
                            @error('keterangan')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Input Kode Cabang -->
                        <div class="mb-3 col-md-6 d-none">
                            <label for="kd_cabang" class="form-label">Kode Cabang</label>
                            <select name="kd_cabang" class="form-select @error('kd_cabang') is-invalid @enderror"
                                id="kd_cabang">
                                <option value="" disabled
                                    {{ old('kd_cabang', session()->get('kd_cabang')) == '' ? 'selected' : '' }}>Pilih
                                    Cabang</option>
                                @foreach ($cabangs as $cabang)
                                    <option value="{{ $cabang->kd_cabang }}"
                                        {{ old('kd_cabang', session()->get('kd_cabang')) == $cabang->kd_cabang ? 'selected' : '' }}>
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
                        <!-- Input Kode ISP (Hidden atau Readonly) -->
                        <div class="mb-3 col-md-6">

                            <!-- Check if the user has the role 'isp' -->
                            @if (Auth::user()->hasRole('isp'))
                                <!-- Jika role user adalah ISP, disable select dan isi value dengan kd_isp user -->
                                <input type="text" name="kd_isp" class="form-control d-none"
                                    value="{{ Auth::user()->isp->first()->kd_isp }}" readonly>
                            @else
                                <label for="kd_isp" class="form-label">Kode ISP</label>
                                <!-- Jika user bukan ISP (misalnya super-admin), tampilkan dropdown dengan semua ISP -->
                                <select name="kd_isp" class="form-select @error('kd_isp') is-invalid @enderror"
                                    id="kd_isp">
                                    <option value="" disabled selected>Pilih ISP</option>
                                    @foreach ($isps as $isp)
                                        <option value="{{ $isp->kd_isp }}"
                                            {{ old('kd_isp') == $isp->kd_isp ? 'selected' : '' }}>
                                            {{ $isp->nm_isp }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            @error('kd_isp')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                    </div>

                    <div class="gap-2 d-flex">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
