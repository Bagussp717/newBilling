<?php

namespace App\Http\Controllers;

use App\Models\ISP;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $user = Auth::user();
    $isps = $user->isp; // Mengambil ISP yang dimiliki pengguna

    // Ambil semua cabang dengan relasi ISP
    $cabangsQuery = Cabang::with('isp');

    if ($user->hasRole('super-admin')) {
        // Jika super-admin, ambil semua cabang
        $cabangs = $cabangsQuery->get();
        $isps = ISP::all();
    } elseif ($user->hasRole('isp')) {
        // Jika ISP, ambil cabang yang terkait dengan ISP pengguna
        $kd_isps = $isps->pluck('kd_isp'); // Mendapatkan kd_isp dari ISP yang dimiliki
        $cabangs = $cabangsQuery->whereIn('kd_isp', $kd_isps)->get();
    } elseif ($user->hasRole('teknisi')) {
        // Jika teknisi, ambil cabang yang terkait dengan teknisi tersebut
        $teknisi = $user->teknisi()->first(); // Ambil teknisi yang terkait dengan user
        if ($teknisi) {
            // Ambil semua kd_cabang yang terkait dengan teknisi
            $kd_cabangs = $teknisi->cabangs()->pluck('cabang_teknisis.kd_cabang')->toArray();
            // Filter cabang berdasarkan kd_cabang
            $cabangs = $cabangsQuery->whereIn('kd_cabang', $kd_cabangs)->get();
        } else {
            $cabangs = collect(); // Kosongkan koleksi jika tidak ada teknisi
        }
    } else {
        // Jika bukan super-admin, ISP, atau teknisi
        $cabangs = collect(); // Kosongkan koleksi
    }

    // Cek apakah permintaan adalah permintaan JSON
    if ($request->wantsJson()) {
        return response()->json([
            'cabangs' => $cabangs,
            'isps' => $isps
        ]);
    }

    return view('pages.cabang.index', compact('cabangs', 'isps'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Ambil ISP sesuai dengan peran pengguna
        if ($user->hasRole('super-admin')) {
            $isps = ISP::all(); // Jika super-admin, ambil semua ISP
        } elseif ($user->hasRole('isp')) {
            // Jika ISP, ambil ISP yang terkait dengan pengguna
            $isps = $user->isp; // Ambil ISP yang dimiliki oleh pengguna
        } else {
            $isps = collect(); // Kosongkan koleksi jika tidak ada role yang sesuai
        }
        
        if ($request->wantsJson()) {
            return response()->json(
                $isps
            );
        }

        return view('pages.cabang.create', compact('isps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nm_cabang' => 'required',
            'alamat_cabang' => 'required',
            'username_mikrotik' => 'required',
            'ip_mikrotik' => 'required',
            'password_mikrotik' => 'required',
            'kd_isp' => 'required',
        ], [
            'nm_cabang.required' => 'Namaan cabang harus diisi',
            'alamat_cabang.required' => 'Alamat mikrotik harus diisi',
            'username_mikrotik.required' => 'Username mikrotik harus diisi',
            'ip_mikrotik.required' => 'IP mikrotik harus diisi',
            'password_mikrotik.required' => 'Password mikrotik harus diisi',
            'kd_isp.required' => 'Kode ISP harus diisi',
        ]);

        $cabang = new Cabang();
        $cabang->nm_cabang = $request->nm_cabang;
        $cabang->alamat_cabang = $request->alamat_cabang;
        $cabang->username_mikrotik = $request->username_mikrotik;
        $cabang->ip_mikrotik = $request->ip_mikrotik;
        $cabang->password_mikrotik = $request->password_mikrotik;
        $cabang->kd_isp = $request->kd_isp;
        // dd($cabang);
        $cabang->save();

        return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kd_cabang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($kd_cabang)
    // {
    //     $cabang = Cabang::findOrFail($kd_cabang);
    //     $isps = ISP::all();

    //     return view('cabang.edit', compact('cabang', 'isps'));
    //     $cabang = Cabang::findOrFail($kd_cabang);
    //     $isps = ISP::all();

    //     return view('cabang.edit', compact('cabang', 'isps'));
    // }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $kd_cabang)
    {
        // Validasi input dari pengguna
        $request->validate([
            'nm_cabang' => 'required',
            'alamat_cabang' => 'required',
            'username_mikrotik' => 'required',
            'ip_mikrotik' => 'required',
            'password_mikrotik' => 'required',
            'kd_isp' => 'required',
        ], [
            'nm_cabang.required' => 'Nama cabang harus diisi',
            'alamat_cabang.required' => 'Alamat cabang harus diisi',
            'username_mikrotik.required' => 'Username mikrotik harus diisi',
            'ip_mikrotik.required' => 'IP mikrotik harus diisi',
            'password_mikrotik.required' => 'Password mikrotik harus diisi',
            'kd_isp.required' => 'Kode ISP harus diisi',
        ]);

        // Temukan cabang yang akan diperbarui
        $cabang = Cabang::findOrFail($kd_cabang);

        // Perbarui atribut dengan data dari permintaan
        $cabang->nm_cabang = $request->input('nm_cabang');
        $cabang->alamat_cabang = $request->input('alamat_cabang');
        $cabang->username_mikrotik = $request->input('username_mikrotik');
        $cabang->ip_mikrotik = $request->input('ip_mikrotik');
        $cabang->password_mikrotik = $request->input('password_mikrotik');
        $cabang->kd_isp = $request->input('kd_isp');

        // Simpan perubahan ke database
        // dd($cabang);
        $cabang->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil diubah');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $kd_cabang)
    {
        $decryptedId = Crypt::decryptString($kd_cabang);
        $cabang = Cabang::findOrFail($decryptedId);

        if ($cabang->pelanggans()->exists()) {
            return redirect()->route('cabang.index')->with(['error' => 'Cabang ini terkait dengan pelanggan dan tidak bisa dihapus.']);
        }

        $cabang->delete();
        return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil dihapus');
    }
}
