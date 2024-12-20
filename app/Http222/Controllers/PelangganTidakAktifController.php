<?php

namespace App\Http\Controllers;

use App\Models\ISP;
use App\Models\ODP;
use App\Models\User;
use App\Models\Loket;
use App\Models\Paket;
use App\Models\Cabang;
use App\Models\Pelanggan;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PelangganTidakAktifController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::whereNull('username_pppoe')->orWhere('username_pppoe', '')->get();
        $cabangs = Cabang::all();
        $pakets = Paket::all();

        $user = Auth::user();
        if ($user->hasRole('super-admin')) {
            // For super-admin, show all inactive pelanggans
            $pelanggans = Pelanggan::whereNull('username_pppoe')->orWhere('username_pppoe', '')->get();
        } elseif ($user->hasRole('isp')) {
            $kd_isps = $user->isp->pluck('kd_isp');
            // For ISP role, filter by ISP and inactive pelanggans
            $pelanggans = Pelanggan::whereIn('kd_isp', $kd_isps)
                ->where(function ($query) {
                    $query->whereNull('username_pppoe')
                        ->orWhere('username_pppoe', '');
                })
                ->get();
        }

        return view('pages.pelanggantidakaktif.index', compact('pelanggans', 'cabangs', 'pakets'));
    }

    public function show(string $kd_pelanggan)
    {
        $pelanggan = Pelanggan::where('kd_pelanggan', $kd_pelanggan)->firstOrFail();
        $cabang = Cabang::all();
        $paket = Paket::all();
        $loket = Loket::all();
        return view('pages.pelanggantidakaktif.show', compact('pelanggan', 'cabang', 'paket', 'loket'));
    }

    public function edit(string $kd_pelanggan)
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

        return view('pages.pelanggantidakaktif.edit', compact('pelanggan', 'cabangs', 'pakets', 'lokets', 'odps', 'isps'));
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

        return redirect()->route('pelangganTidakAktif.index')->with('success', 'Pelanggan berhasil diperbarui');
    }


    public function destroy(string $kd_pelanggan)
    {
        $decryptedId = Crypt::decryptString($kd_pelanggan);
        $pelanggan = Pelanggan::find($decryptedId);
        $pelanggan->delete();
        return redirect()->route('pelangganTidakAktif.index')->with('success', 'Pelanggan berhasil dihapus');
    }

    public function editScreet(string $kd_pelanggan)
    {
        $decryptedId = Crypt::decryptString($kd_pelanggan);
        $pelanggan = Pelanggan::find($decryptedId);

        // ambil data mikrotik
        $ip = $pelanggan->cabang->ip_mikrotik;
        $user = $pelanggan->cabang->username_mikrotik;
        $pass = $pelanggan->cabang->password_mikrotik;

        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secret = $API->comm('/ppp/secret/print');
            $profile = $API->comm('/ppp/profile/print');
        } else {
            return view('error.mikrotikerror');
        }

        $data = [
            'pelanggan' => $pelanggan,
            'secret' => $secret,
            'profile' => $profile
        ];

        return view('pages.pelanggantidakaktif.updatescreet', $data);
    }

    public function updateScreet(Request $request, string $kd_pelanggan)
    {
        // dd($request->all());
        $decryptedId = Crypt::decryptString($kd_pelanggan);
        $pelanggan = Pelanggan::find($decryptedId);

        // ambil data mikrotik
        $ip = $pelanggan->cabang->ip_mikrotik;
        $user = $pelanggan->cabang->username_mikrotik;
        $pass = $pelanggan->cabang->password_mikrotik;

        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secrets = $API->comm('/ppp/secret/print');

            foreach ($secrets as $secret) {
                if ($secret['name'] === $request->input('username_pppoe')) {
                    $API->disconnect();

                    // Kembalikan error jika username sudah ada
                    return redirect()->back()->withErrors([
                        'username_pppoe' => 'Username sudah ada di Mikrotik, gunakan username lain.'
                    ]);
                }
            }
            $API->comm('/ppp/secret/add', array(
                'name' => $request->input('username_pppoe'),
                'password' => $request->input('password_pppoe'),
                'service' => $request->input('service_pppoe'),
                'profile' => $request->input('profile_pppoe'),
            ));

        } else {
            return view('error.mikrotikerror');
        }

        $pelanggan->username_pppoe = $request->username_pppoe;
        $pelanggan->password_pppoe = $request->password_pppoe;
        $pelanggan->service_pppoe = $request->service_pppoe;
        $pelanggan->profile_pppoe = $request->profile_pppoe;
        $pelanggan->save();

        return redirect()->route('pelangganTidakAktif.index')->with('success', 'Screet berhasil diperbarui');
    }
}
