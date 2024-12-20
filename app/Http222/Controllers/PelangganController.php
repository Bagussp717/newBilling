<?php

namespace App\Http\Controllers;

use App\Models\ISP;
use App\Models\ODP;
use App\Models\User;
use App\Models\Loket;
use App\Models\Paket;
use App\Models\Cabang;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::whereNotNull('username_pppoe')->where('username_pppoe', '!=', '')->get();
        $cabangs = Cabang::all();
        $pakets = Paket::all();

        $user = Auth::user();
        if ($user->hasRole('super-admin')) {
            // For super-admin, show all active pelanggans
            $pelanggans = Pelanggan::whereNotNull('username_pppoe')->where('username_pppoe', '!=', '')->get();
        } elseif ($user->hasRole('isp')) {
            $kd_isps = $user->isp->pluck('kd_isp');
            // For ISP role, filter by ISP and active pelanggans
            $pelanggans = Pelanggan::whereIn('kd_isp', $kd_isps)
                ->whereNotNull('username_pppoe')
                ->where('username_pppoe', '!=', '')
                ->get();
        } elseif ($user->hasRole('teknisi')) {
            $kd_isps = $user->teknisi->pluck('kd_isp');
            // For teknisi role, filter by ISP and active pelanggans
            $pelanggans = Pelanggan::whereIn('kd_isp', $kd_isps)
                ->whereNotNull('username_pppoe')
                ->where('username_pppoe', '!=', '')
                ->get();
        }

        return view('pages.pelanggan.index', compact('pelanggans', 'cabangs', 'pakets'));
    }


    public function show($kd_pelanggan)
    {
        $pelanggan = Pelanggan::where('kd_pelanggan', $kd_pelanggan)->firstOrFail();
        $cabang = Cabang::all();
        $paket = Paket::all();
        $loket = Loket::all();
        return view('pages.pelanggan.show', compact('pelanggan', 'cabang', 'paket', 'loket'));
    }


    public function edit($kd_pelanggan)
    {
        $decryptedId = Crypt::decryptString($kd_pelanggan);
        $pelanggan = Pelanggan::where('kd_pelanggan', $decryptedId)->firstOrFail();
        $cabangs = Cabang::all();
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            $pakets = Paket::all();
            $lokets = Loket::all();
            $odps = ODP::all();
            $isps = ISP::all();
        } else if ($user->hasRole('isp')) {
            $isps = $user->isp;
            $kd_isps = $isps->pluck('kd_isp');
            $pakets = Paket::whereIn('kd_isp', $kd_isps)->get();
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
            $odps = ODP::whereIn('kd_isp', $kd_isps)->get();
        } elseif ($user->hasRole('teknisi')) {
            $isps = $user->teknisi;
            $kd_isps = $isps->pluck('kd_isp'); // Mengambil kode ISP terkait user
            $pakets = Paket::whereIn('kd_isp', $kd_isps)->get();
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
            $odps = ODP::whereIn('kd_isp', $kd_isps)->get();
        }

        return view('pages.pelanggan.edit', compact('pelanggan', 'cabangs', 'pakets', 'lokets', 'odps', 'isps'));
    }

    public function update(Request $request, $kd_pelanggan)
    {
        $decryptedId = Crypt::decryptString($kd_pelanggan);
        $pelanggan = Pelanggan::where('kd_pelanggan', $decryptedId)->first();
        $user = User::find($pelanggan->kd_user);

        $request->validate(
            [
                'tgl_pemasangan' => 'nullable',
                'jenis_identitas' => 'nullable',
                'no_identitas' => 'nullable',
                'nm_pelanggan' => 'required',
                't_lahir' => 'nullable',
                'tgl_lahir' => 'nullable',
                'pekerjaan' => 'nullable',
                'alamat' => 'nullable',
                'no_telp' => 'nullable|max:13',
                'lat' => 'nullable',
                'long' => 'nullable',
                'foto_rumah' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'username_pppoe' => 'nullable',
                'password_pppoe' => 'nullable',
                'service_pppoe' => 'nullable',
                'profile_pppoe' => 'nullable',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'kd_paket' => 'required',
            ],
            [
                'nm_pelanggan.required' => 'Nama harus diisi.',
                'kd_paket.required' => 'Paket harus diisi.',
            ]
        );


        $user->name = $request->nm_pelanggan;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Handling file upload with compression
        if ($request->hasFile('foto_rumah')) {
            // Hapus foto lama jika ada
            if ($pelanggan->foto_rumah) {
                Storage::delete('public/foto_rumah/' . $pelanggan->foto_rumah);
            }

            $image = $request->file('foto_rumah');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Kompresi gambar menggunakan Intervention Image
            $imagePath = storage_path('app/public/foto_rumah/' . $filename);
            $imageIntervention = Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio(); // Menjaga rasio aspek
                $constraint->upsize(); // Mencegah gambar menjadi lebih besar
            });

            // Simpan gambar dengan kualitas yang lebih rendah (misalnya 70%)
            $imageIntervention->save($imagePath, 70);

            // Simpan nama file gambar yang telah di-compress
            $fotoName = 'foto_rumah/' . $filename;
        } else {
            // Tetap gunakan nama foto lama jika tidak ada foto baru yang diupload
            $fotoName = $pelanggan->foto_rumah;
        }

        // Perbarui data pelanggan
        $pelanggan->tgl_pemasangan = $request->tgl_pemasangan;
        $pelanggan->jenis_identitas = $request->jenis_identitas;
        $pelanggan->no_identitas = $request->no_identitas;
        $pelanggan->nm_pelanggan = $request->nm_pelanggan;
        $pelanggan->t_lahir = $request->t_lahir;
        $pelanggan->tgl_lahir = $request->tgl_lahir;
        $pelanggan->pekerjaan = $request->pekerjaan;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->no_telp = $request->no_telp;
        $pelanggan->lat = $request->lat;
        $pelanggan->long = $request->long;
        $pelanggan->foto_rumah = $fotoName;
        $pelanggan->username_pppoe = $request->username_pppoe;
        $pelanggan->password_pppoe = $request->password_pppoe;
        $pelanggan->service_pppoe = $request->service_pppoe;
        $pelanggan->profile_pppoe = $request->profile_pppoe;
        $pelanggan->kd_cabang = $request->kd_cabang;
        $pelanggan->kd_paket = $request->kd_paket;
        $pelanggan->kd_loket = $request->kd_loket;
        $pelanggan->kd_isp = $request->kd_isp;
        $pelanggan->save();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui');
    }
}
