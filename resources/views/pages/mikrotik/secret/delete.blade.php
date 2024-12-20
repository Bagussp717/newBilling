{{-- <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Secret</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="flex text-wrap">
                    Apakah Anda yakin ingin menghapus secret pelanggan <strong id="usernamePaket"></strong>?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('GET')
                    <!-- Mengubah method ke DELETE -->
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade" id="deleteModal{{ str_replace(' ', '-', $username_pppoe) }}" tabindex="-1"
    aria-labelledby="deleteModalLabel{{ str_replace(' ', '-', $username_pppoe) }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ str_replace(' ', '-', $username_pppoe) }}">Hapus Data Secreet Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="max-w-full break-words modal-body">
                <p class="text-wrap">Apakah Anda yakin ingin menghapus data secreet pelanggan
                    <strong>{{ str_replace(' ', '-', $username_pppoe) }}</strong>?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('secretMikrotik.destroy', $username_pppoe) }}" method="POST">
                    @csrf
                    @method('GET')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
