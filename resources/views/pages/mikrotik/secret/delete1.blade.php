{{-- <!-- Modal Delete1 -->
<div class="modal fade" id="deleteModal1" tabindex="-1" aria-labelledby="deleteModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel1">Hapus Semua Data Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus semua data <span id="usernamePaket1"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm1" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
<!-- Modal Delete -->
<div class="modal fade" id="deleteModal1{{ str_replace(' ', '-', $username_pppoe) }}" tabindex="-1"
    aria-labelledby="deleteModalLabel1{{ str_replace(' ', '-', $username_pppoe) }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel1{{ str_replace(' ', '-', $username_pppoe) }}">Hapus Semua Data Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="max-w-full break-words modal-body">
                <p class="text-wrap">Apakah Anda yakin ingin menghapus semua data pelanggan
                    <strong>{{ str_replace(' ', '-', $username_pppoe) }}</strong>?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('secretMikrotik.destroy1', $username_pppoe) }}" method="POST">
                    @csrf
                    @method('GET')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
