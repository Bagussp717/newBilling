
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>INV{{ $invoice->kd_invoice }} - {{ $invoice->pelanggan->nm_pelanggan }}</title>
<style type="text/css">
    /* Add any required CSS here */
</style>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="5">
    <tr>
        <td width="368" valign="top">
            <img src="{{ asset('assets/images/logos/logo_invoice.png') }}" alt="Company Logo" />
            <p>Yth Bapak/Ibu: {{ $invoice->pelanggan->nm_pelanggan }}<br />
                {{ $invoice->pelanggan->alamat }}</p>
        </td>
        <td width="332" valign="top">
            <p align="right" style="font-size: 13px;">
                <strong>INV-{{ $invoice->kd_invoice }}</strong><br />
                KODE PELANGGAN: {{ $invoice->pelanggan->kd_pelanggan }}<br />
                TANGGAL TERBIT:{{ \Carbon\Carbon::parse($invoice->tgl_invoice)->locale('id')->translatedFormat('d F Y') }}<br />
                JATUH TEMPO: {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->locale('id')->translatedFormat('d F Y') }}<br />
                JENIS BILLING: PRABAYAR
            </p>
        </td>
        <td width="123" valign="top">
            <p style="font-size: 10px;" align="right">
                BAYAR DENGAN <em>QRIS</em><br /><br />
                <img src="{{ asset('assets/images/logos/qris-semesta.jpg') }}" width="95" alt="QR Code">
            </p>
        </td>
    </tr>
</table>

<table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; margin-top: -15px;">
    <tr>
        <td width="595"><strong>DESKRIPSI / LAYANAN</strong></td>
        <td width="123" align="right"><strong>JUMLAH</strong></td>
    </tr>
    <tr>
        <td width="595">{{ $invoice->pelanggan->paket->nm_paket }}</td>
        <td width="123" align="right">Rp {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td width="595" align="right"><strong>Total</strong></td>
        <td width="123" align="right"><strong>Rp {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}</strong></td>
    </tr>
</table>
<br>
<table border="1" align="left" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-top: -10px;">
    <td width="738" align="center">
        <strong><em>{{ ucfirst(terbilang_rupiah($invoice->pelanggan->paket->hrg_paket)) }}</em></strong>
    </td>
</table>
<br>
<strong style="font-size: 12px;">Catatan :</strong>
<ul style="font-size: 12px; margin-top: 0px;">
    <li>Harap melakukan pembayaran setiap tanggal 1 s/d 5 setiap bulannya untuk menghindari pemblokiran oleh sistem.</li>
    <li>Untuk permintaan nomor rekening, layanan pelanggan, dan keluhan jaringan, silahkan hubungi nomor: <em>081 216 416 437</em></li>
</ul>

</body>
</html>
<script type="text/javascript">window.print();</script>

