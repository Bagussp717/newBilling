<?php

namespace App\Http\Controllers\Mikrotik;

use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; 

class LoginController extends Controller
{

    public function login(Request $request)
    {
        // Cari cabang berdasarkan kd_cabang
        $login = Cabang::where('kd_cabang', $request->post('kd_cabang'))->first();

        if ($login) {
            $ip = $login->ip_mikrotik;
            $user = $login->username_mikrotik;
            $pass = $login->password_mikrotik;

            // Simpan data login di session
            $data = [
                'ip' => $ip,
                'user' => $user,
                'pass' => $pass,
                'kd_cabang' => $login->kd_cabang,
                'cabang_nama' => $login->nm_cabang
            ];

            $request->session()->put($data);

            Log::info('User logged in:', $data);

            // Redirect ke dashboard mikrotik berdasarkan kd_cabang
            return redirect()->route('mikrotik.dashboard');
        } else {
            return view('error.mikrotikerror');
        }
    }
}
