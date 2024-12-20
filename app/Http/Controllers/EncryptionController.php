<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class EncryptionController extends Controller
{
    public function encryptData($data)
    {
        // Pastikan pengguna sudah terautentikasi
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }
    
        // Enkripsi data menggunakan Crypt Laravel
        try {
            // Gunakan encryptString untuk konsistensi dengan tampilan
            $encryptedData = Crypt::encryptString($data);
    
            return response()->json([
                'success' => true,
                'encryptedData' => $encryptedData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Encryption failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function encryptArray($data)
    {
        // Pastikan pengguna sudah terautentikasi
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }
    
        // Enkripsi data menggunakan Crypt Laravel
        try {
            // Gunakan encryptString untuk konsistensi dengan tampilan
            $encryptedData = Crypt::encrypt($data);
    
            return response()->json([
                'success' => true,
                'encryptedData' => $encryptedData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Encryption failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
