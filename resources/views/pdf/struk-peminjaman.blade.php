<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Peminjaman</title>
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
        .status {
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
        }
        .status.approved {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>STRUK PEMINJAMAN ALAT/BARANG</h1>
            <p>Manajemen Alat</p>
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

            <!-- Info Peminjaman -->
            <div class="section">
                <div class="section-title">DATA PEMINJAMAN</div>
                <div class="info-row">
                    <div class="info-label">Alat/Barang</div>
                    <div class="info-value">: {{ $loan->tool->nama_alat ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kategori</div>
                    <div class="info-value">: {{ $loan->tool->category->nama_kategori ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jurusan</div>
                    <div class="info-value">: {{ $loan->tool->jurusan ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jumlah</div>
                    <div class="info-value">: {{ $loan->jumlah }} unit</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Peminjaman</div>
                    <div class="info-value">: {{ $loan->tanggal_pinjam ? \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d-m-Y H:i') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Tenggat Pengembalian</div>
                    <div class="info-value">: {{ $loan->tanggal_kembali_target ? \Carbon\Carbon::parse($loan->tanggal_kembali_target)->format('d-m-Y H:i') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">: <span class="status approved">{{ ucfirst(str_replace('_', ' ', $loan->status)) }}</span></div>
                </div>
            </div>

            <!-- Peraturan & Denda -->
            <div class="section">
                <div class="section-title">INFORMASI DENDA (JIKA TERLAMBAT/RUSAK/HILANG)</div>
                <table>
                    <thead>
                        <tr>
                            <th>Status Barang</th>
                            <th>Biaya Denda</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Terlambat</td>
                            <td>Rp 1.000,- / hari / unit</td>
                            <td>Per hari keterlambatan</td>
                        </tr>
                        <tr>
                            <td>Rusak/Berkurang</td>
                            <td>50% dari harga</td>
                            <td>Jika barang rusak/hilang sebagian</td>
                        </tr>
                        <tr>
                            <td>Hilang</td>
                            <td>100% dari harga</td>
                            <td>Jika barang benar-benar hilang</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Catatan -->
            <div class="section">
                <div class="section-title">CATATAN PENTING</div>
                <p style="font-size: 11px; line-height: 1.6; padding: 10px;">
                    • Harap kembalikan barang tepat waktu sesuai tanggal tenggat pengembalian<br/>
                    • Jika terlambat, denda akan dikenakan otomatis per hari<br/>
                    • Barang harus dikembalikan dalam kondisi baik dan bersih<br/>
                    • Mohon konfirmasi penerimaan barang dengan petugas<br/>
                    • Simpan struk ini sebagai bukti peminjaman
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Dicetak pada: {{ $tanggal_cetak->format('d-m-Y H:i:s') }}</strong></p>
            <p style="margin-top: 15px; font-size: 10px;">Terima kasih telah menggunakan layanan kami</p>
        </div>
    </div>
</body>
</html>
