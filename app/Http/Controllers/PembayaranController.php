<?php

namespace App\Http\Controllers;

use App\Models\Loket;
use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PembayaranController extends Controller
{
    // public function create($kd_invoice)
    // {
    //     // dd($kd_invoice);
    //     $decryptedId = Crypt::decrypt($kd_invoice);
    //     $ip = session()->get('ip');
    //     $invoice = Invoice::with('pelanggan')->where('kd_invoice', $decryptedId)->first();
    //     $lokets = Loket::all();
    //     $invoices = Invoice::all();
    //     $pembayarans = Pembayaran::where('kd_invoice', $decryptedId)->get();
    //     $pelanggan = $invoice->pelanggan;
    //     $kd_invoice = $invoice->kd_invoice;

    //     return view('pages.pembayaran.create', compact('invoice', 'lokets', 'invoices', 'kd_invoice', 'pelanggan', 'pembayarans', 'ip', 'kd_invoice'));
    // }
    public function create(Request $request, $kd_invoice)
    {
        // Decrypt the invoice ID
        $decryptedId = Crypt::decrypt($kd_invoice);

        // Fetch session data
        $ip = session()->get('ip');

        // Fetch invoice data along with associated pelanggan
        $invoice = Invoice::with('pelanggan')->where('kd_invoice', $decryptedId)->first();
        $pelanggan = $invoice->pelanggan;

        // Get kd_isp of the current logged-in user
        $user = Auth::user();

        // Check user's role and filter lokets based on the user's ISP code
        if ($user->hasRole('isp')) {
            $isps = $user->isp;
            $kd_isps = $isps->pluck('kd_isp');  // Get the ISP codes for the user
            // Filter Loket by the user's ISP code
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
        } elseif ($user->hasRole('loket')) {
            $kd_isps = $user->loket->pluck('kd_isp');
            $lokets = Loket::whereIn('kd_isp', $kd_isps)->get();
        } else {
            // For super-admin or other roles, show all Lokets
            $lokets = Loket::all();
        }

        // Fetch related data for the view
        $invoices = Invoice::all();
        $pembayarans = Pembayaran::where('kd_invoice', $decryptedId)->get();
        $kd_invoice = $invoice->kd_invoice;


        if ($request->wantsJson()) {
            return response()->json([
                'invoice' => $invoice,
                'paket' => $pelanggan->paket,
                'lokets' => $lokets,
                'kd_invoice' => $kd_invoice,
                'pelanggan' => $pelanggan,
                'pembayarans' => $pembayarans,
                'ip' => $ip,
            ]);
        }
        // Return the view with the required data
        return view('pages.pembayaran.create', compact('invoice', 'lokets', 'invoices', 'kd_invoice', 'pelanggan', 'pembayarans', 'ip', 'kd_invoice'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'kd_loket' => 'required',
            'kd_invoice' => 'required',
            'tgl_bayar' => 'required',
            'jml_bayar' => 'required',
        ], [
            'kd_invoice.required' => 'Kode invoice harus diisi.',
            'tgl_bayar.required' => 'Tgl bayar harus diisi.',
            'jml_bayar.required' => 'Jml bayar harus diisi.',
            'kd_loket.required' => 'Kode loket harus diisi.',
        ]);

        // temukan invoice
        $invoice = Invoice::where('kd_invoice', $request->kd_invoice)->first();

        if (!$invoice) {
            return redirect()->back()->with(['error' => 'Invoice tidak ditemukan berdasarkan id invoice']);
        }

        // temukan pelanggan berdasarkan invoice
        $pelanggan = Pelanggan::where('kd_pelanggan', $invoice->kd_pelanggan)->first();

        if (!$pelanggan) {
            return redirect()->back()->with(['error' => 'Pelanggan tidak ditemukan berdasarkan id pelanggan']);
        }

        // ambil data mikrotik
        $ip = $pelanggan->cabang->ip_mikrotik;
        $user = $pelanggan->cabang->username_mikrotik;
        $pass = $pelanggan->cabang->password_mikrotik;
        $defaultProfile = $pelanggan->profile_pppoe;


        $API = new RouterosAPI();
        $API->debug(false);

        if ($API->connect($ip, $user, $pass)) {
            $secrets = $API->comm('/ppp/secret/print');

            $statusPppoe = null;

            foreach ($secrets as $secret) {
                if ($secret['name'] === $pelanggan->username_pppoe) {
                    $statusPppoe = $secret['profile']; // Get the current profile
                    if ($statusPppoe === 'isolir') {
                        $API->comm('/ppp/secret/set', [
                            '.id' => $secret['.id'],
                            'profile' => $defaultProfile
                        ]);

                        // Update status_pppoe in the database
                        Invoice::where('kd_invoice', $request->kd_invoice)
                            ->update(['status_pppoe' => $defaultProfile]);
                    }
                    break; // No need to continue the loop once found
                }
            }

            if (is_null($statusPppoe)) {
                return redirect()->back()->withErrors(['error' => 'PPPoE profile tidak ditemukan']);
            }

            $pembayaran = new Pembayaran();
            $pembayaran->kd_loket = $request->kd_loket;
            $pembayaran->tgl_bayar = $request->tgl_bayar;
            $pembayaran->kd_invoice = $request->kd_invoice;
            $pembayaran->jml_bayar = $request->jml_bayar;
            $pembayaran->save();

            // return redirect()->route('pembayaran.create', Crypt::encrypt($request->kd_invoice))->with('success', 'Data pembayaran berhasil ditambahkan');
            // Redirect dengan parameter yang diminta
            return redirect()->route('search.invoice', [
                'kd_loket' => Crypt::encryptString($request->kd_loket),
                'tgl_invoice' => $invoice->tgl_invoice ?? null,
            ])->with('success', 'Data pembayaran berhasil ditambahkan');
        } else {
            return view('error.mikrotikerror');
        }
    }

    public function update(Request $request, $kd_pembayaran)
    {
        // dd($request->all());
        // Valkd$kd_pembayaran)ate the incoming request
        $request->validate([
            'kd_loket' => 'required',
            'kd_invoice' => 'required',
            'tgl_bayar' => 'required',
            'jml_bayar' => 'required',
        ], [
            'kd_invoice.required' => 'Kode invoice harus diisi.',
            'tgl_bayar.required' => 'Tgl bayar harus diisi.',
            'jml_bayar.required' => 'Jml bayar harus diisi.',
            'kd_loket.required' => 'Kode loket harus diisi.',
        ]);

        // dd($request->all());

        // Find the existing Pembayaran record by its ID
        $pembayaran = Pembayaran::find($kd_pembayaran);

        // Update the Pembayaran record with the new data
        $pembayaran->kd_loket = $request->kd_loket;
        $pembayaran->tgl_bayar = $request->tgl_bayar;
        $pembayaran->kd_invoice = $request->kd_invoice;
        $pembayaran->jml_bayar = $request->jml_bayar;
        $pembayaran->save();

        return redirect()->route('pembayaran.create', Crypt::encrypt($request->kd_invoice))->with('success', 'Data pembayaran berhasil diperbarui');
    }

    public function destroy($kd_pembayaran)
    {
        $decryptedId = Crypt::decryptString($kd_pembayaran);
        $pembayaran = Pembayaran::find($decryptedId);
        $pembayaran->delete();
        return redirect()->back()->with('success', 'Data pembayaran berhasil dihapus');
    }
}
