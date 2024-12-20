<?php

namespace App\Http\Controllers;
use App\Models\ISP;
use App\Models\ODP;
use App\Models\Cabang;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ODPController extends Controller
{

    public function index()
    {
        // Ambil user yang sedang terautentikasi
        $user = Auth::user();

        // Cek apakah user adalah super-admin
        if ($user->kd_role === 'super-admin') {
            // Ambil semua ODP untuk super-admin
            $odps = ODP::with('cabang', 'isp')->orderBy('created_at', 'desc')->get();
        } elseif ($user->kd_role === 'teknisi') {
            // Ambil Teknisi terkait dengan user yang sedang terautentikasi
            $teknisi = Teknisi::where('kd_user', $user->id)->first();

            if ($teknisi && $teknisi->isp) {
                // Ambil ODP yang terkait dengan ISP dari Teknisi
                $odps = ODP::with('cabang', 'isp')
                    ->where('kd_isp', $teknisi->isp->kd_isp) // Filter berdasarkan kd_isp dari ISP
                    ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal dibuat
                    ->get();
            } else {
                $odps = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
            }
        } else {
            // Ambil ISP terkait dengan user yang sedang terautentikasi
            $isp = ISP::where('kd_user', $user->id)->first();

            if ($isp) {
                // Ambil ODP yang terkait dengan ISP dari user yang sedang terautentikasi
                $odps = ODP::with('cabang', 'isp')
                    ->where('kd_isp', $isp->kd_isp) // Filter berdasarkan kd_isp dari ISP
                    ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal dibuat
                    ->get();
            } else {
                $odps = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
            }
        }
        if (request()->wantsJson()) {
            return response()->json([
                'odp' => $odps
            ]);
        }

        return view('pages.odp.index', compact('odps'));
    }

    public function create()
    {
        $user = Auth::user(); // Ambil user yang sedang terautentikasi
        
        // Query untuk cabang
        $cabangsQuery = Cabang::query();

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
                $cabangs = $cabangsQuery->whereIn('kd_isp', $kd_isps)->get();
            } else {
                // Jika tidak ada teknisi/ISP, cabang dikosongkan dan ISP dikosongkan
                $cabangs = collect();
                $isps = collect(); // Kosongkan ISP juga jika tidak ada teknisi/ISP terkait
            }
        } else {
            // Jika bukan teknisi atau ISP, ambil semua cabang dan ISP
            $cabangs = $cabangsQuery->get();
            $isps = ISP::all(); // Ambil semua ISP
        }

        if (request()->wantsJson()) {
            return response()->json([
                'cabangs' => $cabangs,
                'isps' => $isps,

            ]);
        }

        // Mengembalikan view dengan data cabang dan ISP
        return view('pages.odp.create', compact('cabangs', 'isps'));
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'kd_cabang' => 'required',
                'kd_isp' => 'required', 
                'nm_odp' => 'nullable|unique:odps,nm_odp',
                'foto_odp' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
                'lat' => 'required|numeric',
                'long' => 'required|numeric',
            ],
            [
                'kd_cabang.required' => 'Harus Memilih Nama Cabang',
                'kd_isp.required' => 'Harus Memilih ISP', 
                'foto_odp.required' => 'Foto ODP harus diisi',
                'nm_odp.unique' => 'Nama ODP sudah ada'
            ]
        );

        if ($request->hasFile('foto_odp')) {
            $fotoPath = $request->file('foto_odp')->store('foto_odp', 'public');
            $fotoName = basename($fotoPath);
        }

        $odp = new ODP();
        $odp->kd_cabang = $request->kd_cabang;
        $odp->kd_isp = $request->kd_isp; 
        $odp->nm_odp = $request->nm_odp;
        $odp->lat = $request->lat;
        $odp->long = $request->long;
        $odp->foto_odp = $fotoName;
        $odp->save();

        return redirect()->route('odp.index')->with('success', 'Data ODP berhasil ditambahkan.');
    }

    public function edit($kd_odp)
    {
        $decryptedId = Crypt::decryptString($kd_odp);
        $odp = ODP::findOrFail($decryptedId);
        $cabangs = Cabang::all(); // Ambil semua cabang dari database
        $isps = ISP::all(); // Ambil semua ISP dari database (pastikan Anda memiliki model ISP)

        if (request()->wantsJson()) {
            return response()->json([
                'odp' => $odp,
                'cabangs' => $cabangs,
                'isps' => $isps,
            ]);
        }

        return view('pages.odp.edit', compact('odp', 'cabangs', 'isps'));
    }


    public function update(Request $request, $kd_odp)
    {
        $request->validate(
            [
                'kd_cabang' => 'required',
                'kd_isp' => 'required', 
                'nm_odp' => 'nullable',
                'foto_odp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
                'lat' => 'required|numeric',
                'long' => 'required|numeric',
            ],
            [
                'kd_cabang.required' => 'Harus Memilih Nama Cabang',
                'kd_isp.required' => 'Harus Memilih ISP', 
                'foto_odp.image' => 'Foto ODP harus berupa gambar.',
                'nm_odp.unique' => 'Nama ODP sudah ada'
            ]
        );

        $decryptedId = Crypt::decryptString($kd_odp);
        $odp = ODP::findOrFail($decryptedId);

        if ($request->hasFile('foto_odp')) {
            if ($odp->foto_odp && file_exists(storage_path('app/public/foto_odp/' . $odp->foto_odp))) {
                // Hapus foto lama jika ada
                unlink(storage_path('app/public/foto_odp/' . $odp->foto_odp));
            }

            $fotoPath = $request->file('foto_odp')->store('foto_odp', 'public');
            $odp->foto_odp = basename($fotoPath);
        }

        // Update data ODP
        $odp->kd_cabang = $request->kd_cabang;
        $odp->kd_isp = $request->kd_isp; // Menyimpan kd_isp
        $odp->nm_odp = $request->nm_odp;
        $odp->lat = $request->lat;
        $odp->long = $request->long;
        $odp->save(); // Simpan perubahan ke dalam database

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('odp.index')->with('success', 'Data ODP berhasil diperbarui.');
    }


    public function destroy($kd_odp)
    {

        $decryptedId = Crypt::decryptString($kd_odp);
        $odp = ODP::findOrFail($decryptedId);

        if ($odp->foto_odp) {
            $filePath = public_path('uploads/foto_odp/' . $odp->foto_odp);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $odp->delete();

        return redirect()->route('odp.index')->with('success', 'Data ODP berhasil dihapus.');
    }
}
