<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Denda</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            margin: 2px 0;
        }
        .content {
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px;
            margin-bottom: 8px;
            font-size: 12px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
            word-break: break-word;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 11px;
        }
        table thead {
            background-color: #f0f0f0;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }
        table th {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
        .footer p {
            font-size: 10px;
            margin: 5px 0;
        }
        .status-badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
            display: inline-block;
        }
        .status-terverifikasi {
            background-color: #d4edda;
            color: #155724;
        }
        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
        }
        .summary-box {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .total-amount {
            font-weight: bold;
            font-size: 13px;
            background-color: #f0f0f0;
            padding: 8px;
            text-align: right;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BUKTI PEMBAYARAN DENDA</h1>
            <p>Perpustakaan & Manajemen Alat</p>
            <p>Nomor Bukti: {{ $payment->id }}</p>
        </div>

        <div class="content">
            <!-- Info Pembayar -->
            <div class="section">
                <div class="section-title">DATA PEMBAYAR</div>
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">: {{ $payment->loan->user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">NIS/NIP</div>
                    <div class="info-value">: {{ $payment->loan->user->identity_number ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jurusan</div>
                    <div class="info-value">: {{ $payment->loan->user->jurusan ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">: {{ $payment->loan->user->email }}</div>
                </div>
            </div>

            <!-- Info Peminjaman Terkait -->
            <div class="section">
                <div class="section-title">INFORMASI PEMINJAMAN TERKAIT</div>
                <div class="info-row">
                    <div class="info-label">Nomor Peminjaman</div>
                    <div class="info-value">: {{ $payment->loan->id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alat/Barang</div>
                    <div class="info-value">: {{ $payment->loan->tool->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Peminjaman</div>
                    <div class="info-value">: {{ $payment->loan->tanggal_peminjaman->format('d-m-Y H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Pengembalian</div>
                    <div class="info-value">: {{ $payment->loan->tanggal_pengembalian ? $payment->loan->tanggal_pengembalian->format('d-m-Y H:i') : '-' }}</div>
                </div>
            </div>

            <!-- Detail Pembayaran -->
            <div class="section">
                <div class="section-title">DETAIL PEMBAYARAN DENDA</div>
                <table>
                    <tbody>
                        <tr>
                            <td style="font-weight: bold;">Nominal Denda</td>
                            <td style="text-align: right;">Rp {{ number_format($payment->nominal_denda, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Sisa Denda Belum Dibayar</td>
                            <td style="text-align: right;">Rp {{ number_format($payment->sisa_denda, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Jumlah Pembayaran</td>
                            <td style="text-align: right;">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Info Pembayaran -->
            <div class="section">
                <div class="section-title">INFORMASI PEMBAYARAN</div>
                <div class="info-row">
                    <div class="info-label">Metode Pembayaran</div>
                    <div class="info-value">: {{ $payment->metode_pembayaran ?? 'Tunai' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Pembayaran</div>
                    <div class="info-value">: {{ $payment->tanggal_bayar->format('d-m-Y H:i:s') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status Pembayaran</div>
                    <div class="info-value">: 
                        @if($payment->status === 'terverifikasi')
                            <span class="status-badge status-terverifikasi">Terverifikasi</span>
                        @else
                            <span class="status-badge status-menunggu">Menunggu Verifikasi</span>
                        @endif
                    </div>
                </div>
                @if($payment->verified_by)
                    <div class="info-row">
                        <div class="info-label">Diverifikasi Oleh</div>
                        <div class="info-value">: {{ $payment->verifiedBy->name ?? 'Petugas' }}</div>
                    </div>
                @endif
                @if($payment->tanggal_verifikasi)
                    <div class="info-row">
                        <div class="info-label">Tanggal Verifikasi</div>
                        <div class="info-value">: {{ $payment->tanggal_verifikasi->format('d-m-Y H:i:s') }}</div>
                    </div>
                @endif
                @if($payment->catatan)
                    <div class="info-row">
                        <div class="info-label">Catatan</div>
                        <div class="info-value">: {{ $payment->catatan }}</div>
                    </div>
                @endif
            </div>

            <!-- Ringkasan -->
            <div class="section">
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Total Denda Awal</span>
                        <span>Rp {{ number_format($payment->nominal_denda, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Sudah Dibayar</span>
                        <span>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-amount">
                        Sisa Hutang: Rp {{ number_format($payment->sisa_denda, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Catatan Penting -->
            <div class="section">
                <div class="section-title">CATATAN PENTING</div>
                <p style="font-size: 11px; line-height: 1.6; padding: 10px;">
                    ✓ Bukti pembayaran ini sebagai tanda terima pembayaran denda<br/>
                    ✓ Simpan bukti ini sebagai arsip pribadi<br/>
                    @if($payment->sisa_denda > 0)
                        ✓ Masih ada sisa denda sebesar Rp {{ number_format($payment->sisa_denda, 0, ',', '.') }}<br/>
                    @endif
                    ✓ Untuk informasi lebih lanjut, hubungi petugas perpustakaan
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Dicetak pada: {{ $tanggal_cetak->format('d-m-Y H:i:s') }}</strong></p>
            <p style="margin-top: 15px; font-size: 10px;">Terima kasih telah melunasi denda tepat waktu</p>
        </div>
    </div>
</body>
</html>
