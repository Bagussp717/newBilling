<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\IspController;
use App\Http\Controllers\LoketController;
use App\Http\Controllers\LoketPembayaranController;
use App\Http\Controllers\Mikrotik\DashboardMikrotikController;
use App\Http\Controllers\Mikrotik\LoginController;
use App\Http\Controllers\Mikrotik\ProfileMikrotikController;
use App\Http\Controllers\Mikrotik\SecretController;
use App\Http\Controllers\Mikrotik\StatusPelangganController;
use App\Http\Controllers\ODPController;
use App\Http\Controllers\PaketController;

use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\EncryptionController;

use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganTidakAktifController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\TiketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CryptController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// dashboard utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::post('/login/cabang', [DashboardController::class, 'loginCabang'])->name('dashboard.login')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/semua-pelanggan', [DashboardController::class, 'secretDashboard'])->name('secretDashboard')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/pelanggan-aktif', [DashboardController::class, 'activeDashboard'])->name('activeDashboard')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/pelanggan-tidak-aktif', [DashboardController::class, 'nonActiveDashboard'])->name('nonActiveDashboard')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

Route::get('/dashboard/cabang/index', [CabangController::class, 'index'])->name('cabang.index')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/cabang/create', [CabangController::class, 'create'])->name('cabang.create')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::post('/dashboard/cabang/store', [CabangController::class, 'store'])->name('cabang.store')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::put('/dashboard/cabang/update/{kd_cabang}', [CabangController::class, 'update'])->name('cabang.update')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::delete('/dashboard/cabang/destroy/{kd_cabang}', [CabangController::class, 'destroy'])->name('cabang.destroy')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

// Route untuk Paket
Route::get('/paket', [PaketController::class, 'index'])->name('paket.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/paket/create', [PaketController::class, 'create'])->name('paket.create')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/paket', [PaketController::class, 'store'])->name('paket.store')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/paket/edit/{kd_paket}', [PaketController::class, 'edit'])->name('paket.edit')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::put('/paket/update/{kd_paket}', [PaketController::class, 'update'])->name('paket.update')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::delete('/paket/destroy/{kd_paket}', [PaketController::class, 'destroy'])->name('paket.destroy')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

// pelanggan
Route::get('/dashboard/pelanggan-aktif', [PelangganController::class, 'index'])->name('pelanggan.index')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/pelanggan/show/{kd_pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/pelanggan/edit/{kd_pelanggan}', [PelangganController::class, 'edit'])->name('pelanggan.edit')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::put('/dashboard/pelanggan/update/{kd_pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

// status pelanggan tidak aktif
Route::get('/dashboard/pelanggan-tidak-aktif', [PelangganTidakAktifController::class, 'index'])->name('pelangganTidakAktif.index')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/pelanggan-tidak-aktif/show/{kd_pelanggan}', [PelangganTidakAktifController::class, 'show'])->name('pelangganTidakAktif.show')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/pelanggan-tidak-aktif/edit/{kd_pelanggan}', [PelangganTidakAktifController::class, 'edit'])->name('pelangganTidakAktif.edit')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::put('/dashboard/pelanggan-tidak-aktif/update/{kd_pelanggan}', [PelangganTidakAktifController::class, 'update'])->name('pelangganTidakAktif.update')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::delete('/dashboard/pelanggan-tidak-aktif/destroy/{kd_pelanggan}', [PelangganTidakAktifController::class, 'destroy'])->name('pelangganTidakAktif.destroy')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/pelanggan-tidak-aktif/edit-secreet/{kd_pelanggan}', [PelangganTidakAktifController::class, 'editScreet'])->name('pelangganTidakAktif.editScreet')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::put('/dashboard/pelanggan-tidak-aktif/update-secreet/{kd_pelanggan}', [PelangganTidakAktifController::class, 'updateScreet'])->name('pelangganTidakAktif.updateScreet')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

Route::post('/mikrotik/login', [LoginController::class, 'login'])->name('mikrotik.login')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/mikrotik/dashboard', [DashboardMikrotikController::class, 'index'])->name('mikrotik.dashboard')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

Route::get('/dashboard/profile', [ProfileMikrotikController::class, 'index'])->name('profilemikrotik.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/realtime/profile', [ProfileMikrotikController::class, 'realtime'])->name('profilemikrotik.realtime')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/profile/create', [ProfileMikrotikController::class, 'create'])->name('profilemikrotik.create')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/dashboard/profile/store', [ProfileMikrotikController::class, 'store'])->name('profilemikrotik.store')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/profile/edit/{nm_paket}', [ProfileMikrotikController::class, 'edit'])->name('profilemikrotik.edit')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/dashboard/profile/update/{nm_paket}', [ProfileMikrotikController::class, 'update'])->name('profilemikrotik.update')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/profile/destroy/{nm_paket}', [ProfileMikrotikController::class, 'destroy'])->name('profilemikrotik.destroy')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/dashboard/profile/sync', [ProfileMikrotikController::class, 'sync'])->name('profilemikrotik.sync')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

