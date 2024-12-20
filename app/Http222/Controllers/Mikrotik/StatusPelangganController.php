<?php

namespace App\Http\Controllers\Mikrotik;

use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusPelangganController extends Controller
{
    public function secret()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $data = $API->comm('/ppp/secret/print');

            return view('pages.mikrotik.statuspelanggan.index', compact('data'));
        } else {
            return view('error.mikrotikerror');
        }
    }

    public function active()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $data = $API->comm('/ppp/active/print');

            return view('pages.mikrotik.statuspelanggan.index', compact('data'));
        } else {
            return view('error.mikrotikerror');
        }
    }

    public function nonActive()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secretactive = $API->comm('/ppp/active/print');
            $secret = $API->comm('/ppp/secret/print');

            $data = [];

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
                    $data[] = $semua;
                }
            }

            return view('pages.mikrotik.statuspelanggan.index', compact('data'));
        } else {
            return view('error.mikrotikerror');
        }
    }
}
