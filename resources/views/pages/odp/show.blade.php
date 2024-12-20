<!-- Modal Show Pelanggan -->
<div class="modal fade" id="showodpModal{{ $odp->kd_odp }}" tabindex="-1"
    aria-labelledby="showodpModalLabel{{ $odp->kd_odp }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showodpModalLabel{{ $odp->kd_odp }}">Detail ODP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div style="text-wrap: pretty;">
                            <p><strong>Nama ODP:</strong> <br> {{ $odp->nm_odp ?? ' - ' }}</p>
                            <p><strong>Lokasi Maps:</strong> <br><br>
                                <a class="btn btn-sm btn-primary"
                                    href="https://www.google.com/maps?q={{ $odp->lat }},{{ $odp->long }}"
                                    target="_blank">
                                    <i class="ti ti-map-pin" style="font-size: 20px"></i> Lihat Lokasi
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Foto ODP:</strong></p>
                        <img src="{{ asset('storage/foto_odp/' . $odp->foto_odp) }}" alt="Foto odp"
                            class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
