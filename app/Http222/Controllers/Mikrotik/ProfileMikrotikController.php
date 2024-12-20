<?php

namespace App\Http\Controllers\Mikrotik;

use App\Models\ISP;
use App\Models\Paket;
use App\Models\Cabang;
use App\Models\RouterosAPI;
use Termwind\Components\Dd;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ProfileMikrotikController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::all();
        $isps = ISP::all();
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $profile = $API->comm('/ppp/profile/print');
            $ippool = $API->comm('/ip/pool/print');
        } else {
            return view('error.mikrotikerror');
        }

        $data = [
            'cabangs' => $cabangs,
            'profile' => $profile,
            'ippool' => $ippool,
            'isps' => $isps,
        ];

        return view('pages.mikrotik.profile.index', $data);
    }

    public function create()
    {
        $cabangs = Cabang::all();
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $profile = $API->comm('/ppp/profile/print');
            $ippool = $API->comm('/ip/pool/print');
        } else {
            return view('error.mikrotikerror');
        }

        $data = [
            'cabangs' => $cabangs,
            'profile' => $profile,
            'ippool' => $ippool,
        ];

        // Return the view with the data
        return view('pages.mikrotik.profile.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nm_paket' => [
                'required',
                Rule::unique('pakets')->where(function ($query) use ($request) {
                    return $query->where('kd_cabang', $request->input('kd_cabang'));
                }),
            ],
            'hrg_paket' => 'required',
            'local_address' => 'nullable|string',
            'remote_address' => 'nullable|string',
            'rate_limit' => 'nullable|string',
            'keterangan' => 'required|in:default,uji coba', // Tambahkan validasi untuk keterangan
            'kd_isp' => 'required',
        ], [
            'nm_paket.required' => 'Nama paket harus diisi',
            'nm_paket.unique' => 'Nama paket sudah ada di cabang ini',
            'hrg_paket.required' => 'Harga paket harus diisi',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.in' => 'Keterangan tidak valid',
            'kd_isp.required' => 'ISP harus diisi',
        ]);

        // Ambil data session
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');

        // Koneksi ke Mikrotik
        $API = new RouterosAPI();
        $API->debug(false);

        // Cek koneksi
        if ($API->connect($ip, $user, $pass)) {
            // Tambahkan profil ke Mikrotik
            $API->comm('/ppp/profile/add', [
                'name' => $request->input('nm_paket'),
                'rate-limit' => $request->input('rate_limit'),
                'remote-address' => $request->input('remote_address'),
                'local-address' => $request->input('local_address'),
            ]);

            // Simpan data ke database
            $paket = new Paket();
            $paket->nm_paket = $validated['nm_paket'];
            $paket->hrg_paket = $validated['hrg_paket'];
            $paket->local_address = $request->input('local_address');
            $paket->remote_address = $request->input('remote_address');
            $paket->rate_limit = $request->input('rate_limit');
            $paket->kd_cabang = $request->input('kd_cabang');
            $paket->keterangan = $request->input('keterangan');
            $paket->kd_isp =  $request->input('kd_isp'); // Menyimpan keterangan
            $paket->save();

            return redirect()->route('profilemikrotik.index')
                ->with('success', 'Profil telah ditambahkan ke Mikrotik dan database');
        } else {
            return view('error.mikrotikerror');
        }
    }





    public function edit($nm_paket)
    {
        $cabangs = Cabang::all();
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if (!$nm_paket) {
            return redirect()->route('profilemikrotik.index')->with('error', 'Paket tidak ditemukan di database.');
        }

        if ($API->connect($ip, $user, $pass)) {
            $getuser = $API->comm('/ppp/profile/print', [
                '?name' => $nm_paket,
            ]);

            // Periksa jika profil tidak ditemukan di Mikrotik
            if (empty($getuser)) {
                return redirect()->route('profilemikrotik.index')->with('error', 'Profile tidak ditemukan di Mikrotik.');
            }

            $paket = Paket::where('nm_paket', $nm_paket)->first();

            // Periksa jika paket tidak ditemukan di database
            if (!$paket) {
                return redirect()->route('profilemikrotik.index')->with('error', 'Paket tidak ditemukan di database.');
            }

            $ippool = $API->comm('/ip/pool/print');
            $profile = $API->comm('/ppp/profile/print');

            $data = [
                'paket' => $paket,
                'cabangs' => $cabangs,
                'ippool' => $ippool,
                'profile' => $profile,
            ];

            return view('pages.mikrotik.profile.edit', $data);
        } else {
            return view('error.mikrotikerror');
        }
    }

    public function update(Request $request, $nm_paket)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'nm_paket' => 'required',
            'hrg_paket' => 'required',
            'kd_cabang' => 'required',
            'local_address' => 'nullable|string',
            'remote_address' => 'nullable|string',
            'rate_limit' => 'nullable|string',
        ], [
            'nm_paket.required' => 'Nama paket harus diisi',
            'nm_paket.unique' => 'Nama paket sudah ada',
            'hrg_paket.required' => 'Harga paket harus diisi',
            'kd_cabang.required' => 'Cabang harus diisi',
        ]);

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $profile = $API->comm('/ppp/profile/print', [
                '?name' => $nm_paket,
            ]);

            if (empty($profile)) {
                return redirect()->route('dashboard.profile')->with('error', 'Profile tidak ditemukan di Mikrotik.');
            }

            $API->comm('/ppp/profile/set', [
                '.id' => $profile[0]['.id'],
                'name' => $request->input('nm_paket'),
                'rate-limit' => $request->input('rate_limit'),
                'remote-address' => $request->input('remote_address'),
                'local-address' => $request->input('local_address'),
            ]);

            $paket = Paket::where('nm_paket', $nm_paket)->first();
            $paket->nm_paket = $validated['nm_paket'];
            $paket->hrg_paket = $validated['hrg_paket'];
            $paket->local_address = $request->input('local_address');
            $paket->remote_address = $request->input('remote_address');
            $paket->rate_limit = $request->input('rate_limit');
            $paket->kd_cabang = $validated['kd_cabang'];
            $paket->save();

            return redirect()->route('profilemikrotik.index')->with('success', 'Profil telah diperbarui di Mikrotik dan database.');
        } else {
            return view('error.mikrotikerror');
        }
    }

    public function destroy($nm_paket)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        // Hapus entri dari database
        $paket = Paket::where('nm_paket', $nm_paket)->first();
        if ($paket) {
            // Periksa apakah paket masih berelasi dengan pelanggan
            $relatedCustomers = $paket->pelanggans()->pluck('nm_pelanggan'); // Assuming 'nama' is the column for customer name

            if ($relatedCustomers->isNotEmpty()) {
                // Jika masih berelasi, tampilkan nama pelanggan yang terkait
                $customerNames = $relatedCustomers->implode(', ');
                return redirect()->route('profilemikrotik.index')
                    ->withErrors(['error' => 'Gagal menghapus profile di Mikrotik. Profile ini digunakan oleh pelanggan: ' . $customerNames . '.']);
            }
        }

        if ($API->connect($ip, $user, $pass)) {
            // Ambil data paket dari Mikrotik untuk memastikan paket ada
            $profile = $API->comm('/ppp/profile/print', [
                '?name' => $nm_paket,
            ]);

            if (!empty($profile)) {
                // Ambil ID profil dari hasil pencarian
                $profileId = $profile[0]['.id'];

                // Hapus data dari Mikrotik menggunakan ID
                $result = $API->comm('/ppp/profile/remove', [
                    '.id' => $profileId,
                ]);

                // Periksa apakah terjadi error saat menghapus di Mikrotik
                if (isset($result['!trap']) || isset($result['!re'])) {
                    return redirect()->route('profilemikrotik.index')->with('error', 'Gagal menghapus profile di Mikrotik.');
                }
            }

            // Hapus entri dari database
            $paket = Paket::where('nm_paket', $nm_paket)->first();

            if ($paket) {
                // Jika profil berhasil dihapus dari Mikrotik, lanjut hapus dari database
                $paket->delete();
                return redirect()->route('profilemikrotik.index')->with('success', 'Profil Mikrotik dan database telah dihapus.');
            } else {
                // Jika tidak ditemukan di database
                return redirect()->route('profilemikrotik.index')->with('error', 'Profil berhasil dihapus dari Mikrotik tetapi tidak ditemukan di database.');
            }
        } else {
            // Jika koneksi ke API Mikrotik gagal
            return view('error.mikrotikerror');
        }
    }



    public function sync()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $kd_cabang = session()->get('kd_cabang'); // Ambil kd_cabang dari session
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            // Ambil semua profil dari Mikrotik
            $profiles = $API->comm('/ppp/profile/print');

            // Ambil kd_isp dari tabel Cabang berdasarkan kd_cabang
            $cabang = Cabang::where('kd_cabang', $kd_cabang)->first();
            $kd_isp = $cabang ? $cabang->kd_isp : null;

            // Tentukan ukuran chunk, misalnya 100
            $chunkSize = 100;

            // Pisahkan data menjadi beberapa chunk
            $profileChunks = array_chunk($profiles, $chunkSize);

            // Proses setiap chunk secara bertahap
            foreach ($profileChunks as $chunk) {
                $newPakets = [];
                foreach ($chunk as $profile) {
                    // Cek apakah profil sudah ada di database
                    $existingPaket = Paket::where('nm_paket', $profile['name'])
                        ->where('kd_cabang', $kd_cabang) // Sesuaikan dengan cabang yang dipilih
                        ->first();

                    if (!$existingPaket) {
                        // Jika tidak ada, simpan data ke array untuk batch insert
                        $newPakets[] = [
                            'nm_paket' => $profile['name'],
                            'rate_limit' => $profile['rate-limit'] ?? null,
                            'local_address' => $profile['local-address'] ?? null,
                            'remote_address' => $profile['remote-address'] ?? null,
                            'kd_cabang' => $kd_cabang, // Gunakan kd_cabang dari session
                            'kd_isp' => $kd_isp, // Sesuaikan logika untuk kd_isp jika diperlukan
                            'hrg_paket' => 0, // Sesuaikan logika untuk harga paket jika diperlukan
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Batch insert untuk setiap chunk
                if (!empty($newPakets)) {
                    Paket::insert($newPakets);
                }
            }

            return redirect()->route('profilemikrotik.index')->with('success', 'Data dari Mikrotik telah disinkronkan ke database.');
        } else {
            return view('error.mikrotikerror');
        }
    }
}
