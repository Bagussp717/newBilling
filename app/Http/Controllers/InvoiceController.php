<?php

namespace App\Http\Controllers;

use App\Models\ISP;
use App\Models\Loket;
use App\Models\Paket;
use App\Models\Cabang;
use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->kd_role === 'super-admin') {
            $invoices = Invoice::with('pelanggan', 'isp')->orderBy('created_at', 'desc')->get();
            $isps = ISP::all(); // Mengambil semua ISP untuk super-admin
        } else {
            $isp = ISP::where('kd_user', $user->id)->first();
            if ($isp) {
                $invoices = Invoice::with('pelanggan', 'isp')
                    ->where('kd_isp', $isp->kd_isp)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $isps = collect([$isp]); // Hanya mengizinkan user ISP untuk memilih ISP mereka sendiri
            } else {
                // Jika tidak ada ISP yang terkait, kembalikan koleksi kosong
                $invoices = collect();
                $isps = collect();
            }
        }
        // Cek apakah request menginginkan JSON
        if ($request->wantsJson()) {
            return response()->json([
                'invoices' => $invoices,
                'isps' => $isps,
            ]);
    }

        return view('pages.invoice.index', compact('invoices', 'isps'));
    }

    public function showByPeriod(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'tgl_invoice' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_invoice',
        ]);

        // Ambil user yang sedang terautentikasi
        $user = Auth::user();

        // Ambil tanggal dari request
        $tgl_invoice = $request->input('tgl_invoice');
        $tgl_akhir = $request->input('tgl_akhir');

        // Cek apakah user adalah super-admin
        if ($user->kd_role === 'super-admin') {
            // Ambil semua Invoice untuk super-admin berdasarkan periode kecuali yang profile-nya "Gratis"
            $invoices = Invoice::with(['pelanggan.paket', 'pembayaran'])
                ->whereHas('pelanggan', function ($query) {
                    $query->where('profile_pppoe', '!=', 'Gratis'); // Filter profile yang tidak "Gratis"
                })
                ->whereBetween('tgl_invoice', [$tgl_invoice, $tgl_akhir])
                ->orderBy('tgl_invoice', 'asc')
                ->get();
        } elseif ($user->kd_role === 'loket') {
            // Ambil Loket terkait dengan user yang sedang terautentikasi
            $loket = Loket::where('kd_user', $user->id)->first();

            if ($loket && $loket->isp) {
                // Ambil Invoice yang terkait dengan ISP dari Loket berdasarkan periode kecuali yang profile-nya "Gratis"
                $invoices = Invoice::with(['pelanggan.paket', 'pembayaran'])
                    ->whereHas('pelanggan', function ($query) use ($loket) {
                        $query->where('kd_isp', $loket->isp->kd_isp)
                            ->where('profile_pppoe', '!=', 'Gratis'); // Filter profile yang tidak "Gratis"
                    })
                    ->whereBetween('tgl_invoice', [$tgl_invoice, $tgl_akhir])
                    ->orderBy('tgl_invoice', 'asc')
                    ->get();
            } else {
                $invoices = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
            }
        } else {
            // Ambil ISP terkait dengan user yang sedang terautentikasi
            $isp = ISP::where('kd_user', $user->id)->first();

            if ($isp) {
                // Ambil Invoice yang terkait dengan ISP dari user yang sedang terautentikasi berdasarkan periode kecuali yang profile-nya "Gratis"
                $invoices = Invoice::with(['pelanggan.paket', 'pembayaran'])
                    ->whereHas('pelanggan', function ($query) use ($isp) {
                        $query->where('kd_isp', $isp->kd_isp)
                            ->where('profile_pppoe', '!=', 'Gratis'); // Filter profile yang tidak "Gratis"
                    })
                    ->whereBetween('tgl_invoice', [$tgl_invoice, $tgl_akhir])
                    ->orderBy('tgl_invoice', 'asc')
                    ->get();
            } else {
                $invoices = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
            }
        }

         // Cek apakah request menginginkan JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'invoices' => $invoices
                ]);
            }

        // Kembalikan view dengan data invoices yang sudah difilter
        return view('pages.invoice.periode_invoice', compact('invoices', 'tgl_invoice', 'tgl_akhir'));
    }



    /**
     * Menampilkan semua invoice, filterable berdasarkan cabang, dan tgl_invoice
     *
     * @return \Illuminate\Http\Response
     */
    // public function allindex()
    // {
    //     // Ambil user yang sedang terautentikasi
    //     $user = Auth::user();

    //     // Cek apakah user adalah super-admin
    //     if ($user->kd_role === 'super-admin') {
    //         // Ambil semua Invoice untuk super-admin
    //         $invoices = Invoice::with(['pelanggan.paket', 'pembayaran', 'cabang'])
    //             ->orderBy('tgl_akhir', 'desc')
    //             ->get();
    //     } elseif ($user->kd_role === 'loket') {
    //         // Ambil Loket terkait dengan user yang sedang terautentikasi
    //         $loket = Loket::where('kd_user', $user->id)->first();

    //         if ($loket && $loket->isp) {
    //             // Ambil Invoice yang terkait dengan ISP dari Loket
    //             $invoices = Invoice::with(['pelanggan.paket', 'pembayaran', 'cabang'])
    //                 ->whereHas('pelanggan', function ($query) use ($loket) {
    //                     $query->where('kd_isp', $loket->isp->kd_isp);
    //                 })
    //                 ->orderBy('tgl_akhir', 'desc')
    //                 ->get();
    //         } else {
    //             $invoices = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
    //         }
    //     } else {
    //         // Ambil ISP terkait dengan user yang sedang terautentikasi
    //         $isp = ISP::where('kd_user', $user->id)->first();

    //         if ($isp) {
    //             // Ambil Invoice yang terkait dengan ISP dari user yang sedang terautentikasi
    //             $invoices = Invoice::with(['pelanggan.paket', 'pembayaran', 'cabang'])
    //                 ->whereHas('pelanggan', function ($query) use ($isp) {
    //                     $query->where('kd_isp', $isp->kd_isp);
    //                 })
    //                 ->orderBy('tgl_akhir', 'desc')
    //                 ->get();
    //         } else {
    //             $invoices = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
    //         }
    //     }

    //     // Kembalikan view dengan data invoices yang sudah difilter
    //     return view('pages.invoice.all_invoice', compact('invoices'));
    // }
    public function allindex(Request $request)
    {
        // Ambil user yang sedang terautentikasi
        $user = Auth::user();
    
        // Variabel untuk menampung data invoices
        $invoicesQuery = Invoice::with(['pelanggan.paket', 'pembayaran', 'cabang'])
            ->whereHas('pelanggan', function ($query) {
                $query->where('profile_pppoe', '!=', 'Gratis'); // Filter profile yang tidak "Gratis"
            });
    
        // Cek role pengguna
        if ($user->kd_role === 'super-admin') {
            // Tidak ada filter tambahan untuk super-admin
        } elseif ($user->kd_role === 'loket') {
            $loket = Loket::where('kd_user', $user->id)->first();
            if ($loket && $loket->isp) {
                $invoicesQuery->whereHas('pelanggan', function ($query) use ($loket) {
                    $query->where('kd_isp', $loket->isp->kd_isp);
                });
            } else {
                $invoicesQuery = collect(); // Koleksi kosong jika tidak ada ISP
            }
        } else {
            $isp = ISP::where('kd_user', $user->id)->first();
            if ($isp) {
                $invoicesQuery->whereHas('pelanggan', function ($query) use ($isp) {
                    $query->where('kd_isp', $isp->kd_isp);
                });
            } else {
                $invoicesQuery = collect(); // Koleksi kosong jika tidak ada ISP
            }
        }

        $search = $request->get('search', '');

        if (!empty($search)) {
            // Menambahkan pencarian berdasarkan kolom yang relevan
            $invoicesQuery->where(function($query) use ($search) {
                $query->where('kd_invoice', 'like', "%$search%")
                      ->orWhere('kd_pelanggan', 'like', "%$search%")
                      ->orWhereHas('pelanggan', function($query) use ($search) {
                          $query->where('username_pppoe', 'like', "%$search%"); // Asumsi nama pelanggan
                      });
            });
        }
    
        if (is_a($invoicesQuery, 'Illuminate\Support\Collection')) {
            $invoices = $invoicesQuery; // Jika query kosong, koleksi tetap
        } else {
            // Urutkan data berdasarkan tgl_akhir
            $invoicesQuery->orderBy('tgl_akhir', 'desc');
    
            // Periksa jika `wantsJson` untuk memberikan data dengan paginasi
            if ($request->wantsJson()) {
                $page = $request->get('page', 1); // Ambil halaman saat ini
                $perPage = $request->get('per_page', 10); // Ambil ukuran halaman
                $invoices = $invoicesQuery->paginate($perPage, ['*'], 'page', $page);
    
                return response()->json($invoices);
            }
    
            // Jika tidak JSON, ambil semua data
            $invoices = $invoicesQuery->get();
        }
    
        // Kembalikan view dengan data invoices yang sudah difilter
        return view('pages.invoice.all_invoice', compact('invoices'));
    }
    




    public function pelanggan(Request $request)
{
    $user = Auth::user();

    // Mengambil query dasar dengan filter pelanggan berdasarkan user ID
    $invoicesQuery = Invoice::with(['pelanggan.paket', 'pembayaran', 'cabang'])
        ->whereHas('pelanggan', function ($query) use ($user) {
            $query->where('kd_user', $user->id);
        })
        ->orderBy('tgl_akhir', 'desc');

    // Ambil parameter pencarian
    $search = $request->get('search', '');

    if (!empty($search)) {
        // Menambahkan filter pencarian
        $invoicesQuery->where(function ($query) use ($search) {
            $query->where('kd_invoice', 'like', "%$search%")
                ->orWhere('tgl_akhir', 'like', "%$search%")
                ->orWhereHas('pelanggan', function ($query) use ($search) {
                    $query->where('username_pppoe', 'like', "%$search%");
                });
        });
    }

    
    // Jika request JSON, kembalikan data dalam format JSON
    if ($request->wantsJson()) {
        // Pagination dengan jumlah item per halaman
        $perPage = $request->get('per_page', 10); // Default 10 item per halaman
        $invoices = $invoicesQuery->paginate($perPage);
        return response()->json($invoices);
    }
    $invoices = $invoicesQuery->get();

    // Kembalikan ke view dengan data paginated
    return view('pages.invoice.pelanggan', compact('invoices'));
}





    public function isolirByPelanggan($kd_pelanggan)
    {
        $customer = Pelanggan::where('kd_pelanggan', $kd_pelanggan)->first();

        if (!$customer) {
            return view('error.notfound');
        }

        // Ambil data cabang berdasarkan cabang pelanggan
        $cabang = Cabang::where('kd_cabang', $customer->kd_cabang)->first();

        if (!$cabang) {
            return view('error.notfound');
        }

        $ip = $cabang->ip_mikrotik;
        $user = $cabang->username_mikrotik;
        $pass = $cabang->password_mikrotik;

        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $profiles = $API->comm('/ppp/profile/print');

            $isolirProfileId = null;
            // Cari profil yang bernama 'isolir'
            foreach ($profiles as $profile) {
                if ($profile['name'] == 'isolir') {
                    $isolirProfileId = $profile['.id'];
                    break;
                }
            }

            if (!$isolirProfileId) {
                $API->disconnect();
                return view('error.mikrotikerror');
            }

            // Ambil semua secret dari Mikrotik
            $secrets = $API->comm('/ppp/secret/print');

            $secretMap = [];
            foreach ($secrets as $secret) {
                $secretMap[$secret['name']] = $secret['.id'];
            }

            // Jika username pelanggan ditemukan di Mikrotik
            if (isset($secretMap[$customer->username_pppoe])) {
                Log::info("Match found for Customer {$customer->kd_pelanggan}!");

                $secretId = $secretMap[$customer->username_pppoe];

                // Update profil PPPoE pelanggan ke 'isolir'
                $API->comm('/ppp/secret/set', [
                    '.id' => $secretId,
                    'profile' => $isolirProfileId
                ]);

                // Update status PPPoE di database menjadi 'isolir'
                Invoice::where('kd_pelanggan', $customer->kd_pelanggan)
                    ->update(['status_pppoe' => 'isolir']);
            }

            $API->disconnect();

            return redirect()->back()->with('success', 'Pelanggan berhasil di isolir');
        } else {
            return view('error.mikrotikerror');
        }
    }

    public function pulihkanByPelanggan($kd_pelanggan)
    {
        // Ambil data pelanggan berdasarkan kd_pelanggan
        $customer = Pelanggan::where('kd_pelanggan', $kd_pelanggan)->first();

        if (!$customer) {
            return view('error.notfound'); // Handle jika pelanggan tidak ditemukan
        }

        // Ambil data cabang berdasarkan cabang pelanggan
        $cabang = Cabang::where('kd_cabang', $customer->kd_cabang)->first();

        if (!$cabang) {
            return view('error.notfound'); // Handle jika cabang tidak ditemukan
        }

        // Login ke Mikrotik menggunakan data dari tabel cabang
        $ip = $cabang->ip_mikrotik;
        $user = $cabang->username_mikrotik;
        $pass = $cabang->password_mikrotik;

        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            // Ambil semua profil PPPoE dari Mikrotik
            $profiles = $API->comm('/ppp/profile/print');

            // Profil yang akan dipulihkan berdasarkan data pelanggan
            $profileName = $customer->profile_pppoe;

            $profileId = null;
            // Cari profil berdasarkan nama di Mikrotik
            foreach ($profiles as $profile) {
                if ($profile['name'] == $profileName) {
                    $profileId = $profile['.id'];
                    break;
                }
            }

            if (!$profileId) {
                $API->disconnect();
                return view('error.mikrotikerror'); // Handle jika profil yang akan dipulihkan tidak ditemukan
            }

            // Ambil semua secret dari Mikrotik
            $secrets = $API->comm('/ppp/secret/print');

            $secretMap = [];
            // Buat map dari secrets berdasarkan username pelanggan
            foreach ($secrets as $secret) {
                $secretMap[$secret['name']] = $secret['.id'];
            }

            // Jika username pelanggan ditemukan di Mikrotik
            if (isset($secretMap[$customer->username_pppoe])) {
                Log::info("Match found for Customer {$customer->kd_pelanggan}! Restoring profile to {$profileName}.");

                $secretId = $secretMap[$customer->username_pppoe];

                // Update profil PPPoE pelanggan ke profil yang lama
                $API->comm('/ppp/secret/set', [
                    '.id' => $secretId,
                    'profile' => $profileId
                ]);

                // Update status PPPoE di database menjadi nama profil yang lama
                Invoice::where('kd_pelanggan', $customer->kd_pelanggan)
                    ->update(['status_pppoe' => $profileName]);

                $API->disconnect();

                return redirect()->back()->with('success', 'Profile pelanggan berhasil dipulihkan');
            } else {
                $API->disconnect();
                return view('error.notfound'); // Handle jika username pelanggan tidak ditemukan di Mikrotik
            }
        } else {
            return view('error.mikrotikerror'); // Handle jika gagal terhubung ke Mikrotik
        }
    }


    public function generateInvoices(Request $request)
    {
        // Validasi input request
        $request->validate([
            'tgl_invoice' => 'required',
            'tgl_akhir' => 'required',
            'kd_isp' => 'required_if:kd_role,super-admin', // Pastikan kd_isp diisi untuk super-admin
        ],
        [
            'tgl_invoice.required' => 'Tgl invoice harus diisi.',
            'tgl_akhir.required' => 'Tgl akhir harus diisi.',
            'kd_isp.required_if' => 'ISP harus dipilih jika Anda adalah super-admin.',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Jika user adalah super-admin, ambil kd_isp dari request
        if ($user->kd_role === 'super-admin') {
            $kd_isp = $request->kd_isp;
        } else {
            // Ambil ISP yang terkait dengan user jika user adalah ISP
            $isp = ISP::where('kd_user', $user->id)->first();

            if (!$isp) {
                // Jika user tidak memiliki ISP terkait, batalkan proses dan kembalikan pesan error
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk generate invoice.');
            }
            $kd_isp = $isp->kd_isp; // Ambil kd_isp dari ISP yang terkait
        }

        // Filter pelanggan yang memiliki kd_isp yang sama
        $errorPelanggan = [];
        Pelanggan::where('kd_isp', $kd_isp)->chunk(100, function ($pelanggans) use ($request, $kd_isp, &$errorPelanggan) {
            foreach ($pelanggans as $pelanggan) {
                // Cek apakah kd_paket atau kd_loket kosong
                if (empty($pelanggan->kd_paket) || empty($pelanggan->kd_loket)) {
                    // Jika ada yang kosong, tambahkan ke daftar error
                    $errorPelanggan[] = $pelanggan->username_pppoe;
                }
            }

            // Jika ada pelanggan yang error, hentikan proses
            if (!empty($errorPelanggan)) {
                return false;
            }
        });

        // Jika ada pelanggan yang memiliki kolom kosong, kembalikan pesan error dan jangan buat invoice
        if (!empty($errorPelanggan)) {
            return redirect()->back()->with('error', 'Pelanggan berikut memiliki kolom "kd_paket" atau "kd_loket" yang kosong: ' . implode(', ', $errorPelanggan) . '. Invoice tidak dapat dibuat.');
        }

        // Jika tidak ada error, lanjutkan membuat invoice
        Pelanggan::where('kd_isp', $kd_isp)->chunk(100, function ($pelanggans) use ($request, $kd_isp) {
            foreach ($pelanggans as $pelanggan) {
                // Membuat invoice untuk setiap pelanggan dengan kd_isp yang sama
                Invoice::create([
                    'kd_pelanggan' => $pelanggan->kd_pelanggan,
                    'kd_isp' => $kd_isp, // Menyimpan kd_isp yang dipilih ke invoice
                    'tgl_invoice' => $request->tgl_invoice,
                    'tgl_akhir' => $request->tgl_akhir,
                    'status_pppoe' => $pelanggan->profile_pppoe ?? '', // Jika status PPPoE null, gunakan string kosong
                ]);
            }
        });

        // Redirect kembali ke halaman index invoice dengan pesan sukses
        return redirect()->route('invoice.index')->with('success', 'Invoices berhasil di generate untuk ISP Anda.');
    }


    // public function small($kd_invoice)
    // {
    //     $decryptedId = Crypt::decryptString($kd_invoice);
    //     $invoice = Invoice::where('kd_invoice', $decryptedId)->first();
    //     $pelanggan = Pelanggan::all();
    //     return view('pages.invoice.invoice_kecil', compact('invoice', 'pelanggan'));
    // }

    public function small($kd_invoice)
    {
        try {
            $decryptedId = Crypt::decryptString($kd_invoice);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Return a meaningful error message, log the error or redirect
            return redirect()->back()->withErrors('Failed to decrypt the invoice ID.');
        }

        $invoice = Invoice::where('kd_invoice', $decryptedId)->first();
        $pelanggan = Pelanggan::all();

        return view('pages.invoice.invoice_kecil', compact('invoice', 'pelanggan'));
    }


    public function viewinvoice()
    {
        return view('pages.invoice.template');
    }

    public function fullpage(Request $request, $kd_invoice)
    {
        $decryptedId = Crypt::decryptString($kd_invoice);
        $invoice = Invoice::with(['pelanggan.paket', 'pembayaran'])->findOrFail($decryptedId);
        $pelanggan = Pelanggan::all();
        if ($request->wantsJson()) {
            return response()->json([
                'invoices' => $invoice,               
            ]);
        }
        
        return view('pages.invoice.invoice_fullpage', compact('invoice', 'pelanggan'));
    }

    public function destroyByInvoiceDate(Request $request)
    {
        // Validasi tanggal invoice
        $request->validate([
            'tgl_invoice' => 'required|date',
        ], [
            'tgl_invoice.required' => 'Tanggal Invoice harus diisi.',
            'tgl_invoice.date' => 'Tanggal Invoice tidak valid.',
        ]);

        // Ambil semua kd_pelanggan yang terkait dengan tanggal invoice yang dipilih
        $kdPelanggans = Invoice::where('tgl_invoice', $request->tgl_invoice)
                               ->pluck('kd_pelanggan');

        // Hapus semua invoice berdasarkan tgl_invoice yang dipilih
        Invoice::where('tgl_invoice', $request->tgl_invoice)->delete();


        return redirect()->route('invoice.index')->with('success', 'Invoices dan pelanggan pada tanggal ' . $request->tgl_invoice . ' berhasil dihapus');
    }
}
