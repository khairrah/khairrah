<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Pengembalian</title>
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
        .status-baik {
            background-color: #d4edda;
            color: #155724;
        }
        .status-rusak {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-hilang {
            background-color: #f8d7da;
            color: #721c24;
        }
        .denda-summary {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }
        .denda-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .total-denda {
            font-weight: bold;
            font-size: 12px;
            background-color: #f0f0f0;
            padding: 8px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>STRUK PENGEMBALIAN ALAT/BARANG</h1>
            <p>Perpustakaan & Manajemen Alat</p>
            <p>Nomor Transaksi: {{ $loan->id }}</p>
        </div>

        <div class="content">
            <!-- Info Peminjam -->
            <div class="section">
                <div class="section-title">DATA PEMINJAM</div>
                <div class="info-row">
                    <div class="info-label">Nama Peminjam</div>
                    <div class="info-value">: {{ $loan->user->name }}</div>
                </div>
            </div>

            <!-- Info Pengembalian -->
            <div class="section">
                <div class="section-title">DATA PENGEMBALIAN</div>
                <div class="info-row">
                    <div class="info-label">Alat/Barang</div>
                    <div class="info-value">: {{ $loan->tool->nama_alat ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kategori</div>
                    <div class="info-value">: {{ $loan->tool->category->nama_kategori ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jumlah Dipinjam</div>
                    <div class="info-value">: {{ $loan->jumlah }} unit</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Peminjaman</div>
                    <div class="info-value">: {{ $loan->tanggal_pinjam ? \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d-m-Y H:i') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Target Kembali</div>
                    <div class="info-value">: {{ $loan->tanggal_kembali_target ? \Carbon\Carbon::parse($loan->tanggal_kembali_target)->format('d-m-Y H:i') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Pengembalian Aktual</div>
                    <div class="info-value">: {{ $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d-m-Y H:i') : '-' }}</div>
                </div>
            </div>

            <!-- Status Barang -->
            <div class="section">
                <div class="section-title">KONDISI BARANG YANG DIKEMBALIKAN</div>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $loan->tool->nama_alat ?? '-' }}</td>
                            <td>{{ $loan->jumlah }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($loan->status_alat ?? 'baik') }}">
                                    {{ ucfirst($loan->status_alat ?? 'baik') }}
                                </span>
                            </td>
                            <td>{{ $loan->alasan_denda ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Perhitungan Denda -->
            <div class="section">
                <div class="section-title">PERHITUNGAN DENDA</div>
                <div class="denda-summary">
                    @if($loan->alasan_denda)
                        <div class="denda-row">
                            <span>Rincian Denda:</span>
                            <span style="text-align: right;">{{ $loan->alasan_denda }}</span>
                        </div>
                    @else
                        <div class="denda-row">
                            <span>Status: Barang dalam kondisi baik</span>
                            <span><strong>Rp 0,-</strong></span>
                        </div>
                    @endif

                    <div class="total-denda">
                        Total Denda: <strong>Rp {{ number_format($loan->denda, 0, ',', '.') }}</strong>
                        @if($loan->denda_status)
                            <div style="font-size: 10px; margin-top: 5px;">Status: {{ ucfirst(str_replace('_', ' ', $loan->denda_status)) }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Verifikasi -->
            <div class="section">
                <div class="section-title">VERIFIKASI PENGEMBALIAN</div>
                <div class="info-row">
                    <div class="info-label">Diverifikasi Oleh</div>
                    <div class="info-value">: Petugas Perpustakaan</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Verifikasi</div>
                    <div class="info-value">: {{ now()->format('d-m-Y H:i:s') }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>Dicetak pada: {{ $tanggal_cetak->format('d-m-Y H:i:s') }}</strong></p>
            <p style="margin-top: 15px; font-size: 10px;">Terima kasih telah mengembalikan barang dengan baik</p>
        </div>
    </div>
</body>
</html>
