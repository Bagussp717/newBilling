<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    public function index($id)
    {
        $decryptedId = Crypt::decryptString($id);
        $admin = User::findOrFail($decryptedId);
        return view('pages.superadmin.index', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $decryptedId = Crypt::decryptString($id);
        $admin = User::findOrFail($decryptedId);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|min:6',
        ],
        [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus berupa email yang valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password harus minimal 6 karakter.',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();
        return redirect()->route('admin.edit', Crypt::encryptString($admin->id))->with('success', 'Admin berhasil diperbarui');
    }
}
