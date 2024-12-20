<!-- Modal Show Pelanggan -->
<div class="modal fade" id="showPelangganModal{{ $pelanggan->kd_pelanggan }}" tabindex="-1"
    aria-labelledby="showPelangganModalLabel{{ $pelanggan->kd_pelanggan }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showPelangganModalLabel{{ $pelanggan->kd_pelanggan }}">Detail Pelanggan:
                    {{ $pelanggan->nm_pelanggan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div style="text-wrap: pretty;">
                            <p><strong>Nama Pelanggan:</strong> <br> {{ $pelanggan->nm_pelanggan ?? ' - ' }}</p>
                            <p><strong>Tanggal Pemasangan:</strong><br> {{ $pelanggan->tgl_pemasangan ?? ' - ' }}</p>
                            <p><strong>Alamat:</strong> <br> {{ $pelanggan->alamat ?? ' - ' }}</p>
                            <p><strong>TTL:</strong> <br> {{ $pelanggan->t_lahir }},
                                {{ $pelanggan->tgl_lahir ?? ' - ' }}</p>
                            <p><strong>Pekerjaan:</strong> <br> {{ $pelanggan->pekerjaan ?? ' - ' }}</p>
                            <p><strong>No. Telepon:</strong> <br> <a
                                    href="https://wa.me/{{ $pelanggan->no_telp ?? ' - ' }}"
                                    class="btn btn-success btn-sm"><i class="ti ti-brand-whatsapp"
                                        style="font-size: 20px"></i>
                                    {{ $pelanggan->no_telp }}</a></p>
                            <p><strong>Cabang:</strong> <br> {{ $pelanggan->cabang->nm_cabang ?? ' - ' }}</p>
                            <p><strong>Loket:</strong> <br> {{ $pelanggan->loket->nm_loket ?? ' - ' }} </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="text-wrap: pretty;">
                            {{-- <p><strong>ID Pelanggan:</strong> <br> {{ $pelanggan->kd_pelanggan ?? ' - ' }}</p> --}}
                            <p><strong>Username:</strong> <br> {{ $pelanggan->username_pppoe ?? ' - ' }}</p>
                            <p><strong>password:</strong> <br> {{ $pelanggan->password_pppoe ?? ' - ' }}</p>
                            <p><strong>profile:</strong> <br> {{ $pelanggan->profile_pppoe ?? ' - ' }}</p>
                            <p><strong>Lokasi Maps:</strong> <br>
                                <a class="btn btn-sm btn-primary"
                                    href="https://www.google.com/maps?q={{ $pelanggan->lat }},{{ $pelanggan->long }}"
                                    target="_blank">
                                    <i class="ti ti-map-pin" style="font-size: 20px"></i> Lihat Lokasi
                                </a>
                            </p>
                            <p><strong>Foto Rumah:</strong></p>
                            <img src="{{ asset('storage/' . $pelanggan->foto_rumah) }}" alt="Foto Rumah"
                                class="img-thumbnail" style="max-height: 200px;">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
