<?php

namespace App\Http\Controllers;

use App\Models\ISP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class IspController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $isp = ISP::all();
        return view('pages.isp.index', compact('isp'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.isp.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nm_isp' => 'required',
            'nm_brand' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ], [
            'nm_isp.required' => 'Nama ISP harus diisi.',
            'nm_brand.required' => 'Nama Brand harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'logo.required' => 'Logo harus diupload.',
            'logo.image' => 'Logo harus berupa file gambar.',
            'logo.mimes' => 'Logo harus memiliki format: jpeg, png, jpg, gif, atau svg.',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 2MB.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus minimal 6 karakter.',
        ]);


        $user = new User();
        $user->name = $request->nm_isp;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->kd_role = $request->kd_role;
        $user->save();

        $user->assignRole('isp');

        $isp = new ISP();
        $isp->nm_isp = $request->nm_isp;
        $isp->nm_brand = $request->nm_brand;
        $isp->alamat = $request->alamat;
        $isp->no_telp = $request->no_telp;
        $isp->kd_user = $user->id;

        if ($request->hasFile('logo')) {
            if ($isp->logo && Storage::disk('public')->exists($isp->logo)) {
                Storage::disk('public')->delete($isp->logo);
            }

            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logos', $filename, 'public');
            $isp->logo = $path;
        }

        $isp->save();

        return redirect()->route('isp.index')->with('success', 'Tambah data berhasil');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kd_isp)
    {
        $decryptedId = Crypt::decryptString($kd_isp);
        $isp = ISP::where('kd_isp', $decryptedId)->first();
        return view('pages.isp.edit', compact('isp'));
    }

    public function update(Request $request, $kd_isp)
    {
        $decryptedId = Crypt::decryptString($kd_isp);
        $isp = ISP::where('kd_isp', $decryptedId)->first();
        $user = User::find($isp->kd_user);
            // Validasi input dengan pesan kustom
            $request->validate([
                'nm_isp' => 'required',
                'nm_brand' => 'required',
                'alamat' => 'required',
                'no_telp' => 'required',
                'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'email' => 'required|email|unique:users,email,' . $user->id,
            ], [
                'nm_isp.required' => 'Nama ISP harus diisi.',
                'nm_brand.required' => 'Nama Brand harus diisi.',
                'alamat.required' => 'Alamat harus diisi.',
                'no_telp.required' => 'Nomor telepon harus diisi.',
                'logo.image' => 'Logo harus berupa file gambar.',
                'logo.mimes' => 'Logo harus memiliki format: jpeg, png, jpg, gif, atau svg.',
                'logo.max' => 'Ukuran logo tidak boleh lebih dari 2MB.',
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
            ]);

             // Update user data
            $user->name = $request->nm_isp;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $isp->nm_isp = $request->nm_isp;
            $isp->nm_brand = $request->nm_brand;
            $isp->alamat = $request->alamat;
            $isp->no_telp = $request->no_telp;

            if ($request->hasFile('logo')) {
                if ($isp->logo && Storage::disk('public')->exists($isp->logo)) {
                    Storage::disk('public')->delete($isp->logo);
                }

                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('logos', $filename, 'public');
                $isp->logo = $path;
            }

            $isp->save();

            return redirect()->route('isp.index')->with('success', 'Data berhasil diperbarui');

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $kd_isp)
    {
        $decryptedId = Crypt::decryptString($kd_isp);
        $isp = ISP::where('kd_isp', $decryptedId)->first();
        $user = User::find($isp->kd_user);

        if ($isp->cabangs()->exists()) {
            return redirect()->route('isp.index')->withErrors(['error' => 'ISP ini terkait dengan cabang dan tidak bisa dihapus.']);
        }

        if ($isp->logo && Storage::disk('public')->exists($isp->logo)) {
            Storage::disk('public')->delete($isp->logo);
        }

        $isp->delete();
        $user->delete();

        return redirect()->route('isp.index')->with('success', 'Data ISP berhasil dihapus.');
    }

}
