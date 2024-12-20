<?php

namespace App\Http\Controllers\Mikrotik;

use Exception;
use App\Models\ISP;
use App\Models\Cabang;
use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class DashboardMikrotikController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::all();
        $isps = ISP::all();

        set_time_limit(50);

        register_shutdown_function(function() {
            $error = error_get_last();
            if ($error !== null && $error['type'] === E_ERROR) {
                if (ob_get_level()) {
                    ob_end_clean();
                }
                echo '<script>
                    // Create the modal container
                    var modal = document.createElement("div");
                    modal.style.position = "fixed";
                    modal.style.top = "0";
                    modal.style.left = "0";
                    modal.style.width = "100%";
                    modal.style.height = "100%";
                    modal.style.backgroundColor = "#ffffff";
                    modal.style.display = "flex";
                    modal.style.justifyContent = "center";
                    modal.style.alignItems = "center";
                    modal.style.zIndex = "1000";

                    // Create the modal content
                    var modalContent = document.createElement("div");
                    modalContent.style.backgroundColor = "white";
                    modalContent.style.padding = "20px";
                    modalContent.style.borderRadius = "5px";
                    modalContent.style.textAlign = "center";
                    modalContent.style.boxShadow = "0 4px 8px rgba(0,0,0,0.2)";

                    // Create the message paragraph
                    var message = document.createElement("p");
                    message.textContent = "Maximum execution time of 60 seconds exceeded.";

                    // Create the button
                    var button = document.createElement("button");
                    button.textContent = "Back";
                    button.style.padding = "7px 25px";
                    button.style.backgroundColor = "#007bff";
                    button.style.color = "#ffffff";
                    button.style.border = "none";
                    button.style.borderRadius = "5px";
                    button.style.cursor = "pointer";
                    button.style.marginTop = "10px";

                    // Append the message and button to the modal content
                    modalContent.appendChild(message);
                    modalContent.appendChild(button);

                    // Append the modal content to the modal
                    modal.appendChild(modalContent);

                    // Append the modal to the body
                    document.body.appendChild(modal);

                    // Add event listener to button
                    button.addEventListener("click", function () {
                        window.history.back();
                    });
                </script>';
            }
        });

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');

        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)){
            $hotspotactive = $API->comm('/ip/hotspot/active/print');
            $resource = $API->comm('/system/resource/print');
            $secret = $API->comm('/ppp/secret/print');
            $secretactive = $API->comm('/ppp/active/print');
            $interface = $API->comm('/interface/print');
            $identity = $API->comm('/system/identity/print');
            $secretnonactive = [];

            foreach ($secret as $semua) {
                $namaSecret = $semua['name'];
                $isNonActive = true;

                foreach ($secretactive as $active) {
                    if ($active['name'] === $namaSecret) {
                        $isNonActive = false;
                        break;
                    }
                }
                if ($isNonActive) {
                    $secretnonactive[] = $semua;
                }
            }
        } else {
            return redirect()->route('cabang.index')->with([
            'error' => 'Gagal menghubungkan ke mikrotik',
            'cabangs' => $cabangs,
            'isps' => $isps
            ]);
        }

        $data = [
            'totalnonactive' => count($secretnonactive),
            'totalscreet' => count($secret),
            'totalhotspot' => count($hotspotactive),
            'hotspotactive' => count($hotspotactive),
            'secretactive' => count($secretactive),
            'cpu' => $resource[0]['cpu-load'],
            
            'uptime' => $resource[0]['uptime'],
            'version' => $resource[0]['version'],
            'interface' => $interface,
            'boardname' => $resource[0]['board-name'],
            'freememory' => $resource[0]['free-memory'],
            'freehdd' => $resource[0]['free-hdd-space'],
            'identity' => $identity[0]['name'],
        ];

        return view('pages.mikrotik.dashboard.index', $data);
    }

    public function show($kd_cabang)
    {
        $cabang = Cabang::where('kd_cabang', $kd_cabang)->firstOrFail();

        return view('pages.mikrotik.dashboard.index', compact('cabang'));
    }

    public function cpu()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if($API->connect($ip, $user, $pass)){
            $cpu = $API->comm('/system/resource/print');
        } else {
            return 'Koneksi Gagal';
        }

        $data = [
            'cpu' => $cpu[0]['cpu-load'],
        ];

        return response()->json($data);
        // return view('pages.realtime.cpu', $data);
    }

    public function uptime()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if($API->connect($ip, $user, $pass)){
            $uptime = $API->comm('/system/resource/print');
        } else {
            return 'Koneksi Gagal';
        }

        $data = [
            'uptime' => $uptime[0]['uptime'],
        ];

        return response()->json($data);
    }

    public function freememory()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if($API->connect($ip, $user, $pass)){
            $resource = $API->comm('/system/resource/print');
        } else {
            return 'Koneksi Gagal';
        }


        if (isset($resource[0]['free-memory']) && isset($resource[0]['total-memory'])) {
            $freeMemory = $resource[0]['free-memory'];
            $totalMemory = $resource[0]['total-memory'];
            $usedMemory = $totalMemory - $freeMemory;

            // Konversi memori dari byte ke gigabyte
            $freeMemoryGB = $freeMemory / (1024 * 1024 * 1024);
            $usedMemoryGB = $usedMemory / (1024 * 1024 * 1024);
            $totalMemoryGB = $totalMemory / (1024 * 1024 * 1024);

            $data = [
                'freeMemoryGB' => $freeMemoryGB,
                'usedMemoryGB' => $usedMemoryGB,
                'totalMemoryGB' => $totalMemoryGB,
            ];

            return response()->json($data);
        }

    }

    public function freehddspace()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if($API->connect($ip, $user, $pass)){
            $resource = $API->comm('/system/resource/print');
        } else {
            return 'Koneksi Gagal';
        }

        if (isset($resource[0]['free-hdd-space']) && isset($resource[0]['total-hdd-space'])) {
            $freeHddSpace = $resource[0]['free-hdd-space'];
            $totalHddSpace = $resource[0]['total-hdd-space'];
            $usedHddSpace = $totalHddSpace - $freeHddSpace;

            // Konversi ruang HDD dari byte ke gigabyte
            $freeHddSpaceGB = $freeHddSpace / (1024 * 1024 * 1024);
            $usedHddSpaceGB = $usedHddSpace / (1024 * 1024 * 1024);
            $totalHddSpaceGB = $totalHddSpace / (1024 * 1024 * 1024);

            $data = [
                'freeHddSpaceGB' => $freeHddSpaceGB,
                'usedHddSpaceGB' => $usedHddSpaceGB,
                'totalHddSpaceGB' => $totalHddSpaceGB,
            ];

            return response()->json($data);
        }
    }

    public function traffic($traffic)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if($API->connect($ip, $user, $pass)){
            $traffic = $API->comm('/interface/monitor-traffic', array(
                'interface' => $traffic,
                'once' => '',
            ));
        } else {
            return 'Koneksi Gagal';
        }

        $tx = $traffic[0]['tx-bits-per-second'];
        $rx = $traffic[0]['rx-bits-per-second'];

        $result = array(
            array(
                'name' => 'Tx',
                'data' => array($tx),
            ),
            array(
                'name' => 'Rx',
                'data' => array($rx),
            )
        );

        $API->disconnect();

        return response()->json($result);
    }

    public function isolir()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        // Ambil pelanggan yang belum bayar
        $pelangganBelumBayar = Pelanggan::whereHas('pembayaran', function($query) {
            $query->where(function($q) {
                $q->where('jml_bayar', '<=', 0)
                ->orWhereNull('jml_bayar')
                ->orWhere('jml_bayar', '');
            });
        })->get();

        // Ambil pelanggan yang tidak memiliki entri pembayaran
        $pelangganTanpaPembayaran = Pelanggan::doesntHave('pembayaran')->get();

        // Gabungkan hasil dari kedua query
        $semuaPelanggan = $pelangganBelumBayar->merge($pelangganTanpaPembayaran);

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

            // dd($secretMap);

            // Tampilkan semua nama dengan dd() jika diperlukan
            // dd($secretMap);

            // Loop melalui pelanggan yang belum bayar dan ubah profil ke 'isolir'
            foreach ($semuaPelanggan as $pelanggan) {
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

                    // Perbarui kolom status_pppoe di tabel invoice
                    Invoice::where('kd_pelanggan', $pelanggan->kd_pelanggan)
                        ->update(['status_pppoe' => 'isolir']);
                }
            }


            $API->disconnect();
        } else {
            return view('error.mikrotikerror');
        }
    }

}
