<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CryptController extends Controller
{
    public function decrypt(Request $request)
    {
        // Validasi input
        $request->validate([
            'encrypted_value' => 'required|string',
        ]);

        try {
            // Dekripsi nilai terenkripsi
            $encryptedValue = $request->input('encrypted_value');
            $decryptedValue = Crypt::decrypt($encryptedValue);

            // Kembalikan nilai hasil dekripsi
            return response()->json([
                'success' => true,
                'decrypted_value' => $decryptedValue,
            ], 200);
        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan pesan error
            return response()->json([
                'success' => false,
                'message' => 'Decryption failed. ' . $e->getMessage(),
            ], 400);
        }
    }
}
