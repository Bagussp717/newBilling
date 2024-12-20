<?php

namespace App\Http\Controllers;

use App\Models\ISP;
use App\Models\Loket;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class LoketPembayaranController extends Controller
{

    public function index()
    {
        $user = Auth::user(); // Ambil user yang sedang terautentikasi

        // Cek apakah user adalah ISP
        if ($user->kd_role === 'isp') {
            // Ambil ISP terkait dengan user
            $isp = ISP::where('kd_user', $user->id)->first();

            if ($isp) {
                // Ambil loket berdasarkan ISP
                $lokets = Loket::with('invoice')
                    ->where('kd_isp', $isp->kd_isp) // Filter berdasarkan kd_isp dari ISP
                    ->get();
            } else {
                $lokets = collect(); // Jika tidak ada ISP, kembalikan koleksi kosong
            }
        } else {
            // Jika bukan ISP, ambil semua loket
            $lokets = Loket::with('invoice')->get();
        }

        $invoiceDates = Invoice::pluck('tgl_invoice')->unique();
        // $invoiceEnds = Invoice::pluck('tgl_akhir')->unique();

        return view('pages.loketpembayaran.index', [
            'lokets' => $lokets,
            'invoiceDates' => $invoiceDates,
            // 'invoiceEnds' => $invoiceEnds,
        ]);
    }

    public function indexloket()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Memfilter data Loket berdasarkan user yang sedang login
        $lokets = Loket::with('invoice') // Mengambil data Loket beserta invoice yang terkait
            ->where('kd_user', $user->id) // Filter Loket berdasarkan user ID
            ->get();

        // Mengambil tanggal invoice yang unik dari relasi invoice melalui loket
        $invoiceDates = Invoice::whereIn('kd_pelanggan', function ($query) use ($user) {
            $query->select('kd_pelanggan')
                ->from('pelanggans')
                ->where('kd_loket', function ($subQuery) use ($user) {
                    $subQuery->select('kd_loket')
                        ->from('lokets')
                        ->where('kd_user', $user->id); // Menghubungkan ke loket yang terkait dengan user
                });
        })
            ->pluck('tgl_invoice')
            ->unique();

        return view('pages.loketpembayaran.idexloket', [
            'lokets' => $lokets,
            'invoiceDates' => $invoiceDates,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'tgl_invoice' => 'required',
        ], [
            'tgl_invoice.required' => 'Tanggal invoice harus diisi.',
        ]);
        $kd_loket = $request->input('kd_loket');
        $decryptedId = Crypt::decryptString($kd_loket);
        $tgl_invoice = $request->input('tgl_invoice');

        session(['kd_loket' => $decryptedId]);

        $komisi = Loket::where('kd_loket', $decryptedId)->first();

        $request->session()->put('kd_loket', $decryptedId);

        // Temukan loket dengan kondisi search
        $loket = Loket::with(['invoice.pembayaran' => function ($query) {
            $query->whereNotNull('jml_bayar');
        }])->where('kd_loket', $decryptedId)->first();

        if (!$loket) {
            // Jika tidak ditemukan, kembalikan ke view dengan pesan error atau data default
            return redirect()->back()->with('error', 'Loket tidak ditemukan.');
        }

        // Filter invoice berdasarkan tanggal jika ada
        $invoices = $loket->invoice;

        if ($tgl_invoice) {
            $invoices = $invoices->where('tgl_invoice', $tgl_invoice);
        }

        // Ambil tanggal invoice dan akhir untuk dropdown
        $invoiceDates = $loket->invoice->pluck('tgl_invoice')->unique();

        // Inisialisasi total pelanggan dan array untuk menyimpan pelanggan lunas
        $totalPelanggan = 0;
        $pelangganLunas = []; // Array untuk menyimpan pelanggan yang sudah lunas

        $loket->invoice->each(function ($invoice) use ($tgl_invoice, &$totalPelanggan, &$pelangganLunas) {
            if ($invoice->tgl_invoice == $tgl_invoice) {
                // Dapatkan pelanggan terkait dengan invoice ini
                $pelanggan = $invoice->pelanggan;

                // Cek apakah pelanggan ada dan memiliki paket
                if ($pelanggan && $pelanggan->paket) {
                    // Ambil harga paket
                    $hargaPaket = $pelanggan->paket->hrg_paket;

                    // Hitung total pembayaran dari semua pembayaran di invoice
                    $totalPembayaran = $invoice->pembayaran->sum('jml_bayar'); // Ubah 'jumlah' menjadi 'jml_bayar' jika itu nama kolom yang benar

                    // Cek apakah total pembayaran sama dengan harga paket (artinya lunas)
                    if ($totalPembayaran == $hargaPaket && $hargaPaket > 0) {
                        $totalPelanggan++; // Tambah ke total pelanggan jika lunas
                        $pelangganLunas[] = $pelanggan->nm_pelanggan; // Tambah pelanggan ke array pelanggan lunas
                    }
                }
            }
        });

        // Menampilkan pelanggan yang sudah lunas
        // dd($pelangganLunas); // Gunakan dd() untuk menampilkan pelanggan yang sudah lunas


        // Hitung total bayar berdasarkan pembayaran yang ada pada tgl_invoice yang dipilih
        $totalBayar = $loket->invoice->sum(function ($invoice) use ($tgl_invoice) {
            if ($invoice->tgl_invoice == $tgl_invoice) {
                if ($invoice->pembayaran->count() > 0) {
                    return $invoice->pembayaran->sum('jml_bayar'); // Menghitung total bayar pada tanggal invoice yang dipilih
                } else {
                    return 0; // Jika tidak ada pembayaran pada tanggal invoice yang dipilih, kembalikan 0
                }
                return $invoice->pembayaran->sum('jml_bayar'); // Menjumlahkan semua pembayaran pada tanggal yang dipilih
            } else {
                return 0; // Menjumlahkan semua pembayaran pada semua tanggal
            }
        });

        // Tentukan apakah komisi fixed atau dynamic berdasarkan kolom jenis_komisi
        if ($komisi->jenis_komisi === 'fixed') {
            // Komisi tetap, gunakan nilai langsung
            $jml_komisi = $komisi->jml_komisi;
        } elseif ($komisi->jenis_komisi === 'dynamic') {
            // Komisi dinamis, kalikan dengan jumlah pelanggan yang membayar
            $jml_komisi = $komisi->jml_komisi * $totalPelanggan;
        } else {
            $jml_komisi = 0; // Default ke 0 jika jenis komisi tidak diketahui
        }

        // Tampilkan hasil ke view
        return view('pages.loketpembayaran.show', [
            'loket' => $loket,
            'invoices' => $invoices,
            'invoiceDates' => $invoiceDates,
            'kd_loket' => $kd_loket,
            'jml_komisi' => $jml_komisi,
            'totalBayar' => $totalBayar,
            'tgl_invoice' => $tgl_invoice,
        ]);
    }

    public function cetakDaftarTagihan($kd_loket, $tgl_invoice)
    {
        $decryptedId = Crypt::decryptString($kd_loket);

        // Temukan loket dan invoice
        $loket = Loket::with(['invoice.pembayaran' => function ($query) {
            $query->whereNotNull('jml_bayar');
        }])->where('kd_loket', $decryptedId)->first();

        if (!$loket) {
            return redirect()->back()->with('error', 'Loket tidak ditemukan.');
        }


        // Filter invoice berdasarkan tanggal dan urutkan
        $invoices = $loket->invoice->where('tgl_invoice', $tgl_invoice)->sortBy(function ($invoice) {
            return strtolower($invoice->pelanggan->username_pppoe); // Sort case insensitively
        });

        // Tampilkan hasil ke view untuk mencetak
        return view('pages.loketpembayaran.cetakdaftartagihan', [
            'loket' => $loket,
            'invoices' => $invoices,
            'tgl_invoice' => $tgl_invoice,
        ]);
    }

    public function cetakAllInvoice($kd_loket, $tgl_invoice)
    {
        $decryptedId = Crypt::decryptString($kd_loket);

        // Retrieve invoices for the specified loket and invoice date
        $loket = Loket::with(['invoice.pembayaran' => function ($query) {
            $query->whereNotNull('jml_bayar');
        }])->where('kd_loket', $decryptedId)->first();

        if (!$loket) {
            return redirect()->back()->with('error', 'Loket tidak ditemukan.');
        }

        // Filter invoices by the specified date and exclude those with hrg_paket = 0
        $invoices = $loket->invoice->where('tgl_invoice', $tgl_invoice)->filter(function ($invoice) {
            return $invoice->pelanggan->paket->hrg_paket > 0; // Only include invoices with hrg_paket > 0
        });

        // Pass the invoices to the view for printing
        return view('pages.loketpembayaran.cetakinvoiceall', [
            'title' => 'Cetak Semua Invoice',
            'invoices' => $invoices,
        ]);
    }
}
