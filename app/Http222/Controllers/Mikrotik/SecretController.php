<?php

namespace App\Http\Controllers\Mikrotik;

use App\Models\ISP;
use App\Models\ODP;
use App\Models\User;
use App\Models\Loket;
use App\Models\Paket;
use App\Models\Cabang;
use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\View\Components\Secret;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class SecretController extends Controller
{
    public function index()
    {
        $uniqueInvoices = Invoice::select('tgl_akhir')
        ->distinct() // Mengambil nilai unik
        ->orderBy('tgl_akhir', 'desc') // Urutkan dari yang terbaru
        ->get();

        $user = Auth::user();
        if($user->hasRole('super-admin')) {
            $pakets = Paket::all();
            $lokets = Loket::all();
            $odps = ODP::all();
        } else if ($user->hasRole('isp') || $user->hasRole('teknisi')) {
            $kd_isps = $user->isp->pluck('kd_isp');
            $pakets = Paket::whereIn('kd_isp', $kd_isps)->get();
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
            $odps = ODP::whereIn('kd_isp', $kd_isps)->get();
        }

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secret = $API->comm('/ppp/secret/print');
            $profile = $API->comm('/ppp/profile/print');
        } else {
            return view('error.mikrotikerror');
        }

        $data = [
            'uniqueInvoices' => $uniqueInvoices,
            'profile' => $profile,
            'secret' => $secret,
        ];

        return view('pages.mikrotik.secret.index', $data);
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $user = Auth::user();
        $cabangs = Cabang::all();

        if($user->hasRole('super-admin')) {
            $pakets = Paket::all();
            $lokets = Loket::all();
            $odps = ODP::all();
            $isps = ISP::all();
        } elseif ($user->hasRole('isp')) {
            $isps = $user->isp;
            $kd_isps = $isps->pluck('kd_isp');// Mengambil kode ISP terkait user
            $pakets = Paket::whereIn('kd_isp', $kd_isps)->get();
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
            $odps = ODP::whereIn('kd_isp', $kd_isps)->get();
        } elseif ($user->hasRole('teknisi')) {
            $isps = $user->teknisi;
            $kd_isps = $isps->pluck('kd_isp');// Mengambil kode ISP terkait user
            $pakets = Paket::whereIn('kd_isp', $kd_isps)->get();
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
            $odps = ODP::whereIn('kd_isp', $kd_isps)->get();
        }

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secret = $API->comm('/ppp/secret/print');
            $profile = $API->comm('/ppp/profile/print');
        } else {
            return view('error.mikrotikerror');
        }

        $selectedCabang = session('kd_cabang');

        $data = [
            'cabangs' => $cabangs,
            'pelanggans' => $pelanggans,
            'pakets' => $pakets,
            'lokets' => $lokets,
            'profile' => $profile,
            'secret' => $secret,
            'selectedCabang' => $selectedCabang,
            'odps' => $odps,
            'isps' => $isps,
        ];

        return view('pages.mikrotik.secret.create', $data);
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'tgl_pemasangan' => 'required',
                'jenis_identitas' => 'required',
                'no_identitas' => 'nullable',
                'nm_pelanggan' => 'required',
                'kd_paket' => 'required',
                'kd_loket' => 'required',
                't_lahir' => 'nullable',
                'tgl_lahir' => 'nullable',
                'pekerjaan' => 'nullable',
                'alamat' => 'nullable',
                'no_telp' => 'required',
                'lat' => 'nullable',
                'long' => 'nullable',
                'foto_rumah' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'username_pppoe' => [
                    'nullable',
                    Rule::unique('pelanggans')->where(function ($query) use ($request) {
                        return $query->where('kd_cabang', $request->input('kd_cabang'));
                    }),
                ],
                'password_pppoe' => 'nullable',
                'service_pppoe' => 'nullable',
                'profile_pppoe' => 'nullable',
                'email' => 'required|email|unique:users',
                'password' => 'nullable|string|min:6',
                'kd_isp' => 'required',
                'kd_odp' => 'nullable',
            ],
            [
                'tgl_pemasangan.required' => 'Tgl pemasangan harus di isi',
                'jenis_identitas.required' => 'Jenis identitas harus di isi',
                'no_identitas.nullable' => 'No identitas harus di isi',
                'nm_pelanggan.required' => 'Nama harus di isi',
                't_lahir.nullable' => 'Tempat lahir harus di isi',
                'tgl_lahir.nullable' => 'Tgl lahir harus di isi',
                'pekerjaan.nullable' => 'Pekerjaan harus di isi',
                'alamat.nullable' => 'Alamat harus di isi',
                'no_telp.required' => 'No telp harus di isi',
                'no_telp.min' => 'Nomor telepon harus 12 karakter.',
                'no_telp.max' => 'Nomor telepon harus 13 karakter.',
                'lat.required' => 'Latitude harus di isi',
                'long.required' => 'Longitude harus di isi',
                'foto_rumah.required' => 'Foto rumah harus di isi',
                'foto_rumah.image' => 'Foto rumah harus berupa gambar',
                'foto_rumah.mimes' => 'Foto rumah harus berupa jpeg,png,jpg,gif,svg',
                'foto_rumah.max' => 'Foto rumah maksimal 2 MB',
                'username_pppoe.nullable' => 'Username pppoe harus di isi',
                'username_pppoe.unique' => 'Nama secret sudah ada di cabang ini',
                'password_pppoe.nullable' => 'Password pppoe harus di isi',
                'service_pppoe.nullable' => 'Service pppoe harus di isi',
                'profile_pppoe.nullable' => 'Profile pppoe harus di isi',
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Email harus berupa email yang valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.nullable' => 'Password harus diisi.',
                'password.min' => 'Password harus minimal 6 karakter.',
                'kd_isp.required' => 'Kode ISP harus diisi.',
                'kd_loket.nullable' => 'Loket harus diisi.',
                'kd_paket.nullable' => 'Paket harus diisi.',
            ]
        );

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $API->comm('/ppp/secret/add', array(
                'name' => $request->input('username_pppoe'),
                'password' => $request->input('password_pppoe'),
                'service' => $request->input('service_pppoe'),
                'profile' => $request->input('profile_pppoe'),
            ));

            $user = new User();
            $user->name = $request->nm_pelanggan;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->kd_role = $request->kd_role;
            $user->username_pppoe = $request->username_pppoe;
            $user->save();

            $user->assignRole('pelanggan');

            if ($request->hasFile('foto_rumah')) {
                $fotoPath = $request->file('foto_rumah')->store('foto_rumah', 'public');
                $fotoName = basename($fotoPath);
            }

            $pelanggan = new Pelanggan();
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
            // $pelanggan->foto_rumah = $fotoName;
            $pelanggan->username_pppoe = $request->username_pppoe;
            $pelanggan->password_pppoe = $request->password_pppoe;
            $pelanggan->service_pppoe = $request->service_pppoe;
            $pelanggan->profile_pppoe = $request->profile_pppoe;
            $pelanggan->kd_cabang = $request->input('kd_cabang');
            $pelanggan->kd_isp = $request->input('kd_isp');
            $pelanggan->kd_paket = $request->kd_paket;
            $pelanggan->kd_loket = $request->kd_loket;
            $pelanggan->kd_odp = $request->kd_odp;
            $pelanggan->kd_user = $user->id;
            $pelanggan->save();

            return redirect()->route('secretMicrotik.index')
                ->with('success', 'Profil telah ditambahkan ke Mikrotik dan database');
        } else {
            return view('error.mikrotikerror');
        }
    }


    public function edit($username_pppoe)
    {
        $pelanggan = Pelanggan::where('username_pppoe', $username_pppoe)->first();
        $cabangs = Cabang::all();
        $user = Auth::user();

        if($user->hasRole('super-admin')) {
            $pakets = Paket::all();
            $lokets = Loket::all();
            $odps = ODP::all();
            $isps = ISP::all(); // Semua ISP untuk super-admin
        } else if ($user->hasRole('isp')) {
            // Jika pengguna adalah ISP atau teknisi, ambil ISP yang terkait
            $isps = $user->isp;
            $kd_isps = $isps->pluck('kd_isp');
            // Ambil paket, loket, dan ODP berdasarkan kode ISP
            $pakets = Paket::whereIn('kd_isp', $kd_isps)->get();
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
            $odps = ODP::whereIn('kd_isp', $kd_isps)->get();
        } elseif ($user->hasRole('teknisi')) {
            $isps = $user->teknisi;
            $kd_isps = $isps->pluck('kd_isp');// Mengambil kode ISP terkait user
            $pakets = Paket::whereIn('kd_isp', $kd_isps)->get();
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
            $odps = ODP::whereIn('kd_isp', $kd_isps)->get();
        }

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if (!$username_pppoe) {
            return redirect()->route('secretMicrotik.index')->with('error', 'Paket tidak ditemukan di database.');
        }

        if ($API->connect($ip, $user, $pass)) {
            $getuser = $API->comm('/ppp/secret/print', [
                '?name' => $username_pppoe,
            ]);

            // Periksa jika secret tidak ditemukan di Mikrotik
            if (empty($getuser)) {
                return redirect()->route('secretMicrotik.index')->with('error', 'Profile not found.');
            }

            $username_pppoe = Pelanggan::where('username_pppoe', $username_pppoe)->first();

            // Periksa jika username_pppoe tidak ditemukan di database
            if (!$username_pppoe) {
                return redirect()->route('secretMicrotik.index')->with('error', 'Paket tidak ditemukan di database.');
            }

            $secret = $API->comm('/ppp/secret/print');
            $profile = $API->comm('/ppp/profile/print');

            $data = [
                'username_pppoe' => $username_pppoe,
                'pelanggan' => $pelanggan,
                'pakets' => $pakets,
                'lokets' => $lokets,
                'cabangs' => $cabangs,
                'secret' => $secret,
                'profile' => $profile,
                'odps' => $odps,
                'isps' => $isps,
            ];

            return view('pages.mikrotik.secret.edit', $data);
        } else {
            return view('error.mikrotikerror');
        }
    }


    public function update(Request $request, $username_pppoe)
    {
        // dd($request->all());
        $pelanggan = Pelanggan::where('username_pppoe', $username_pppoe)->first();

        if (!$pelanggan) {
            return redirect()->route('secretMicrotik.index')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $request->validate([
            // Semua aturan validasi
            'tgl_pemasangan' => 'nullable',
            'jenis_identitas' => 'nullable',
            'no_identitas' => 'nullable',
            'nm_pelanggan' => 'required',
            'kd_paket' => 'required',
            'kd_loket' => 'required',
            't_lahir' => 'nullable',
            'tgl_lahir' => 'nullable',
            'pekerjaan' => 'nullable',
            'alamat' => 'nullable',
            'no_telp' => 'nullable|min:12|max:13',
            'lat' => 'nullable',
            'long' => 'nullable',
            'foto_rumah' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'username_pppoe' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request, $pelanggan) {
                    if ($pelanggan->username_pppoe !== $value) {
                        $exists = Pelanggan::where('username_pppoe', $value)
                            ->where('kd_cabang', $request->input('kd_cabang'))
                            ->exists();

                        if ($exists) {
                            $fail('Username PPPoE sudah ada di cabang ini');
                        }
                    }
                },
            ],
            'password_pppoe' => 'nullable',
            'service_pppoe' => 'nullable',
            'profile_pppoe' => 'nullable',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore(optional($pelanggan->user)->id),
            ],
            'password' => 'nullable|min:6',
        ], [
            'username_pppoe.unique' => 'Username PPPoE sudah ada di cabang ini',
            'nm_pelanggan.required' => 'Nama harus diisi',
            'kd_paket.required' => 'Paket harus diisi',
            'kd_loket.required' => 'Loket harus diisi',
        ]);

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secret = $API->comm('/ppp/secret/print', [
                '?name' => $username_pppoe,
            ]);

            if (empty($secret)) {
                return redirect()->route('secretMicrotik.index')->with('error', 'Secret tidak ada di dalam mikrotik');
            }

            $API->comm('/ppp/secret/set', [
                '.id' => $secret[0]['.id'],
                'name' => $request->input('username_pppoe'),
                'password' => $request->input('password_pppoe'),
                'service' => $request->input('service_pppoe'),
                'profile' => $request->input('profile_pppoe'),
            ]);

            // Cek apakah user sudah ada berdasarkan kd_user
            $userupdate = User::find($pelanggan->kd_user);

            if ($userupdate) {
                // Update user yang sudah ada
                $userupdate->name = $request->nm_pelanggan;
                $userupdate->email = $request->email;
                if ($request->password) {
                    $userupdate->password = Hash::make($request->password);
                }
                $userupdate->save();
            } else {
                // Jika tidak ada, tambahkan user baru
                $userupdate = User::create([
                    'name' => $request->nm_pelanggan,
                    'email' => $request->email,
                    'password' => $request->password ? Hash::make($request->password) : Hash::make('defaultpassword'),
                ]);
                // Simpan ID user baru ke pelanggan
                $pelanggan->kd_user = $userupdate->id;
                $pelanggan->save();
            }

            // Menyimpan gambar ke storage
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

            // Update data pelanggan
            $pelanggan->update([
                'tgl_pemasangan' => $request->tgl_pemasangan,
                'jenis_identitas' => $request->jenis_identitas,
                'no_identitas' => $request->no_identitas,
                'nm_pelanggan' => $request->nm_pelanggan,
                't_lahir' => $request->t_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'lat' => $request->lat,
                'long' => $request->long,
                'foto_rumah' => $pelanggan->foto_rumah = $fotoName,
                'kd_cabang' => $request->kd_cabang,
                'kd_isp' => $request->kd_isp,
                'kd_paket' => $request->kd_paket,
                'kd_loket' => $request->kd_loket,
                'kd_odp' => $request->kd_odp,
                'username_pppoe' => $request->username_pppoe,
                'password_pppoe' => $request->password_pppoe,
                'service_pppoe' => $request->service_pppoe,
                'profile_pppoe' => $request->profile_pppoe,
            ]);

            return redirect()->route('secretMicrotik.index')->with('success', 'Secret telah diperbarui di Mikrotik dan di database.');
        } else {
            return view('error.mikrotikerror');
        }
    }


    public function destroy($username_pppoe)
    {
        // dd($username_pppoe);
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            // Ambil data paket dari Mikrotik untuk memastikan paket ada
            $secret = $API->comm('/ppp/secret/print', [
                '?name' => $username_pppoe,
            ]);

            if (!empty($secret)) {
                // Ambil ID profil dari hasil pencarian
                $secretId = $secret[0]['.id'];

                // Hapus data dari Mikrotik menggunakan ID
                $API->comm('/ppp/secret/remove', [
                    '.id' => $secretId,
                ]);

                // Mengupdate kolom pelanggan menjadi null setelah penghapusan
                Pelanggan::where('username_pppoe', $username_pppoe)->update([
                    'username_pppoe' => null,
                    'password_pppoe' => null,
                    'service_pppoe' => null,
                    'profile_pppoe' => null,
                ]);

                    return redirect()->route('secretMicrotik.index')->with('success', 'Profil mikrotik di hapus di mikrotik.');
                }
            } else {
            // Jika koneksi ke API Mikrotik gagal
            return view('error.mikrotikerror');
        }
    }

    public function destroy1($username_pppoe)
    {
        // dd($username_pppoe);
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            // Ambil data paket dari Mikrotik untuk memastikan paket ada
            $secret = $API->comm('/ppp/secret/print', [
                '?name' => $username_pppoe,
            ]);

            if (!empty($secret)) {
                // Ambil ID profil dari hasil pencarian
                $secretId = $secret[0]['.id'];

                // Hapus data dari Mikrotik menggunakan ID
                $result = $API->comm('/ppp/secret/remove', [
                    '.id' => $secretId,
                ]);

                // Periksa hasil respon dari Mikrotik
                if (isset($result['!trap']) || isset($result['!re'])) {
                    // Jika ada trap atau error dalam respon, penghapusan gagal
                    return redirect()->route('secretMicrotik.index')->with('error', 'Gagal menghapus profil di Mikrotik.');
                }

                // Temukan entri di database yang memiliki username_pppoe yang sama
                $pelanggan = Pelanggan::where('username_pppoe', $username_pppoe)->first();

                if ($pelanggan) {
                    // Jika pelanggan memiliki user terkait, hapus user tersebut
                    if ($pelanggan->kd_user) {
                        $pelanggan->user->delete();
                    }

                    // Hapus entri pelanggan dari database
                    $pelanggan->delete();

                    return redirect()->route('secretMicrotik.index')->with('success', 'Profil di Mikrotik dan data pelanggan di database telah dihapus.');
                } else {
                    // Jika pelanggan tidak ditemukan di database
                    return redirect()->route('secretMicrotik.index')->with('error', 'Profil berhasil dihapus dari Mikrotik, tetapi tidak ditemukan di database.');
                }
            } else {
                // Jika profil tidak ditemukan di Mikrotik
                return redirect()->route('secretMicrotik.index')->with('error', 'Profil tidak ditemukan di Mikrotik.');
            }
        } else {
            // Jika koneksi ke API Mikrotik gagal
            return view('error.mikrotikerror');
        }
    }


    // public function sync()
    // {
    //     $ip = session()->get('ip');
    //     $user = session()->get('user');
    //     $pass = session()->get('pass');
    //     $kd_cabang = session()->get('kd_cabang');
    //     $API = new RouterosAPI();
    //     $API->debug(false);

    //     if ($API->connect($ip, $user, $pass)) {
    //         $secrets = $API->comm('/ppp/secret/print');

    //         // Ambil ID terakhir dari tabel users
    //         $lastUserId = User::max('id');
    //         $currentUserId = $lastUserId ? $lastUserId + 1 : 1;

    //          // Ambil kd_isp dari tabel Cabang berdasarkan kd_cabang
    //         $cabang = Cabang::where('kd_cabang', $kd_cabang)->first();
    //         $kd_isp = $cabang ? $cabang->kd_isp : null;

    //         // Chunking data into smaller batches
    //         $chunkSize = 100; // Tentukan ukuran chunk, misal 100
    //         $secretChunks = array_chunk($secrets, $chunkSize);

    //         foreach ($secretChunks as $chunk) {
    //             // Optimasi insert untuk tabel Pelanggan
    //             $newPelanggan = [];
    //             $newUsers = [];
    //             $newRoles = [];

    //             foreach ($chunk as $secret) {
    //                 $existingsecret = Pelanggan::where('username_pppoe', $secret['name'])
    //                     ->where('kd_cabang', $kd_cabang)
    //                     ->first();

    //                 if (! $existingsecret) {
    //                     $newPelanggan[] = [
    //                         'username_pppoe' => $secret['name'],
    //                         'password_pppoe' => $secret['password'] ?? null,
    //                         'service_pppoe' => $secret['service'] ?? null,
    //                         'profile_pppoe' => $secret['profile'] ?? null,
    //                         'kd_user' => $currentUserId,
    //                         'kd_cabang' => $kd_cabang,
    //                         'kd_isp' => $kd_isp,
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ];

    //                     // Increment user ID for the next pelanggan
    //                     $currentUserId++;
    //                 }

    //                 // Cek apakah pengguna sudah ada di tabel users
    //                 $existingUser = User::where('username_pppoe', $secret['name'])->first();

    //                 if (! $existingUser) {
    //                     $newUserId = $currentUserId;
    //                     $newUsers[] = [
    //                         'username_pppoe' => $secret['name'],
    //                         'name' => 'Semestamultitekno',
    //                         'email' => $secret['name'] . '@semesta.co.id',
    //                         'password' => bcrypt('12341234'),
    //                         'kd_role' => 'pelanggan',
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ];

    //                     $newRoles[] = [
    //                         'role_id' => 5, // Role ID pelanggan
    //                         'model_type' => 'App\Models\User',
    //                         'model_id' => $newUserId,
    //                     ];
    //                 }
    //             }

    //             // Batch insert untuk Pelanggan
    //             if (!empty($newPelanggan)) {
    //                 Pelanggan::insert($newPelanggan);
    //             }

    //             // Batch insert untuk Users
    //             if (!empty($newUsers)) {
    //                 User::insert($newUsers);
    //             }

    //             // Batch insert untuk Roles
    //             if (!empty($newRoles)) {
    //                 DB::table('model_has_roles')->insert($newRoles);
    //             }
    //         }

    //         return redirect()->route('secretMicrotik.index')->with('success', 'Data dari Mikrotik telah disinkronkan ke database.');
    //     } else {
    //         return view('error.mikrotikerror');
    //     }
    // }

    public function sync()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $kd_cabang = session()->get('kd_cabang');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secrets = $API->comm('/ppp/secret/print');

            // Ambil kd_isp dari tabel Cabang berdasarkan kd_cabang
            $cabang = Cabang::where('kd_cabang', $kd_cabang)->first();
            $kd_isp = $cabang ? $cabang->kd_isp : null;

            // Chunking data into smaller batches
            $chunkSize = 100; // Tentukan ukuran chunk, misal 100
            $secretChunks = array_chunk($secrets, $chunkSize);

            foreach ($secretChunks as $chunk) {
                // Optimasi insert untuk tabel Pelanggan dan Users
                $newPelanggan = [];
                $newUsers = [];
                $newRoles = [];

                foreach ($chunk as $secret) {
                    // Cek apakah pelanggan sudah ada di tabel Pelanggan
                    $existingsecret = Pelanggan::where('username_pppoe', $secret['name'])
                        ->where('kd_cabang', $kd_cabang)
                        ->first();

                    if (! $existingsecret) {
                        // Cek apakah pengguna sudah ada di tabel users
                        $existingUser = User::where('username_pppoe', $secret['name'])->first();

                        if (! $existingUser) {
                            // Insert user baru ke tabel Users
                            $newUserId = User::insertGetId([
                                'username_pppoe' => $secret['name'],
                                'name' => 'Semestamultitekno',
                                'email' => $secret['name'] . '@semesta.co.id',
                                'password' => bcrypt('12341234'),
                                'kd_role' => 'pelanggan',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            // Insert role baru ke tabel model_has_roles
                            $newRoles[] = [
                                'role_id' => 5, // Role ID pelanggan
                                'model_type' => 'App\Models\User',
                                'model_id' => $newUserId,
                            ];
                        } else {
                            // Jika user sudah ada, ambil id user yang sudah ada
                            $newUserId = $existingUser->id;
                        }

                        // Masukkan data pelanggan baru dengan kd_user yang sesuai dengan id user
                        $newPelanggan[] = [
                            'username_pppoe' => $secret['name'],
                            'password_pppoe' => $secret['password'] ?? null,
                            'service_pppoe' => $secret['service'] ?? null,
                            'profile_pppoe' => $secret['profile'] ?? null,
                            'kd_user' => $newUserId, // Menggunakan ID user yang baru dibuat
                            'kd_cabang' => $kd_cabang,
                            'kd_isp' => $kd_isp,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Batch insert untuk Pelanggan
                if (!empty($newPelanggan)) {
                    Pelanggan::insert($newPelanggan);
                }

                // Batch insert untuk Roles
                if (!empty($newRoles)) {
                    DB::table('model_has_roles')->insert($newRoles);
                }
            }

            return redirect()->route('secretMicrotik.index')->with('success', 'Data dari Mikrotik telah disinkronkan ke database.');
        } else {
            return view('error.mikrotikerror');
        }
    }


    public function isolir(Request $request)
    {
        // Validasi input
        $request->validate([
            'tgl_akhir' => 'required',
        ], [
            'tgl_akhir.required' => 'Harap pilih tanggal akhir.',
        ]);

        $tglAkhir = $request->input('tgl_akhir');

        // Ambil data login Mikrotik dari session
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');

        $API = new RouterosAPI();
        $API->debug(false);

        // Ambil pelanggan yang memiliki invoice dengan tgl_akhir sesuai dan belum membayar
        $pelangganBelumBayar = Pelanggan::whereHas('invoice', function ($query) use ($tglAkhir) {
            // Filter invoice hanya untuk tgl_akhir yang sama dengan tanggal yang dipilih
            $query->where('tgl_akhir', '=', $tglAkhir)
                ->whereDoesntHave('pembayaran', function($pembayaranQuery) {
                    // Filter pembayaran untuk mencari yang belum dibayar
                    $pembayaranQuery->where('jml_bayar', '>', 0);
                });
        })->get();

        // Jika tidak ada pelanggan yang ditemukan
        if ($pelangganBelumBayar->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pelanggan yang belum membayar pada tanggal yang dipilih.');
        }

        if ($API->connect($ip, $user, $pass)) {
            // Ambil semua profil PPPoE dari Mikrotik
            $profiles = $API->comm('/ppp/profile/print');

            // Temukan ID profil 'isolir'
            $isolirProfileId = null;
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

            // Ambil semua pelanggan PPPoE dari Mikrotik
            $secrets = $API->comm('/ppp/secret/print');

            // Inisialisasi array untuk menyimpan nama dan ID secret
            $secretMap = [];

            // Loop untuk menambahkan setiap nama dan ID ke array
            foreach ($secrets as $secret) {
                $secretMap[$secret['name']] = $secret['.id'];
            }

            // Loop melalui pelanggan yang belum membayar dan ubah profil ke 'isolir'
            foreach ($pelangganBelumBayar as $pelanggan) {
                // Cek apakah username_pppoe pelanggan ada dalam secretMap
                if (isset($secretMap[$pelanggan->username_pppoe])) {
                    Log::info("Match found for Pelanggan {$pelanggan->kd_pelanggan}!");

                    // Ambil ID secret dari array
                    $secretId = $secretMap[$pelanggan->username_pppoe];

                    // Ubah profil pelanggan di Mikrotik menjadi 'isolir'
                    $API->comm('/ppp/secret/set', [
                        '.id' => $secretId,
                        'profile' => $isolirProfileId
                    ]);

                    // Perbarui kolom status_pppoe di tabel invoice berdasarkan tgl_akhir
                    Invoice::where('kd_pelanggan', $pelanggan->kd_pelanggan)
                        ->where('tgl_akhir', $tglAkhir)
                        ->update(['status_pppoe' => 'isolir']);
                }
            }

            $API->disconnect();

            return redirect()->route('secretMicrotik.index')->with('success', 'Isolir berhasil dijalankan untuk pelanggan yang belum membayar pada tanggal akhir invoice yang dipilih.');
        } else {
            return view('error.mikrotikerror');
        }
    }

    public function recover(Request $request)
    {
        // Validasi input
        $request->validate([
            'tgl_akhir' => 'required',
        ], [
            'tgl_akhir.required' => 'Harap pilih tanggal akhir.',
        ]);

        $tglAkhir = $request->input('tgl_akhir');

        // Ambil data login Mikrotik dari session
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');

        $API = new RouterosAPI();
        $API->debug(false);

        // Ambil pelanggan yang memiliki invoice dengan tgl_akhir sesuai dan status isolir
        $pelangganIsolir = Pelanggan::whereHas('invoice', function ($query) use ($tglAkhir) {
            // Filter invoice hanya untuk tgl_akhir yang sama dan status isolir
            $query->where('tgl_akhir', '=', $tglAkhir)
                ->where('status_pppoe', 'isolir'); // Status yang menandakan pelanggan diisolir
        })->get();

        // Jika tidak ada pelanggan yang ditemukan
        if ($pelangganIsolir->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pelanggan yang diisolir pada tanggal yang dipilih.');
        }

        if ($API->connect($ip, $user, $pass)) {
            // Ambil semua profil PPPoE dari Mikrotik
            $profiles = $API->comm('/ppp/profile/print');

            // Ambil semua pelanggan PPPoE dari Mikrotik
            $secrets = $API->comm('/ppp/secret/print');

            // Inisialisasi array untuk menyimpan nama dan ID secret
            $secretMap = [];

            // Loop untuk menambahkan setiap nama dan ID ke array
            foreach ($secrets as $secret) {
                $secretMap[$secret['name']] = $secret['.id'];
            }

            // Loop melalui pelanggan yang diisolir dan ubah profil mereka kembali ke profile_pppoe
            foreach ($pelangganIsolir as $pelanggan) {
                // Cek apakah username_pppoe pelanggan ada dalam secretMap
                if (isset($secretMap[$pelanggan->username_pppoe])) {
                    Log::info("Match found for Pelanggan {$pelanggan->kd_pelanggan}! Pulihkan profil.");

                    // Ambil ID secret dari array
                    $secretId = $secretMap[$pelanggan->username_pppoe];

                    // Ambil profil default dari kolom profile_pppoe di tabel pelanggan
                    $defaultProfile = $pelanggan->profile_pppoe;

                    // Ubah profil pelanggan di Mikrotik menjadi profil default dari pelanggan
                    $API->comm('/ppp/secret/set', [
                        '.id' => $secretId,
                        'profile' => $defaultProfile // Gunakan profile_pppoe dari tabel pelanggan
                    ]);

                    // Perbarui kolom status_pppoe di tabel invoice berdasarkan tgl_akhir
                    Invoice::where('kd_pelanggan', $pelanggan->kd_pelanggan)
                        ->where('tgl_akhir', $tglAkhir)
                        ->update(['status_pppoe' => $defaultProfile]); // Kembalikan ke profile_pppoe pelanggan
                }
            }

            $API->disconnect();

            return redirect()->route('secretMicrotik.index')->with('success', 'Pemulihan berhasil dijalankan untuk pelanggan yang diisolir pada tanggal akhir invoice yang dipilih.');
        } else {
            return view('error.mikrotikerror');
        }
    }
}