// secret
Route::get('/dashboard/secret', [SecretController::class, 'index'])->name('secretMicrotik.index')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/secret/create', [SecretController::class, 'create'])->name('secretMicrotik.create')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::post('/dashboard/secret/store', [SecretController::class, 'store'])->name('secretMikrotik.store')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/secret/edit/{username_pppoe}', [SecretController::class, 'edit'])->name('secretMikrotik.edit')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::post('/dashboard/secret/update/{username_pppoe}', [SecretController::class, 'update'])->name('secretMikrotik.update')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/secret/destroy1/{username_pppoe}', [SecretController::class, 'destroy1'])->name('secretMikrotik.destroy1')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/secret/destroy/{username_pppoe}', [SecretController::class, 'destroy'])->name('secretMikrotik.destroy')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::post('/dashboard/secret/sync-mikrotik', [SecretController::class, 'sync'])->name('secretMikrotik.sync');

// teknisi
Route::get('/dashboard/teknisi/index', [TeknisiController::class, 'index'])->name('teknisi.index')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/teknisi/create', [TeknisiController::class, 'create'])->name('teknisi.create')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::post('/dashboard/teknisi/store', [TeknisiController::class, 'store'])->name('teknisi.store')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/dashboard/teknisi/edit/{kd_teknisi}', [TeknisiController::class, 'edit'])->name('teknisi.edit')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::put('/dashboard/teknisi/update/{kd_teknisi}', [TeknisiController::class, 'update'])->name('teknisi.update')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::delete('/dashboard/teknisi/destroy/{kd_teknisi}', [TeknisiController::class, 'destroy'])->name('teknisi.destroy')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

// isp
Route::get('/dashboard/isp', [IspController::class, 'index'])->name('isp.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/tambah-isp', [IspController::class, 'create'])->name('isp.create')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/dashboard/tambah-isp', [IspController::class, 'store'])->name('isp.store')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/edit-isp/{kd_isp}', [IspController::class, 'edit'])->name('isp.edit')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::put('/dashboard/update-isp/{kd_isp}', [IspController::class, 'update'])->name('isp.update')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::delete('/dashboard/delete-isp/{kd_isp}', [IspController::class, 'destroy'])->name('isp.destroy')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

// Loket Pembayaran
Route::get('/dashboard/loket-pembayaran', [LoketPembayaranController::class, 'index'])->name('loketPembayaran.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/Perloket-pembayaran', [LoketPembayaranController::class, 'indexloket'])->name('loketPembayaran.dashboard.indexloket')->middleware(['auth', 'verified', 'role:loket']);
Route::get('/loket-pembayaran', [LoketPembayaranController::class, 'indexloket'])->name('loketPembayaran.indexloket')->middleware(['auth', 'verified', 'role:loket']);
Route::get('/search-invoice', [LoketPembayaranController::class, 'search'])->name('search.invoice')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/loket-pembayaran/search', [LoketPembayaranController::class, 'search'])->name('loketPembayaran.search');

// cetak daftar tagihan dan all invoice
Route::get('/cetak-daftar-tagihan/{kd_loket}/{tgl_invoice}', [LoketPembayaranController::class, 'cetakDaftarTagihan'])->name('cetakDaftarTagihan.invoice')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/cetak-all-invoice/{kd_loket}/{tgl_invoice}', [LoketPembayaranController::class, 'cetakAllInvoice'])->name('cetakAllInvoice.invoice')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

// pembayaran
Route::get('/dashboard/pembayaran/index', [PembayaranController::class, 'index'])->name('pembayaran.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/pembayaran/{kd_invoice}/create', [PembayaranController::class, 'create'])->name('pembayaran.create')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/dashboard/pembayaran/store', [PembayaranController::class, 'store'])->name('pembayaran.store')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::put('/dashboard/pembayaran/update/{kd_pembayaran}', [PembayaranController::class, 'update'])->name('pembayaran.update')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::delete('/dashboard/pembayaran/destroy/{kd_pembayaran}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

// Loket
Route::get('/dashboard/loket/index', [LoketController::class, 'index'])->name('loket.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/loket/create', [LoketController::class, 'create'])->name('loket.create')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/dashboard/loket/store', [LoketController::class, 'store'])->name('loket.store')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/dashboard/loket/{id}/edit', [LoketController::class, 'edit'])->name('loket.edit')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::put('/dashboard/loket/{id}', [LoketController::class, 'update'])->name('loket.update')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::delete('/dashboard/loket/{id}', [LoketController::class, 'destroy'])->name('loket.destroy')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

