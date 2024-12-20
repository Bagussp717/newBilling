<!doctype html>
<html>
<title>{{ $title }}</title>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style type="text/css">
        @media print {
            @page {
                margin: 15mm;
            }

            body {
                margin: 0;
                padding: 1px;
            }

            header,
            footer {
                display: none;
            }

            /* Add a page break after every two invoices */
            .invoice-group {
                page-break-after: always;
            }

            /* Prevent the last group from having a break after it */
            .invoice-group:last-child {
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>
    @foreach ($invoices->sortBy('pelanggan.nm_pelanggan')->chunk(3) as $invoiceGroup)
        <div class="invoice-group">
            @foreach ($invoiceGroup as $invoice)
                <table border="0" cellspacing="0" cellpadding="5">
                    @if ($invoice->pelanggan->profile_pppoe !== 'Gratis' && optional($invoice->pelanggan->paket)->keterangan !== 'uji coba')
                        <tr>
                            <td width="368" valign="top">
                                <img src="{{ asset('assets/images/logos/logo_invoice.png') }}" />
                                <p>Yth Bapak/Ibu : {{ ucfirst($invoice->pelanggan->nm_pelanggan) }} <br />
                                    {{ ucfirst($invoice->pelanggan->alamat) }}</p>
                            </td>
                            <td width="332" valign="top">
                                <p align="right" style="font-size: 13px;">
                                    <strong>INV-240{{ $invoice->kd_invoice }}</strong><br />KODE PELANGGAN
                                    :000{{ $invoice->kd_pelanggan }}<br>
                                    TANGGAL TERBIT :
                                    {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->locale('id')->isoFormat('D MMMM YYYY') }}<br />
                                    JATUH TEMPO :
                                    {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->locale('id')->isoFormat('D MMMM YYYY') }}<br />
                                    JENIS BILLING : PRABAYAR
                                </p>
                            </td>
                            <td width="123" valign="top">
                                <p style="font-size: 10px;" align="right">BAYAR DENGAN <em>QRIS</em><br><br><img
                                        src="{{ asset('assets/images/logos/qris-semesta.jpg') }}" width="95"></p>
                            </td>
                        </tr>
                    @endif
                </table>

                <!-- Payment Status Section -->
               
                <table border="1" cellspacing="0" cellpadding="5"
                    style="border-collapse: collapse; margin-top: -15px;">
                    <tr>
                        <td width="595"><strong>DESKRIPSI</strong><strong> / LAYANAN</strong></td>
                        <td width="123" align="right"><strong>JUMLAH</strong></td>
                    </tr>
                    <tr>
                        <td width="595">{{ $invoice->pelanggan->paket->nm_paket }}</td>
                        <td width="123" align="right">
                            Rp{{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td width="595" align="right"><strong>Total</strong></td>
                        <td width="123" align="right">
                            <strong>Rp{{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </table>

                <strong style="font-size: 12px;">Catatan :</strong>
                <ul style="font-size: 12px; margin-top: 0px;">
                    <li>Harap melakukan pembayaran setiap tanggal 1 s/d 5 setiap bulannya untuk menghindari pemblokiran
                        oleh sistem.</li>
                    <li>Untuk permintaan nomor rekening, layanan pelanggan dan keluhan jaringan silahkan hubungi nomor :
                        <em>081 216 416 437</em></li>
                </ul>
                <br>

            @endforeach
        </div>
    @endforeach

</body>

</html>
<script type="text/javascript">
    window.print();
</script>
