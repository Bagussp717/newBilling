<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tiket;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = request()->input('search');

        $pengaduans = Tiket::with('user', 'pelanggan')
            ->where('kd_user', $user->id) 
            ->when($search, function ($query, $search) {
                $query->Where('deskripsi_tiket', 'like', '%' . $search . '%'); // Filter deskripsi_tiket
            })
            ->orderBy('created_at', 'desc')
            ->get();

            if ($request->wantsJson()) {
                return response()->json($pengaduans);
        }

        return view('pages.pengaduan.index', compact('pengaduans'));
    }

    public function create(Request $request)
    {
        $users = User::all(); 
        $pelanggans = Pelanggan::all();

        if ($request->wantsJson()) {
            $user = Auth::user();

            return response()->json([
                'user' => $user,
                'pelanggans' => $pelanggans,
            ]);
        }

        return view('pages.pengaduan.create', compact('users', 'pelanggans'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'kd_user' => 'required',
            'kd_pelanggan' => 'required',
            'deskripsi_tiket' => 'required|string',
            'foto' => 'nullable|image',
        ]);

        $foto = null;

        // Jika ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Kompresi gambar menggunakan Intervention Image untuk resize
            $imagePath = storage_path('app/public/aduan/' . $filename);

            // Resize gambar (contoh, lebar maksimal 800px)
            $imageIntervention = Image::make($image)->resize(500, null, function ($constraint) {
                $constraint->aspectRatio(); // Mempertahankan rasio aspek
                $constraint->upsize(); // Mencegah peningkatan ukuran berlebihan
            });

            // Simpan gambar dengan kualitas 90%
            $imageIntervention->save($imagePath, 60);

            // Optimalkan gambar menggunakan Spatie Image Optimizer
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($imagePath);

            // Simpan nama file foto
            $foto = 'aduan/' . $filename;
        }

        // Membuat tiket baru
        Tiket::create([
            'kd_user' => $request->kd_user,
            'kd_pelanggan' => $request->kd_pelanggan,
            'deskripsi_tiket' => $request->deskripsi_tiket,
            'foto' => $foto,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('pengaduan.index')->with('success', 'Tiket berhasil dikirim.');
    }
    


    public function edit($id)
    {
        $tiket = Tiket::findOrFail($id);
        return view('pages.pengaduan.edit', compact('tiket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kd_user' => 'required|exists:users,id',
            'kd_pelanggan' => 'required|exists:pelanggans,id',
            'deskripsi_tiket' => 'required|string',
            'foto' => 'nullable|image',
            'status_tiket' => 'required|string',
        ]);

        $tiket = Tiket::findOrFail($id);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('tikets');
            $tiket->update(['foto' => $foto]);
        }

        $tiket->update([
            'kd_user' => $request->kd_user,
            'kd_pelanggan' => $request->kd_pelanggan,
            'deskripsi_tiket' => $request->deskripsi_tiket,
            'status_tiket' => $request->status_tiket,
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Tiket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tiket = Tiket::findOrFail($id);
        
        $tiket->delete();

        return redirect()->route('pengaduan.index')->with('success', 'Tiket berhasil dihapus.');
    }
}