// admin
Route::get('/dashboard/admin/edit/{id}', [AdminController::class, 'index'])->name('admin.edit')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::put('/dashboard/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

//ODP
Route::get('/odp', [ODPController::class, 'index'])->name('odp.index')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/odp/create', [ODPController::class, 'create'])->name('odp.create')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::post('/odp/store', [ODPController::class, 'store'])->name('odp.store')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/odp/edit/{kd_odp}', [ODPController::class, 'edit'])->name('odp.edit')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::put('/odp/update/{kd_odp}', [ODPController::class, 'update'])->name('odp.update')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::delete('/odp/delete/{kd_odp}', [ODPController::class, 'destroy'])->name('odp.destroy')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

// mikrotik
Route::get('/mikrotik/dashboard', [DashboardMikrotikController::class, 'index'])->name('mikrotik.dashboard')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/cpu/realtime', [DashboardMikrotikController::class, 'cpu'])->name('cpu.realtime')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/uptime/realtime', [DashboardMikrotikController::class, 'uptime'])->name('uptime.realtime')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/free_memory/realtime', [DashboardMikrotikController::class, 'freememory'])->name('freememory.realtime')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/free_hdd_space/realtime', [DashboardMikrotikController::class, 'freehddspace'])->name('freehddspace.realtime')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/traffic/realtime/{traffic}', [DashboardMikrotikController::class, 'traffic'])->name('traffic.realtime')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

// status pelanggan
Route::get('/secret', [StatusPelangganController::class, 'secret'])->name('secret')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/active', [StatusPelangganController::class, 'active'])->name('active')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::get('/nonactive', [StatusPelangganController::class, 'nonActive'])->name('nonactive')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('invoice/pelanggan', [InvoiceController::class, 'pelanggan'])->name('invoice.pelanggan') ->middleware(['auth', 'verified', 'role:super-admin|pelanggan']);
Route::get('invoice/all_invoice', [InvoiceController::class, 'allindex'])->name('invoice.all_invoice')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/invoice/generate-invoices', [InvoiceController::class, 'generateInvoices'])->name('invoice.generateInvoices')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/invoice-small/{kd_invoice}', [InvoiceController::class, 'small'])->name('invoice.small')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/invoice-full/{kd_invoice}', [InvoiceController::class, 'fullpage'])->name('invoice.full')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/invoice-viewinvoice', [InvoiceController::class, 'viewinvoice'])->name('invoice.viewinvoice')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::delete('/invoice/delete-by-date', [InvoiceController::class, 'destroyByInvoiceDate'])->name('invoice.destroyByDate')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/invoice/isolir/{kd_pelanggan}', [InvoiceController::class, 'isolirByPelanggan'])->name('invoice.isolir')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/invoice/pulihkan/{kd_pelanggan}', [InvoiceController::class, 'pulihkanByPelanggan'])->name('invoice.pulihkan')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/invoice/period', [InvoiceController::class, 'showByPeriod'])->name('invoice.showByPeriod')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);


Route::post('/isolir', [SecretController::class, 'isolir'])->name('isolir')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/recover', [SecretController::class, 'recover'])->name('recover')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);


// Pengaduan
Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);
Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);


// Pengaduan
Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|teknisi|loket']);
Route::get('/tiket_selesai', [TiketController::class, 'doneindex'])->name('tiket.doneindex')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket|teknisi']);
Route::get('/tiket/create', [TiketController::class, 'create'])->name('tiket.create')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket|teknisi']);
Route::post('/tiket', [TiketController::class, 'store'])->name('tiket.store')->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket|teknisi']);
Route::get('/tiket/edit/{kd_tiket}', [TiketController::class, 'edit'])->name('tiket.edit')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::put('/tiket/update/{kd_tiket}', [TiketController::class, 'update'])->name('tiket.update')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);
Route::delete('/tiket/delete/{kd_tiket}', [TiketController::class, 'destroy'])->name('tiket.destroy')->middleware(['auth', 'verified', 'role:super-admin|teknisi|isp|pelanggan|loket']);

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});
Route::middleware(['auth', 'role:super-admin|teknisi|isp|pelanggan|loket'])->get('/session-data', [SessionController::class, 'getSessionData']);
Route::get('/encrypt-data/{data}', [EncryptionController::class, 'encryptData'])
    ->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

Route::get('/encrypt-array/{data}', [EncryptionController::class, 'encryptArray'])
    ->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

Route::post('/decrypt', [CryptController::class, 'decrypt'])
    ->middleware(['auth', 'verified', 'role:super-admin|isp|pelanggan|loket']);

require __DIR__ . '/auth.php';
