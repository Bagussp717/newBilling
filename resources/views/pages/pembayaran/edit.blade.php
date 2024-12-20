<div class="modal fade" id="editPembayaranModal{{ $pembayaran->kd_pembayaran }}" tabindex="-1"
    aria-labelledby="editPembayaranModal{{ $pembayaran->kd_pembayaran }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-border-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="editPembayaranModal{{ $pembayaran->kd_pembayaran }}">Edit Data
                    Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pembayaran.update', $pembayaran->kd_pembayaran) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Use PUT or PATCH for updates -->

                    <!-- Nama Loket -->
                    <div class="mb-3">
                        <label for="kd_loket" class="form-label">Nama Loket</label>
                        <select name="kd_loket" class="form-select @error('kd_loket') is-invalid @enderror"
                            id="kd_loket">
                            <option value="">Opsional</option>
                            @foreach ($lokets as $loket)
                                <option value="{{ $loket->kd_loket }}"
                                    {{ old('kd_loket', $pembayaran->kd_loket) == $loket->kd_loket ? 'selected' : '' }}>
                                    {{ $loket->nm_loket }}
                                </option>
                            @endforeach
                        </select>
                        @error('kd_loket')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Invoice -->
                    <div class="mb-3">
                        <label for="kd_invoice" class="form-label">Invoice</label>
                        <select name="kd_invoice" class="form-select @error('kd_invoice') is-invalid @enderror" readonly
                            id="kd_invoice" onchange="updateInvoiceDetails()">
                            <option value="">Pilih Invoice</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->kd_invoice }}"
                                    {{ old('kd_invoice', $pembayaran->kd_invoice) == $invoice->kd_invoice ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('kd_invoice')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Tanggal Bayar -->
                    <div class="mb-3">
                        <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                        <input type="date" id="tgl_bayar"
                            class="form-control @error('tgl_bayar') is-invalid @enderror" name="tgl_bayar"
                            value="{{ old('tgl_bayar', $pembayaran->tgl_bayar) }}" placeholder="Tanggal Bayar">
                        @error('tgl_bayar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Jumlah Bayar -->
                    <div class="mb-3">
                        <label for="jml_bayar" class="form-label">Jumlah Bayar</label>
                        <!-- Input untuk tampilan -->
                        <input type="text" id="jml_bayar_edit"
                            class="form-control @error('jml_bayar') is-invalid @enderror" placeholder="Jumlah Bayar"
                            oninput="formatRupiahedit(this, 'jml_bayar_hidden')"
                            value="{{ old('jml_bayar', number_format($pembayaran->jml_bayar, 0, ',', '.')) }}">

                        <!-- Input hidden untuk nilai asli -->
                        <input type="hidden" id="jml_bayar_hidden" name="jml_bayar"
                            value="{{ old('jml_bayar', $pembayaran->jml_bayar) }}">

                        @error('jml_bayar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class="gap-2 d-flex">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
