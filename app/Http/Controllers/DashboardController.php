<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tiket;
use App\Models\Cabang;
use App\Models\Pelanggan;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];
        $cabangs = collect();
        $kd_isps = $user->isp->pluck('kd_isp');

        $secretnonactiveCount = 0;
        $activeCount = 0;
        $secretCount = 0;

        if ($user->hasRole('loket')) {
            $lokets = $user->loket; // Ambil semua loket dari user

            // Inisialisasi variabel untuk menyimpan loket dan tanggal invoice terbaru
            $latestInvoiceDate = null;
            $kd_loket = null;

            foreach ($lokets as $loket) {
                // Ambil tanggal invoice terbaru dari loket
                $invoices = $loket->invoice()->orderBy('tgl_invoice', 'desc')->get(); // Ambil semua invoice, urutkan berdasarkan tgl_invoice

                if ($invoices->isNotEmpty()) {
                    // Dapatkan tanggal invoice terbaru
                    $currentLatestInvoiceDate = $invoices->first()->tgl_invoice;

                    // Jika tanggal invoice terbaru lebih baru dari yang sebelumnya, simpan nilainya
                    if (is_null($latestInvoiceDate) || $currentLatestInvoiceDate > $latestInvoiceDate) {
                        $latestInvoiceDate = $currentLatestInvoiceDate;
                        $kd_loket = $loket->kd_loket; // Simpan kd_loket dari loket yang memiliki tanggal terbaru
                    }
                }
            }

        // Jika ditemukan kd_loket dan tanggal invoice terbaru
        if ($kd_loket && $latestInvoiceDate) {
            $encryptedKdLoket = Crypt::encryptString($kd_loket);

            return redirect()->route('loketPembayaran.search', [
                'kd_loket' => $encryptedKdLoket,
                'tgl_invoice' => $latestInvoiceDate // Kirim tanggal invoice terbaru
            ]);
        }

        } elseif ($user->hasRole('super-admin')) {
            $cabangs = Cabang::all();
        } elseif($user->hasRole('isp')){
            $cabangs = Cabang::where('kd_isp', $kd_isps)->get();
        } elseif ($user->hasRole('teknisi')) {
            // Ambil data teknisi dari user
            $teknisi = $user->teknisi()->first();
            if ($teknisi) {
                // Ambil semua kd_cabang yang terkait dengan teknisi (menggunakan pivot table cabang_teknisis)
                $kd_cabangs = $teknisi->cabangs()->pluck('cabang_teknisis.kd_cabang')->toArray();

                // Ambil cabang berdasarkan kd_cabang yang didapat dari teknisi
                $cabangs = Cabang::whereIn('kd_cabang', $kd_cabangs)->get();
            } else {
                $cabangs = collect(); // Kosongkan koleksi jika tidak ada teknisi
            }
        }

        $inProgressTickets = Tiket::where('status_tiket', 'Diproses')->count();
        $pengajuanTickets = Tiket::where('status_tiket', 'Pengajuan')->count();
        $completedTicketsCount = Tiket::where('status_tiket', 'Selesai')->count();

        // Ambil jumlah semua pelanggan berdasarkan kd_isp
        $jumlahSemuaPelanggan = Pelanggan::whereIn('kd_isp', $kd_isps)->count();


        // Mendapatkan bulan saat ini dalam format numerik (contoh: 10 untuk Oktober)
        $bulanSekarang = Carbon::now()->month;

        // Menghitung jumlah pelanggan baru untuk bulan sekarang berdasarkan kd_isp dan tgl_pemasangan
        $jumlahPelangganBaruBulanSekarang = Pelanggan::whereIn('kd_isp', $kd_isps) // Filter berdasarkan kd_isp
            ->whereMonth('tgl_pemasangan', $bulanSekarang) // Filter berdasarkan bulan sekarang
            ->count();

        // Ambil jumlah pelanggan yang berhenti berlangganan berdasarkan kd_isp
        $jumlahPelangganBerhenti = Pelanggan::whereIn('kd_isp', $kd_isps)
        ->where(function($query) {
            $query->whereNull('username_pppoe') ->orWhere('username_pppoe', ''); // Memeriksa apakah username_pppoe kosong
        })->count();

        // Check if a cabang is selected from the session
        $selectedCabang = session()->get('kd_cabang');
        if ($selectedCabang) {
            $ip = session()->get('ip');
            $apiUser = session()->get('user');
            $pass = session()->get('pass');

            $API = new RouterosAPI();
            $API->debug(false);

            // Connect to MikroTik and fetch data
            if ($API->connect($ip, $apiUser, $pass)) {
                $data = $API->comm('/ppp/secret/print');
                $secretactive = $API->comm('/ppp/active/print');
                $secretnonactive = [];
                foreach ($data as $semua) {
                    $namaSecret = $semua['name'];
                    $isNonActive = true;

                    foreach ($secretactive as $active) {
                        if ($active['name'] === $namaSecret) {
                            $isNonActive = false;
                            break;
                        }
                    }
                    if ($isNonActive) {
                        $secretnonactive[] = $semua;
                    }
                }

                $secretnonactiveCount = count($secretnonactive);
                $activeCount = count($secretactive);
                $secretCount = count($data);
            } else {
                return view('pages.dashboard.index')->with([
                    'activeTicketsCount' => $pengajuanTickets,
                    'inProgressTicketsCount' => $inProgressTickets,
                    'completedTicketsCount' => $completedTicketsCount,
                    'data' => $data,
                    'cabangs' => $cabangs,
                    'user' => $user,
                    'secretnonactiveCount' => $secretnonactiveCount,
                    'activeCount' => $activeCount,
                    'secretCount' => $secretCount,
                    'error' => 'Gagal menghubungkan ke mikrotik.',
                    'jumlahSemuaPelanggan' => $jumlahSemuaPelanggan,
                    'jumlahPelangganBaruBulanSekarang' => $jumlahPelangganBaruBulanSekarang,
                    'jumlahPelangganBerhenti' => $jumlahPelangganBerhenti,
                ]);
            }
        }

        if (request()->wantsJson()) {
            return response()->json(
                [
                    'activeTicketsCount' => $pengajuanTickets,
                    'inProgressTicketsCount' => $inProgressTickets,
                    'completedTicketsCount' => $completedTicketsCount,
                    'data' => $data,
                    'cabangs' => $cabangs,
                    'user' => $user,
                    'secretnonactiveCount' => $secretnonactiveCount,
                    'activeCount' => $activeCount,
                    'secretCount' => $secretCount,
                    'jumlahSemuaPelanggan' => $jumlahSemuaPelanggan,
                    'jumlahPelangganBaruBulanSekarang' => $jumlahPelangganBaruBulanSekarang,
                    'jumlahPelangganBerhenti' => $jumlahPelangganBerhenti,
                ]
            );
        }

        // Pass data to the view
        return view('pages.dashboard.index', [
            'activeTicketsCount' => $pengajuanTickets,
            'inProgressTicketsCount' => $inProgressTickets,
            'completedTicketsCount' => $completedTicketsCount,
            'data' => $data,
            'cabangs' => $cabangs,
            'user' => $user,
            'secretnonactiveCount' => $secretnonactiveCount,
            'activeCount' => $activeCount,
            'secretCount' => $secretCount,
            'jumlahSemuaPelanggan' => $jumlahSemuaPelanggan,
            'jumlahPelangganBaruBulanSekarang' => $jumlahPelangganBaruBulanSekarang,
            'jumlahPelangganBerhenti' => $jumlahPelangganBerhenti,
        ]);
    }

    // public function index()
    // {
    //     $user = Auth::user();

    //     if ($user->hasRole('loket')) {
    //         $lokets = $user->loket; // Ambil semua loket dari user

    //         // Inisialisasi variabel untuk menyimpan loket dan tanggal invoice terbaru
    //         $latestInvoiceDate = null;
    //         $kd_loket = null;

    //         foreach ($lokets as $loket) {
    //             // Ambil tanggal invoice terbaru dari loket
    //             $invoices = $loket->invoice()->orderBy('tgl_invoice', 'desc')->get(); // Ambil semua invoice, urutkan berdasarkan tgl_invoice

    //             if ($invoices->isNotEmpty()) {
    //                 // Dapatkan tanggal invoice terbaru
    //                 $currentLatestInvoiceDate = $invoices->first()->tgl_invoice;

    //                 // Jika tanggal invoice terbaru lebih baru dari yang sebelumnya, simpan nilainya
    //                 if (is_null($latestInvoiceDate) || $currentLatestInvoiceDate > $latestInvoiceDate) {
    //                     $latestInvoiceDate = $currentLatestInvoiceDate;
    //                     $kd_loket = $loket->kd_loket; // Simpan kd_loket dari loket yang memiliki tanggal terbaru
    //                 }
    //             }
    //         }

    //         // Jika ditemukan kd_loket dan tanggal invoice terbaru
    //         if ($kd_loket && $latestInvoiceDate) {
    //             $encryptedKdLoket = Crypt::encryptString($kd_loket);

    //             return redirect()->route('loketPembayaran.search', [
    //                 'kd_loket' => $encryptedKdLoket,
    //                 'tgl_invoice' => $latestInvoiceDate // Kirim tanggal invoice terbaru
    //             ]);
    //         } else {
    //             return redirect()->back()->with('error', 'Loket atau invoice tidak ditemukan.');
    //         }
    //     }

    //     // Logika untuk teknisi
    //     if ($user->hasRole('teknisi|super-admin|isp|loket|pelanggan')) {
    //         // Mengambil jumlah tiket berdasarkan status untuk teknisi
    //         $inProgressTickets = Tiket::where('status_tiket', 'Diproses')->count();
    //         $pengajuanTickets = Tiket::where('status_tiket', 'Pengajuan')->count();
    //         $completedTicketsCount = Tiket::where('status_tiket', 'Selesai')->count();

    //         $kd_isps = $user->isp->pluck('kd_isp');

    //         // Ambil jumlah semua pelanggan berdasarkan kd_isp
    //         $jumlahSemuaPelanggan = Pelanggan::whereIn('kd_isp', $kd_isps)->count();

    //         // Mendapatkan bulan dan tahun saat ini
    //         $bulanSekarang = Carbon::now()->month; // Contoh: 1 untuk Januari
    //         $tahunSekarang = Carbon::now()->year; // Contoh: 2024

    //         // Menghitung jumlah pelanggan baru untuk bulan sekarang
    //         $jumlahPelangganBaruBulanSekarang = Pelanggan::whereYear('created_at', $tahunSekarang)
    //             ->whereMonth('created_at', $bulanSekarang) // Menghitung untuk bulan saat ini
    //             ->count();

    //         // Ambil jumlah pelanggan yang berhenti berlangganan berdasarkan kd_isp
    //         $jumlahPelangganBerhenti = Pelanggan::whereIn('kd_isp', $kd_isps)
    //             ->where(function($query) {
    //                 $query->whereNull('username_pppoe')
    //                     ->orWhere('username_pppoe', ''); // Memeriksa apakah username_pppoe kosong
    //             })
    //             ->count();

    //         // Data cabang untuk pemilihan cabang mikrotik
    //         $cabangs = Cabang::all();

    //         // Data pelanggan aktif dan tidak aktif dari Mikrotik
    //         $secretnonactiveCount = 0;
    //         $activeCount = 0;
    //         $secretCount = 0;
    //         $data = []; // Data pelanggan dari Mikrotik (atau data lainnya)

    //         // Pastikan session cabang ada
    //         $selectedCabang = session()->get('kd_cabang');
    //         if ($selectedCabang) {
    //             $ip = session()->get('ip');
    //             $apiUser = session()->get('user');
    //             $pass = session()->get('pass');

    //             $API = new RouterosAPI();
    //             $API->debug(false);

    //             if ($API->connect($ip, $apiUser, $pass)) {
    //                 $data = $API->comm('/ppp/secret/print');
    //                 $secretactive = $API->comm('/ppp/active/print');
    //                 $secretnonactive = [];

    //                 foreach ($data as $semua) {
    //                     $namaSecret = $semua['name'];
    //                     $isNonActive = true;

    //                     foreach ($secretactive as $active) {
    //                         if ($active['name'] === $namaSecret) {
    //                             $isNonActive = false;
    //                             break;
    //                         }
    //                     }

    //                     if ($isNonActive) {
    //                         $secretnonactive[] = $semua;
    //                     }
    //                 }

    //                 $secretnonactiveCount = count($secretnonactive);
    //                 $activeCount = count($secretactive);
    //                 $secretCount = count($data);
    //             } else {
    //                 return view('pages.dashboard.index')->with([
    //                     'activeTicketsCount' => $pengajuanTickets,
    //                     'inProgressTicketsCount' => $inProgressTickets,
    //                     'completedTicketsCount' => $completedTicketsCount,
    //                     'data' => $data,
    //                     'cabangs' => $cabangs,
    //                     'user' => $user,
    //                     'secretnonactiveCount' => $secretnonactiveCount,
    //                     'activeCount' => $activeCount,
    //                     'secretCount' => $secretCount,
    //                     'jumlahSemuaPelanggan' => $jumlahSemuaPelanggan,
    //                     'jumlahPelangganBaruBulanSekarang' => $jumlahPelangganBaruBulanSekarang,
    //                     'jumlahPelangganBerhenti' => $jumlahPelangganBerhenti,
    //                     'error' => 'Gagal menghubungkan ke mikrotik.',
    //                 ]);
    //             }
    //         }

    //         // Mengirim semua data ke view
    //         return view('pages.dashboard.index', [
    //             'activeTicketsCount' => $pengajuanTickets,
    //             'inProgressTicketsCount' => $inProgressTickets,
    //             'completedTicketsCount' => $completedTicketsCount,
    //             'cabangs' => $cabangs,
    //             'secretnonactiveCount' => $secretnonactiveCount,
    //             'activeCount' => $activeCount,
    //             'secretCount' => $secretCount,
    //             'data' => $data,
    //             'user' => $user,
    //             'jumlahSemuaPelanggan' => $jumlahSemuaPelanggan,
    //             'jumlahPelangganBaruBulanSekarang' => $jumlahPelangganBaruBulanSekarang,
    //             'jumlahPelangganBerhenti' => $jumlahPelangganBerhenti,
    //         ]);
    //     }

    //     // Logika untuk user dengan peran selain teknisi
    //     // (Contoh logika lain bisa disesuaikan dengan kebutuhan peran yang lain)
    // }


    public function loginCabang(Request $request)
    {
        $validated = $request->validate([
            'kd_cabang' => 'required'
        ],
        [
            'kd_cabang.required' => 'Cabang harus diisi.'
        ]);


        $login = Cabang::where('kd_cabang', $request->post('kd_cabang'))->first();

        if ($login) {
            $ip = $login->ip_mikrotik;
            $user = $login->username_mikrotik;
            $pass = $login->password_mikrotik;

            // Save login data to session
            $data = [
                'ip' => $ip,
                'user' => $user,
                'pass' => $pass,
                'kd_cabang' => $login->kd_cabang,
                'cabang_nama' => $login->nm_cabang
            ];

            $request->session()->put($data);

            // Redirect to the dashboard
            return redirect()->route('dashboard.index');
        } else {
            return view('error.mikrotikerror');
        }
    }

    public function secretDashboard()
    {
        $user = Auth::user();
        $cabangs = collect();
        $data = [];
        $secretnonactiveCount = 0;
        $activeCount = 0;
        $secretCount = 0;

        // Fetch cabangs based on user roles
        if ($user->hasRole('super-admin') || $user->hasRole('isp')) {
            $cabangs = Cabang::where('kd_isp', $user->id)->get();
        } elseif ($user->hasRole('teknisi')) {
            $teknisi = $user->teknisi;
            foreach ($teknisi as $item) {
                $cabang = $item->cabang;
                if ($cabang) {
                    $cabangs->push($cabang);
                }
            }
        }

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $data = $API->comm('/ppp/secret/print');
            $secretactive = $API->comm('/ppp/active/print');
            $secretnonactive = [];
            foreach ($data as $semua) {
                $namaSecret = $semua['name'];
                $isNonActive = true;

                foreach ($secretactive as $active) {
                    if ($active['name'] === $namaSecret) {
                        $isNonActive = false;
                        break;
                    }
                }
                if ($isNonActive) {
                    $secretnonactive[] = $semua;
                }
            }


            $secretnonactiveCount = count($secretnonactive);
            $activeCount = count($secretactive);
            $secretCount = count($data);

            if (request()->wantsJson()) {
                return response()->json([
                    'data' => $data,
                    'cabangs' => $cabangs,
                    'secretCount' => $secretCount,
                    'activeCount' => $activeCount,
                    'secretnonactiveCount' => $secretnonactiveCount,
                    'user' => $user,
                ]);
            }            

            return view('pages.dashboard.index', compact('data', 'cabangs', 'secretCount', 'activeCount', 'secretnonactiveCount',  'user'));
        } else {
            return view('pages.dashboard.index')->with([
                'data' => $data,
                'cabangs' => $cabangs,
                'secretCount' => $secretCount,
                'activeCount' => $activeCount,
                'secretnonactiveCount' => $secretnonactiveCount,
                'user' => $user,
                'error' => 'Gagal menghubungkan ke mikrotik.',
            ]);
        }
    }

    public function activeDashboard()
    {
        $user = Auth::user();
        $cabangs = collect();
        $data = [];
        $secretnonactiveCount = 0;
        $activeCount = 0;
        $secretCount = 0;

        // Fetch cabangs based on user roles
        if ($user->hasRole('super-admin') || $user->hasRole('isp')) {
            $cabangs = Cabang::where('kd_isp', $user->id)->get();
        } elseif ($user->hasRole('teknisi')) {
            $teknisi = $user->teknisi;
            foreach ($teknisi as $item) {
                $cabang = $item->cabang;
                if ($cabang) {
                    $cabangs->push($cabang);
                }
            }
        }


        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secret = $API->comm('/ppp/secret/print');
            $data = $API->comm('/ppp/active/print');
            $secretactive = $API->comm('/ppp/active/print');
            $secretnonactive = [];
            foreach ($secret as $semua) {
                $namaSecret = $semua['name'];
                $isNonActive = true;

                foreach ($secretactive as $active) {
                    if ($active['name'] === $namaSecret) {
                        $isNonActive = false;
                        break;
                    }
                }
                if ($isNonActive) {
                    $secretnonactive[] = $semua;
                }
            }


            $secretnonactiveCount = count($secretnonactive);
            $activeCount = count($data);
            $secretCount = count($secret);

            if (request()->wantsJson()) {
                return response()->json([
                    'data' => $data,
                    'cabangs' => $cabangs,
                    'secretCount' => $secretCount,
                    'activeCount' => $activeCount,
                    'secretnonactiveCount' => $secretnonactiveCount,
                    'user' => $user,
                ]);
            }

            return view('pages.dashboard.index', compact('data', 'cabangs', 'activeCount', 'secretnonactiveCount', 'secretCount', 'user'));
        } else {
            return view('pages.dashboard.index')->with([
                'data' => $data,
                'cabangs' => $cabangs,
                'activeCount' => $activeCount,
                'secretnonactiveCount' => $secretnonactiveCount,
                'secretCount' => $secretCount,
                'user' => $user,
                'error' => 'Gagal menghubungkan ke mikrotik.',
            ]);
        }
    }

    public function nonActiveDashboard()
    {
        $user = Auth::user();
        $cabangs = collect();
        $data = [];
        $secretnonactiveCount = 0;
        $activeCount = 0;
        $secretCount = 0;

        // Fetch cabangs based on user roles
        if ($user->hasRole('super-admin') || $user->hasRole('isp')) {
            $cabangs = Cabang::where('kd_isp', $user->id)->get();
        } elseif ($user->hasRole('teknisi')) {
            $teknisi = $user->teknisi;
            foreach ($teknisi as $item) {
                $cabang = $item->cabang;
                if ($cabang) {
                    $cabangs->push($cabang);
                }
            }
        }

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secretactive = $API->comm('/ppp/active/print');
            $secret = $API->comm('/ppp/secret/print');

            $data = [];

            foreach ($secret as $semua) {
                $namaSecret = $semua['name'];
                $isNonActive = true;

                foreach ($secretactive as $active) {
                    if ($active['name'] === $namaSecret) {
                        $isNonActive = false;
                        break;
                    }
                }
                if ($isNonActive) {
                    $data[] = $semua;
                }
            }

            $secretnonactiveCount = count($data);
            $activeCount = count($secretactive);
            $secretCount = count($secret);

            if (request()->wantsJson()) {
                return response()->json([
                    'data' => $data,
                    'cabangs' => $cabangs,
                    'secretCount' => $secretCount,
                    'activeCount' => $activeCount,
                    'secretnonactiveCount' => $secretnonactiveCount,
                    'user' => $user,
                ]);
            } 

            return view('pages.dashboard.index', compact('data', 'cabangs', 'secretnonactiveCount', 'activeCount',  'secretCount', 'user'));
        } else {
            return view('pages.dashboard.index')->with([
                'data' => $data,
                'cabangs' => $cabangs,
                'secretnonactiveCount' => $secretnonactiveCount,
                'activeCount' => $activeCount,
                'secretCount' => $secretCount,
                'user' => $user,
                'error' => 'Gagal menghubungkan ke mikrotik.',
            ]);
        }
    }
}
