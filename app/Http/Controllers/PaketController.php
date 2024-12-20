<?php

namespace App\Http\Controllers;


use App\Models\ISP;
use App\Models\Paket;
use App\Models\Cabang;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaketController extends Controller
{
    /**
     * Menampilkan daftar paket.
     */
    public function index()
{
    // Ambil user yang sedang terautentikasi
    $user = Auth::user();
    $pakets = collect(); // Inisialisasi koleksi kosong untuk paket
    $cabangs = Cabang::all(); // Ambil semua cabang

    // Cek apakah user adalah super-admin
    if ($user->kd_role === 'super-admin') {
        // Ambil semua Paket untuk super-admin
        $pakets = Paket::with('cabang', 'isp')->orderBy('created_at', 'desc')->get();
    } elseif ($user->kd_role === 'teknisi') {
        // Ambil Teknisi terkait dengan user yang sedang terautentikasi
        $teknisi = Teknisi::where('kd_user', $user->id)->first();

        if ($teknisi && $teknisi->isp) {
            // Ambil Paket yang terkait dengan ISP dari Teknisi
            $pakets = Paket::with('cabang', 'isp')
                ->where('kd_isp', $teknisi->isp->kd_isp) // Filter berdasarkan kd_isp dari ISP
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal dibuat
                ->get();
        }
    } else {
        // Ambil ISP terkait dengan user yang sedang terautentikasi
        $isp = ISP::where('kd_user', $user->id)->first();

        if ($isp) {
            // Ambil Paket yang terkait dengan ISP dari user yang sedang terautentikasi
            $pakets = Paket::with('cabang', 'isp')
                ->where('kd_isp', $isp->kd_isp) // Filter berdasarkan kd_isp dari ISP
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal dibuat
                ->get();
        }
    }

    // Jika permintaan menginginkan JSON, kembalikan respons JSON
    if (request()->wantsJson()) {
        return response()->json([
            'cabangs' => $cabangs,
            'pakets' => $pakets,
        ]);
    }

    // Jika tidak, kembalikan tampilan
    return view('pages.paket.index', compact('pakets', 'cabangs'));
}


    /**
     * Menampilkan form untuk membuat paket baru.
     */
    public function create()
    {
        $user = Auth::user(); // Ambil user yang sedang terautentikasi
        $cabangs = collect(); // Inisialisasi koleksi kosong untuk cabang
        $isps = collect(); // Inisialisasi koleksi kosong untuk ISP

        // Cek apakah user adalah teknisi atau ISP
        if ($user->kd_role === 'teknisi' || $user->kd_role === 'isp') {
            // Ambil Teknisi atau ISP terkait dengan user yang sedang terautentikasi
            $teknisiOrISP = Teknisi::where('kd_user', $user->id)->first() ?: ISP::where('kd_user', $user->id)->first();

            if ($teknisiOrISP) {
                // Ambil ISP yang terkait dengan teknisi atau ISP yang sedang login
                $isps = ISP::where('kd_isp', $teknisiOrISP->kd_isp)->get();

                // Ambil kd_isp dari ISP yang terkait dengan teknisi atau ISP
                $kd_isps = $isps->pluck('kd_isp'); // Mendapatkan nilai kd_isp

                // Filter cabang berdasarkan kd_isp yang didapatkan dari ISP teknisi atau ISP
                $cabangs = Cabang::whereIn('kd_isp', $kd_isps)->get();
            }
        } else {
            // Jika bukan teknisi atau ISP, ambil semua cabang dan ISP
            $cabangs = Cabang::all(); 
            $isps = ISP::all(); // Ambil semua ISP
        }

        // Mengembalikan view dengan data cabang dan ISP
        return view('pages.paket.create', compact('cabangs', 'isps'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nm_paket' => 'required',
            'hrg_paket' => 'required',
            'kd_cabang' => 'required',
            'kd_isp' => 'required', // Tambahkan validasi untuk kd_isp
        ], [
            'nm_paket.required' => 'Nama paket harus diisi.',
            'hrg_paket.required' => 'Harga paket harus diisi.',
            'kd_cabang.required' => 'Kode cabang harus diisi.',
            'kd_isp.required' => 'Kode ISP harus diisi.', // Tambahkan pesan kesalahan untuk kd_isp
        ]);

        // Membuat instance Paket baru
        $paket = new Paket();
        $paket->nm_paket = $request->nm_paket;
        $paket->hrg_paket = $request->hrg_paket;
        $paket->kd_cabang = $request->kd_cabang;
        $paket->kd_isp = $request->kd_isp; // Tambahkan kd_isp ke dalam model Paket
        $paket->save(); // Simpan data paket

        return redirect()->route('paket.index')->with('success', 'Paket berhasil ditambahkan.');
    }


    public function edit($kd_paket)
    {
        // Dekripsi ID paket
        $decryptedId = Crypt::decryptString($kd_paket);
        
        // Temukan Paket berdasarkan ID yang telah didekripsi
        $paket = Paket::findOrFail($decryptedId);
        
        // Ambil semua cabang dari database
        $cabangs = Cabang::all();
        
        // Ambil semua ISP dari database
        $isps = ISP::all(); // Pastikan Anda memiliki model ISP

        // Kembalikan view dengan data paket, cabang, dan ISP
        return view('pages.paket.edit', compact('paket', 'cabangs', 'isps'));
    }


    
    public function update(Request $request, $kd_paket)
    {
        $paket = Paket::findOrFail($kd_paket);
    
        // Validasi input
        $request->validate([
            'nm_paket' => 'required',
            // 'hrg_paket' => 'required',
            'kd_cabang' => 'required',
        ], [
            'nm_paket.required' => 'Nama paket harus diisi.',
            // 'hrg_paket.required' => 'Harga paket harus diisi.',
            'kd_cabang.required' => 'Kode cabang harus diisi.',
        ]);

        
        $paket = Paket::findOrFail($kd_paket);
        $paket->nm_paket = $request->nm_paket;
        $paket->hrg_paket = $request->hrg_paket;
        $paket->kd_cabang = $request->kd_cabang;
        $paket->keterangan = $request->keterangan;
        // dd($paket);
        $paket->save();
    
        // Mengarahkan kembali dengan pesan sukses
        return redirect()->route('paket.index')->with('success', 'Paket berhasil diperbarui.');
    }
   

    public function destroy($kd_paket)
    {
        $paket = Paket::findOrFail($kd_paket);

        if ($paket->pelanggans()->exists()) {
            return redirect()->route('paket.index')->withErrors(['error' => 'Paket ini terkait dengan pelanggan dan tidak bisa dihapus.']);
        }
        $paket->delete();
        return redirect()->route('paket.index')->with('success', 'Paket berhasil dihapus.');
    }


    
}
