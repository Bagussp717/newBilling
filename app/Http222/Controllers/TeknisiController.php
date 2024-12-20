<?php

namespace App\Http\Controllers;

use App\Models\ISP;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class TeknisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // Ambil pengguna yang sedang login
        $teknisis = collect(); // Inisialisasi koleksi teknisi

        // Cek peran pengguna
        if ($user->hasRole('super-admin')) {
            // Jika super-admin, ambil semua teknisi
            $teknisis = Teknisi::all();
        } elseif ($user->hasRole('isp')) {
            // Jika ISP, ambil teknisi yang terkait dengan ISP pengguna
            $kd_isps = $user->isp->pluck('kd_isp'); // Mendapatkan kd_isp dari ISP yang dimiliki
            $teknisis = Teknisi::whereIn('kd_isp', $kd_isps)->get(); // Ambil teknisi berdasarkan kd_isp
        }

        // Kirim data teknisi ke view
        return view('pages.teknisi.index', compact('teknisis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $user = Auth::user(); // Ambil pengguna yang sedang login
    //     $cabangsQuery = Cabang::query(); // Buat query untuk cabang

    //     if ($user->hasRole('super-admin')) {
    //         // Jika super-admin, ambil semua cabang
    //         $isps = ISP::all();
    //         $cabangs = $cabangsQuery->get();
    //     } elseif ($user->hasRole('isp')) {
    //         // Jika ISP, ambil cabang yang terkait dengan ISP penggun
    //         $isps = $user->isp; // Ambil ISP yang dimiliki oleh pengguna
    //         $kd_isps = $isps->pluck('kd_isp'); // Mendapatkan kd_isp dari ISP yang dimiliki

    //         // Filter cabang berdasarkan kd_isp
    //         $cabangs = $cabangsQuery->whereIn('kd_isp', $kd_isps)->get();
    //     } else {
    //         // Jika bukan super-admin atau ISP, bisa mengosongkan koleksi atau mengarahkan ke halaman lain
    //         $cabangs = collect(); // Kosongkan koleksi
    //     }

    //     return view('pages.teknisi.create', compact('cabangs', 'isps'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $request->validate([
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:6',
    //         'nm_teknisi' => 'required',
    //         't_lahir' => 'required',
    //         'tgl_lahir' => 'required',
    //         'tgl_aktif' => 'required',
    //         'alamat_teknisi' => 'required',
    //         'no_telp' => 'required|max:13',
    //         'kd_cabang' => 'required|array',
    //         'kd_cabang.*' => 'exists:cabangs,kd_cabang',
    //         'kd_isp' => 'required'
    //     ],
    //     [
    //         'email.required' => 'Email harus diisi.',
    //         'email.email' => 'Email harus berupa email yang valid.',
    //         'email.unique' => 'Email sudah terdaftar.',
    //         'password.required' => 'Password harus diisi.',
    //         'password.min' => 'Password harus minimal 6 karakter.',
    //         'nm_teknisi.required' => 'Nama teknisi harus diisi.',
    //         't_lahir.required' => 'Tempat lahir harus diisi.',
    //         'tgl_lahir.required' => 'Tanggal lahir harus diisi.',
    //         'tgl_aktif.required' => 'Tanggal aktif harus diisi.',
    //         'alamat_teknisi.required' => 'Alamat teknisi harus diisi.',
    //         'no_telp.required' => 'Nomor telepon harus diisi.',
    //         'no_telp.max' => 'Nomor telepon harus 13 karakter.',
    //         'kd_cabang.required' => 'Kode cabang harus diisi.',
    //         'kd_cabang.*.exists' => 'Kode cabang tidak valid.',
    //         'kd_isp.required' => 'Kode ISP harus diisi.'
    //     ]);

    //     $user = new User();
    //     $user->name = $request->nm_teknisi;
    //     $user->email = $request->email;
    //     $user->password = Hash::make($request->password);
    //     $user->kd_role = $request->kd_role;
    //     $user->save();

    //     $user->assignRole('teknisi');

    //     $teknisi = new Teknisi();
    //     $teknisi->nm_teknisi = $request->nm_teknisi;
    //     $teknisi->t_lahir = $request->t_lahir;
    //     $teknisi->tgl_lahir = $request->tgl_lahir;
    //     $teknisi->tgl_aktif = $request->tgl_aktif;
    //     $teknisi->alamat_teknisi = $request->alamat_teknisi;
    //     $teknisi->no_telp = $request->no_telp;
    //     $teknisi->kd_user = $user->id;
    //     $teknisi->kd_isp = $request->kd_isp;
    //     $teknisi->save();

    //     // Attach multiple cabangs to teknisi using the pivot table
    //     $teknisi->cabangs()->attach($request->kd_cabang);


    //     // Redirect or return response
    //     return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil ditambahkan');

    // }

    public function create()
    {
        $user = Auth::user(); // Ambil pengguna yang sedang login
        $cabangsQuery = Cabang::query(); // Buat query untuk cabang
    
        $isps = collect(); // Inisialisasi kosong untuk ISP
        $kd_isp = null; // Initialize kd_isp
    
        if ($user->hasRole('super-admin')) {
            // Jika super-admin, ambil semua cabang dan ISP
            $isps = ISP::all();
            $cabangs = $cabangsQuery->get();
        } elseif ($user->hasRole('isp')) {
            // Jika ISP, ambil cabang yang terkait dengan ISP pengguna
            $isps = $user->isp; // Ambil ISP yang dimiliki oleh pengguna
            $kd_isp = $isps->first()->kd_isp; // Ambil kd_isp dari ISP pengguna
            $cabangs = $cabangsQuery->where('kd_isp', $kd_isp)->get(); // Filter cabang berdasarkan kd_isp
        } else {
            // Jika bukan super-admin atau ISP, kosongkan koleksi
            $cabangs = collect(); 
        }
    
        // Return view with kd_isp, cabangs, and isps
        return view('pages.teknisi.create', compact('cabangs', 'isps', 'kd_isp', 'user'));
    }
    

public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'nm_teknisi' => 'required',
        't_lahir' => 'required',
        'tgl_lahir' => 'required',
        'tgl_aktif' => 'required',
        'alamat_teknisi' => 'required',
        'no_telp' => 'required|max:13',
        'kd_cabang' => 'required|array',
        'kd_cabang.*' => 'exists:cabangs,kd_cabang',
    ], [
        // Custom error messages
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Email harus berupa email yang valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password harus diisi.',
        'password.min' => 'Password harus minimal 6 karakter.',
        'nm_teknisi.required' => 'Nama teknisi harus diisi.',
        't_lahir.required' => 'Tempat lahir harus diisi.',
        'tgl_lahir.required' => 'Tanggal lahir harus diisi.',
        'tgl_aktif.required' => 'Tanggal aktif harus diisi.',
        'alamat_teknisi.required' => 'Alamat teknisi harus diisi.',
        'no_telp.required' => 'Nomor telepon harus diisi.',
        'no_telp.max' => 'Nomor telepon harus 13 karakter.',
        'kd_cabang.required' => 'Kode cabang harus diisi.',
        'kd_cabang.*.exists' => 'Kode cabang tidak valid.',
    ]);

    // Ambil pengguna yang sedang login
    $loggedUser = Auth::user();

    // Jika pengguna memiliki role 'isp', ambil kd_isp dari pengguna
    $kd_isp = $loggedUser->hasRole('isp') ? $loggedUser->isp->first()->kd_isp : $request->kd_isp;

    // Buat user baru
    $user = new User();
    $user->name = $request->nm_teknisi;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->kd_role = 'teknisi';
    $user->save();

    // Assign role teknisi ke user
    $user->assignRole('teknisi');

    // Buat teknisi baru
    $teknisi = new Teknisi();
    $teknisi->nm_teknisi = $request->nm_teknisi;
    $teknisi->t_lahir = $request->t_lahir;
    $teknisi->tgl_lahir = $request->tgl_lahir;
    $teknisi->tgl_aktif = $request->tgl_aktif;
    $teknisi->alamat_teknisi = $request->alamat_teknisi;
    $teknisi->no_telp = $request->no_telp;
    $teknisi->kd_user = $user->id;
    $teknisi->kd_isp = $kd_isp; // Otomatis isi kd_isp jika pengguna ISP
    $teknisi->save();

    // Attach multiple cabangs to teknisi
    $teknisi->cabangs()->attach($request->kd_cabang);

    // Redirect dengan pesan sukses
    return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil ditambahkan');
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $kd_teknisi)
    {
        // Dekripsi kd_teknisi
        $decryptedId = Crypt::decryptString($kd_teknisi);

        // Ambil teknisi berdasarkan kd_teknisi
        $teknisi = Teknisi::where('kd_teknisi', $decryptedId)->first();

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Buat query untuk cabang
        $cabangsQuery = Cabang::query();

        if ($user->hasRole('super-admin')) {
            // Jika super-admin, ambil semua cabang
            $isps = ISP::all();
            $cabangs = $cabangsQuery->get();
        } elseif ($user->hasRole('isp')) {
            // Jika ISP, ambil cabang yang terkait dengan ISP pengguna
            $isps = $user->isp; // Ambil ISP yang dimiliki oleh pengguna
            $kd_isps = $isps->pluck('kd_isp'); // Mendapatkan kd_isp dari ISP yang dimiliki

            // Filter cabang berdasarkan kd_isp
            $cabangs = $cabangsQuery->whereIn('kd_isp', $kd_isps)->get();
        } else {
            // Jika bukan super-admin atau ISP, kosongkan koleksi atau arahkan ke halaman lain
            $cabangs = collect(); // Kosongkan koleksi
        }

        // Mengambil cabang yang terkait dengan teknisi (dipertahankan dari fungsi asli)
        $selectedCabangs = $teknisi->cabangs()->pluck('cabang_teknisis.kd_cabang')->toArray();

        // Return ke view 'edit' dengan data teknisi, cabangs, selectedCabangs, dan isps
        return view('pages.teknisi.edit', compact('teknisi', 'cabangs', 'selectedCabangs', 'isps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $kd_teknisi)
    {
        $decryptedId = Crypt::decryptString($kd_teknisi);
        $teknisi = Teknisi::where('kd_teknisi', $decryptedId)->first();
        $user = User::find($teknisi->kd_user);

        // Validate incoming request
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'nm_teknisi' => 'required',
            't_lahir' => 'required',
            'tgl_lahir' => 'required',
            'tgl_aktif' => 'required',
            'alamat_teknisi' => 'required',
            'no_telp' => 'required|max:13',
            'kd_cabang' => 'required',
            'kd_cabang.*' => 'exists:cabangs,kd_cabang',
            'kd_isp' => 'required',
        ],
        [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus berupa email yang valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password harus minimal 6 karakter.',
            'nm_teknisi.required' => 'Nama teknisi harus diisi.',
            't_lahir.required' => 'Tempat lahir harus diisi.',
            'tgl_lahir.required' => 'Tanggal lahir harus diisi.',
            'tgl_aktif.required' => 'Tanggal aktif harus diisi.',
            'alamat_teknisi.required' => 'Alamat teknisi harus diisi.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.max' => 'Nomor telepon harus 13 karakter.',
            'kd_cabang.required' => 'Kode cabang harus diisi.',
            'kd_cabang.*.exists' => 'Kode cabang tidak valid.',
            'kd_isp.required' => 'Kode ISP harus diisi.',
        ]);

        // Update user data
        $user->name = $request->nm_teknisi;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update teknisi data
        $teknisi->nm_teknisi = $request->nm_teknisi;
        $teknisi->t_lahir = $request->t_lahir;
        $teknisi->tgl_lahir = $request->tgl_lahir;
        $teknisi->tgl_aktif = $request->tgl_aktif;
        $teknisi->alamat_teknisi = $request->alamat_teknisi;
        $teknisi->no_telp = $request->no_telp;
        $teknisi->kd_isp = $request->kd_isp;
        $teknisi->save();

        // Sync cabangs to teknisi - update many-to-many relationship
        $teknisi->cabangs()->sync($request->kd_cabang);

        // Redirect or return response
        return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $decryptedId = Crypt::decryptString($id);
        $teknisi = Teknisi::where('kd_teknisi', $decryptedId)->first();
        $user = User::find($teknisi->kd_user);
        $teknisi->delete();
        $user->delete();
        return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil dihapus');

    }
}
