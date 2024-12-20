<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tiket;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TiketController extends Controller
{
    public function index()
    {
        $pengaduans = Tiket::with('user', 'pelanggan')
            ->where('status_tiket', '!=', 'Selesai')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pages.tiket.index', compact('pengaduans'));
    }

    public function doneindex()
    {
        $pengaduans = Tiket::with('user', 'pelanggan')
            ->where('status_tiket', 'Selesai')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pages.tiket.done_index', compact('pengaduans'));
    }

    public function create()
    {
        $users = User::all();
        $pelanggans = Pelanggan::all();
        return view('pages.pengaduan.create', compact('users', 'pelanggans'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kd_user' => 'required',
            'kd_pelanggan' => 'required',
            'deskripsi_tiket' => 'required|string',
            'foto' => 'nullable|image',
            'status_tiket' => 'required|string',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('aduan', 'public');
        }

        // Membuat tiket baru
        Tiket::create([
            'kd_user' => $request->kd_user,
            'kd_pelanggan' => $request->kd_pelanggan,
            'deskripsi_tiket' => $request->deskripsi_tiket,
            'foto' => $foto,
            'status_tiket' => $request->status_tiket,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('pengaduan.index')->with('success', 'Tiket berhasil dikirim.');
    }



    public function edit($kd_tiket)
    {
        $decryptedId = Crypt::decryptString($kd_tiket);
        $tiket = Tiket::with('pelanggan')->findOrFail($decryptedId);
        $pelanggan = $tiket->pelanggan;
        return view('pages.tiket.edit', compact('tiket', 'pelanggan'));
    }

    public function update(Request $request, $kd_tiket)
    {
        $request->validate([
            'kd_user' => 'required',
            'kd_pelanggan' => 'required',
            'deskripsi_tiket' => 'required|string',
            'foto' => 'nullable|image',
            'status_tiket' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        $decryptedId = Crypt::decryptString($kd_tiket);
        $tiket = Tiket::findOrFail($decryptedId);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('tikets');
            $tiket->update(['foto' => $foto]);
        }

        $tiket->update([
            'kd_user' => $request->kd_user,
            'kd_pelanggan' => $request->kd_pelanggan,
            'deskripsi_tiket' => $request->deskripsi_tiket,
            'status_tiket' => $request->status_tiket,
            'keterangan' => $request->status_tiket,
        ]);

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil diperbarui.');
    }

}
