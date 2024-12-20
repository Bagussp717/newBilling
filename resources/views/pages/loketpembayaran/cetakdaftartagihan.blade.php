@php
    use Carbon\Carbon;

    // Membuat instance Carbon dari tanggal invoice
    $date = Carbon::parse($tgl_invoice);

    // Mengatur locale ke Bahasa Indonesia
    Carbon::setLocale('id');

    // Memformat nama bulan dalam Bahasa Indonesia
    $monthName = $date->translatedFormat('F'); // 'F' memberikan representasi bulan secara tekstual
    $year = $date->year;
@endphp
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>DAFTAR TAGIHAN - {{ strtoupper($loket->nm_loket) }}</title>
    <style type="text/css">
        table {
            font-size: 13px;
        }

        @media print {

            /* Mengatur margin untuk cetakan */
            @page {
                margin: 7mm;
                /* Menghapus margin default */
            }

            body {
                margin: 0;
                /* Mengatur margin untuk body */
            }

            header,
            footer {
                display: none;
                /* Menyembunyikan header dan footer */
            }
        }
    </style>
</head>

<body>
    <p align="center" style="font-weight: bold;">DAFTAR TAGIHAN
        {{ strtoupper($loket->nm_loket) }}<br>{{ strtoupper($monthName) }} {{ $year }}</p>
    <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;" align="center">
        <tbody>
            <tr>
                <td><strong>No.</strong></td>
                <td><strong>Username</strong></td>
                <td><strong>Nama</strong></td>
                <td><strong>Alamat</strong></td>
                <td><strong>Paket</strong></td>
                <td><strong>Jumlah Bayar</strong></td>
            </tr>
            @php
                // Mengurutkan invoices berdasarkan nama pelanggan secara ascending
                $sortedInvoices = $invoices->sortBy(function ($invoice) {
                    return $invoice->pelanggan->nm_pelanggan;
                });

                $no = 1; // Inisialisasi nomor urut
                $sum = 0; // Inisialisasi total pembayaran
            @endphp

            @foreach ($sortedInvoices as $invoice)
                @if ($invoice->pelanggan->profile_pppoe !== 'Gratis' && optional($invoice->pelanggan->paket)->keterangan !== 'uji coba')
                    <tr>
                        <td align="center">{{ $no }}</td> <!-- Menampilkan nomor urut -->
                        <td>{{ $invoice->pelanggan->username_pppoe }}</td>
                        <td>{{ ucfirst($invoice->pelanggan->nm_pelanggan) }}</td>
                        <td>{{ ucfirst($invoice->pelanggan->alamat) }}</td>
                        <td align="right">Rp{{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    @php
                        $no++; // Menaikkan nomor urut
                        $sum += $invoice->pelanggan->paket->hrg_paket; // Menambahkan harga paket ke total
                    @endphp
                @endif
            @endforeach

            <tr>
                <td colspan="4" align="right"><strong>Estimasi Total:</strong></td>
                <td align="right"><strong>Rp{{ number_format($sum, 0, ',', '.') }}</strong></td>
                <!-- Menampilkan total pembayaran -->
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
<script type="text/javascript">
    window.print(); // Otomatis mencetak halaman saat dibuka
</script>
