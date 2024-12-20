<?php

namespace App\Http\Controllers;

use App\Models\ISP;
use App\Models\User;
use App\Models\Loket;
use App\Models\Cabang;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class LoketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $lokets = Loket::all();
    //     return view('pages.loket.index', compact('lokets'));
    // }

    public function index()
    {
        // Ambil user yang sedang terautentikasi
        $user = Auth::user();

        // Cek apakah user adalah super-admin
        if ($user->kd_role === 'super-admin') {
            // Ambil semua Loket untuk super-admin
            $lokets = Loket::with('cabang', 'isp')->orderBy('created_at', 'desc')->get();
        } elseif ($user->kd_role === 'teknisi') {
            // Ambil Teknisi terkait dengan user yang sedang terautentikasi
            $teknisi = Teknisi::where('kd_user', $user->id)->first();

            if ($teknisi && $teknisi->isp) {
                // Ambil Loket yang terkait dengan ISP dari Teknisi
                $lokets = Loket::with('cabang', 'isp')
                    ->where('kd_isp', $teknisi->isp->kd_isp) // Filter berdasarkan kd_isp dari ISP
                    ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal dibuat
                    ->get();
            } else {
                $lokets = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
            }
        } else {
            // Ambil ISP terkait dengan user yang sedang terautentikasi
            $isp = ISP::where('kd_user', $user->id)->first();

            if ($isp) {
                // Ambil Loket yang terkait dengan ISP dari user yang sedang terautentikasi
                $lokets = Loket::with('cabang', 'isp')
                    ->where('kd_isp', $isp->kd_isp) // Filter berdasarkan kd_isp dari ISP
                    ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal dibuat
                    ->get();
            } else {
                $lokets = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
            }
        }

        return view('pages.loket.index', compact('lokets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user(); // Ambil user yang sedang terautentikasi

        // Query untuk cabang
        $cabangsQuery = Cabang::query();

        // Cek apakah user adalah loket atau ISP
        if ($user->kd_role === 'loket' || $user->kd_role === 'isp') {
            // Ambil Loket atau ISP terkait dengan user yang sedang terautentikasi
            $loketOrISP = Loket::where('kd_user', $user->id)->first() ?: ISP::where('kd_user', $user->id)->first();

            if ($loketOrISP) {
                // Ambil ISP yang terkait dengan loket atau ISP yang sedang login
                $isps = ISP::where('kd_isp', $loketOrISP->kd_isp)->get();

                // Ambil kd_isp dari ISP yang terkait dengan loket atau ISP
                $kd_isps = $isps->pluck('kd_isp'); // Mendapatkan nilai kd_isp

                // Filter cabang berdasarkan kd_isp yang didapatkan dari ISP loket atau ISP
                $cabangs = $cabangsQuery->whereIn('kd_isp', $kd_isps)->get();
            } else {
                // Jika tidak ada loket/ISP, cabang dikosongkan dan ISP dikosongkan
                $cabangs = collect();
                $isps = collect(); // Kosongkan ISP juga jika tidak ada loket/ISP terkait
            }
        } else {
            // Jika bukan loket atau ISP, ambil semua cabang dan ISP
            $cabangs = $cabangsQuery->get();
            $isps = ISP::all(); // Ambil semua ISP
        }

        // Mengembalikan view dengan data cabang dan ISP
        return view('pages.loket.create', compact('cabangs', 'isps'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nm_loket' => 'required|string|max:255',
            'alamat_loket' => 'required|string|max:255',
            'kd_isp' => 'required',
            'kd_cabang' => 'required|exists:cabangs,kd_cabang',
            'jenis_komisi' => 'required|in:fixed,dynamic',
            'jml_komisi' => 'nullable|integer',
        ], [
            // Pesan error kustom
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus minimal 6 karakter.',
            'nm_loket.required' => 'Nama loket harus diisi.',
            'alamat_loket.required' => 'Alamat loket harus diisi.',
            'kd_isp.required' => 'Harus memilih ISP.',
            'kd_cabang.required' => 'Kode cabang harus diisi.',
            'kd_cabang.exists' => 'Kode cabang tidak valid.',
            'jenis_komisi.required' => 'Jenis komisi harus dipilih.',
            'jml_komisi.integer' => 'Jumlah komisi harus berupa angka.',
        ]);

        // Ambil pengguna yang sedang login
        $loggedUser = Auth::user();

        // Jika pengguna memiliki role 'isp', ambil kd_isp dari pengguna
        $kd_isp = $loggedUser->hasRole('isp') ? $loggedUser->isp->first()->kd_isp : $request->kd_isp;

        // Buat user baru
        $user = new User();
        $user->name = $request->nm_loket;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->kd_role = 'loket';
        $user->save();

        // Assign role loket ke user
        $user->assignRole('loket');

        // Buat loket baru
        $loket = new Loket();
        $loket->nm_loket = $request->nm_loket;
        $loket->alamat_loket = $request->alamat_loket;
        $loket->kd_user = $user->id;
        $loket->kd_isp = $kd_isp; // Otomatis isi kd_isp jika pengguna ISP
        $loket->kd_cabang = $request->kd_cabang;
        $loket->jenis_komisi = $request->jenis_komisi;
        $loket->jml_komisi = $request->jml_komisi ?? null; // Jika tidak ada, biarkan null
        $loket->save();

        // Redirect dengan pesan sukses
        return redirect()->route('loket.index')->with('success', 'Loket berhasil ditambahkan');
    }


    // public function edit($id)
    // {
    //     $decryptedId = Crypt::decryptString($id);
    //     $loket = Loket::findOrFail($decryptedId);
    //     $cabangs = Cabang::all();
    //     return view('pages.loket.edit', compact('loket', 'cabangs'));
    // }

    public function edit($id)
    {
        // Dekripsi ID
        $decryptedId = Crypt::decryptString($id);

        // Ambil Loket berdasarkan ID yang didekripsi
        $loket = Loket::findOrFail($decryptedId);

        // Ambil user yang sedang terautentikasi
        $user = Auth::user();

        // Query untuk mengambil cabang dan ISP
        $cabangsQuery = Cabang::query();
        $ispsQuery = ISP::query();

        // Cek apakah user adalah super-admin
        if ($user->kd_role === 'super-admin') {
            // Super-admin bisa melihat semua cabang dan ISP
            $cabangs = $cabangsQuery->get();
            $isps = $ispsQuery->get();
        } elseif ($user->kd_role === 'teknisi') {
            // Ambil Teknisi terkait dengan user yang sedang terautentikasi
            $teknisi = Teknisi::where('kd_user', $user->id)->first();

            if ($teknisi && $teknisi->isp) {
                // Batasi cabang dan ISP berdasarkan ISP teknisi
                $isps = $ispsQuery->where('kd_isp', $teknisi->kd_isp)->get();
                $cabangs = $cabangsQuery->where('kd_isp', $teknisi->kd_isp)->get();
            } else {
                // Jika teknisi tidak terkait dengan ISP, kembalikan koleksi kosong
                $isps = collect();
                $cabangs = collect();
            }
        } elseif ($user->kd_role === 'isp') {
            // Batasi cabang dan ISP untuk user dengan role 'isp'
            $isp = ISP::where('kd_user', $user->id)->first();

            if ($isp) {
                $isps = $ispsQuery->where('kd_isp', $isp->kd_isp)->get();
                $cabangs = $cabangsQuery->where('kd_isp', $isp->kd_isp)->get();
            } else {
                $isps = collect();
                $cabangs = collect();
            }
        } else {
            // Jika user tidak memiliki role yang sesuai, kembalikan ke halaman lain
            return redirect()->route('loket.index')->with('error', 'Anda tidak memiliki akses untuk mengedit loket ini.');
        }

        // Kembalikan view untuk edit loket dengan data yang sudah difilter sesuai role
        return view('pages.loket.edit', compact('loket', 'cabangs', 'isps'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_loket' => 'required|string|max:255',
            'alamat_loket' => 'required|string|max:255',
            'kd_cabang' => 'required|exists:cabangs,kd_cabang',
            'jenis_komisi' => 'required|in:fixed,dynamic',
            'jml_komisi' => 'nullable|integer',
            'kd_role' => 'nullable',
        ], [
            'nm_loket.required' => 'Nama loket harus diisi.',
            'alamat_loket.required' => 'Alamat loket harus diisi.',
            'kd_cabang.required' => 'Kode cabang harus diisi.',
            'jenis_komisi.required' => 'Jenis komisi harus dipilih.',
        ]);

        $decryptedId = Crypt::decryptString($id);
        $loket = Loket::findOrFail($decryptedId);
        $user = User::findOrFail($loket->kd_user);

        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->kd_role = $request->kd_role ?? null;
        $user->save();

        $loket->kd_cabang = $request->kd_cabang;
        $loket->nm_loket = $request->nm_loket;
        $loket->alamat_loket = $request->alamat_loket;
        $loket->jenis_komisi = $request->jenis_komisi;
        $loket->jml_komisi = $request->jml_komisi ?? null;
        $loket->save();

        return redirect()->route('loket.index')->with('success', 'Loket berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $decryptedId = Crypt::decryptString($id);
        $loket = Loket::findOrFail($decryptedId);
        $user = User::findOrFail($loket->kd_user);

        $loket->delete();
        $user->delete();

        return redirect()->route('loket.index')->with('success', 'Loket berhasil dihapus');
    }
}
