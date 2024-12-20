<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Menampilkan data sesi pengguna dalam format JSON.
     * Hanya dapat diakses oleh pengguna yang sudah login.
     */
    public function getSessionData(Request $request)
    {
        // Mendapatkan data pengguna yang sedang login
        $user = $request->user();

        // Memeriksa apakah pengguna ada (sudah login)
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // Mengambil role pengguna
        $roles = $user->getRoleNames(); // Mendapatkan nama-nama role yang dimiliki

        // Mengembalikan data pengguna dalam format JSON
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $roles, // Menambahkan roles ke dalam respons JSON
            // Tambahkan data sesi lain sesuai kebutuhan
        ]);
    }
}
