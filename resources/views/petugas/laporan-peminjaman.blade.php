@extends('layouts.petugas')

@section('content')
<div class="p-6" style="background-color: #FFF7E6;">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📊 Laporan Peminjaman</h1>
            <p class="text-gray-600">Riwayat peminjaman yang sudah dikembalikan</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('petugas.laporan-peminjaman') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Filter Siswa -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Siswa</label>
                    <select name="user_id" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        <option value="">-- Semua Siswa --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Alat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alat</label>
                    <select name="tool_id" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        <option value="">-- Semua Alat --</option>
                        @foreach($tools as $tool)
                            <option value="{{ $tool->id }}" {{ request('tool_id') == $tool->id ? 'selected' : '' }}>
                                {{ $tool->nama_alat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tanggal Dari -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                </div>

                <!-- Filter Tanggal Sampai -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                </div>

                <!-- Button -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 text-gray-800 font-semibold rounded-lg transition" style="background: #CDEDEA;">
                        🔍 Filter
                    </button>
                    <a href="{{ route('petugas.laporan-peminjaman') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-lg transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Peminjaman</p>
                <p class="text-3xl font-bold text-blue-600">{{ $loans->total() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Tanpa Denda</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ \App\Models\Loan::where('status', 'returned')->where('denda', 0)->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Ada Denda</p>
                <p class="text-3xl font-bold text-red-600">
                    {{ \App\Models\Loan::where('status', 'returned')->where('denda', '>', 0)->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Denda</p>
                <p class="text-3xl font-bold text-orange-600">
                    Rp {{ number_format(\App\Models\Loan::where('status', 'returned')->sum('denda'), 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Tabel Peminjaman -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($loans->count() > 0)
                <table class="w-full">
                    <thead style="background: #CDEDEA; color: #374151;">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Siswa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Alat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Target Kembali</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Status Alat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Denda</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($loans as $loan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ($loans->currentPage() - 1) * 20 + $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $loan->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $loan->tool->nama_alat ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $loan->jumlah }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $loan->tanggal_kembali_target ? \Carbon\Carbon::parse($loan->tanggal_kembali_target)->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($loan->status_alat === 'baik')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">✓ Baik</span>
                                    @elseif ($loan->status_alat === 'rusak')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">⚠️ Rusak</span>
                                    @elseif ($loan->status_alat === 'hilang')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">✗ Hilang</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded text-xs font-semibold flex flex-col items-center leading-tight" style="min-width:48px;">
                                            <span>BELUM</span>
                                            <span>DIISI</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold">
                                    @if ($loan->denda > 0)
                                        <span class="text-red-600">Rp {{ number_format($loan->denda, 0, ',', '.') }}</span>
                                        <br>
                                        <small class="text-gray-600">
                                            {{ $loan->denda_status === 'lunas' ? '✓ Lunas' : ($loan->denda_status ? '⏳ ' . ucfirst(str_replace('_', ' ', $loan->denda_status)) : 'Belum Diisi') }}
                                        </small>
                                    @else
                                        <span class="text-green-600">Rp 0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('petugas.cetak-pengembalian', $loan->id) }}" target="_blank" class="inline-block px-4 py-2 text-gray-800 text-xs font-semibold rounded-lg transition" style="background: #CDEDEA;">
                                        🖨️ Cetak
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-8 text-center text-gray-500">
                    <p>Tidak ada data peminjaman yang dikembalikan</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($loans->hasPages())
            <div class="mt-8">
                {{ $loans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    @media print {
        /* Hide elemen yang tidak perlu di print */
        .sidebar-petugas,
        .mt-8,
        .bg-white.rounded-lg.shadow.p-6.mb-6,
        .px-6.py-3 a,
        .px-6.py-4.text-sm a,
        a[href*="cetak"],
        .pagination,
        nav {
            display: none !important;
        }

        /* Set page ke landscape */
        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        /* Styling untuk halaman print */
        * {
            margin: 0 !important;
            padding: 0 !important;
        }

        html, body {
            width: 100%;
            height: 100%;
            background: white;
        }

        .p-6 {
            padding: 5px !important;
        }

        .max-w-7xl {
            max-width: 100% !important;
        }

        .mb-8 {
            margin-bottom: 5px !important;
        }

        .mb-6 {
            margin-bottom: 8px !important;
        }

        .text-3xl {
            font-size: 14px !important;
        }

        .text-gray-600 {
            font-size: 9px !important;
        }

        /* Styling tabel untuk print */
        .bg-white.rounded-lg.shadow.overflow-hidden {
            border: 1px solid #333 !important;
            border-radius: 0 !important;
        }

        table {
            font-size: 8px !important;
            width: 100% !important;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            padding: 3px 2px !important;
            border: 1px solid #333 !important;
            text-align: left;
            word-wrap: break-word;
            overflow: hidden;
        }

        th {
            background-color: #1a3a5c !important;
            color: white !important;
            font-weight: bold;
            font-size: 7px !important;
            padding: 2px 1px !important;
        }

        thead {
            display: table-header-group;
            background-color: #1a3a5c !important;
        }

        tbody tr {
            page-break-inside: avoid;
        }

        /* Sembunyikan tombol aksi (Cetak) di print */
        table td:nth-child(10),
        table th:nth-child(10) {
            display: none !important;
        }

        /* Atur lebar kolom untuk landscape A4 */
        table th:nth-child(1),
        table td:nth-child(1) {
            width: 3% !important;
            min-width: 15px;
        }

        table th:nth-child(2),
        table td:nth-child(2) {
            width: 10% !important;
        }

        table th:nth-child(3),
        table td:nth-child(3) {
            width: 12% !important;
        }

        table th:nth-child(4),
        table td:nth-child(4) {
            width: 5% !important;
        }

        table th:nth-child(5),
        table td:nth-child(5) {
            width: 9% !important;
        }

        table th:nth-child(6),
        table td:nth-child(6) {
            width: 9% !important;
        }

        table th:nth-child(7),
        table td:nth-child(7) {
            width: 9% !important;
        }

        table th:nth-child(8),
        table td:nth-child(8) {
            width: 10% !important;
        }

        table th:nth-child(9),
        table td:nth-child(9) {
            width: 13% !important;
        }

        /* Grid untuk statistik */
        .grid {
            display: grid !important;
            gap: 4px !important;
        }

        .grid.grid-cols-1 {
            grid-template-columns: repeat(4, 1fr) !important;
        }

        .bg-white.rounded-lg.shadow.p-4 {
            padding: 4px !important;
            border: 1px solid #ddd !important;
            page-break-inside: avoid;
        }

        /* Typography */
        h1, .text-3xl {
            font-size: 14px !important;
            font-weight: bold;
            margin-bottom: 2px;
        }

        p {
            margin: 0 !important;
        }

        .text-sm {
            font-size: 8px !important;
        }

        /* Hover effects hilang saat print */
        .hover\:bg-gray-50:hover {
            background-color: transparent !important;
        }

        /* Badge styling */
        span[class*="px-2"] {
            font-size: 7px !important;
            padding: 1px 2px !important;
        }

        /* Warna untuk badge */
        .bg-green-100 {
            background-color: #dcfce7 !important;
        }

        .bg-yellow-100 {
            background-color: #fef3c7 !important;
        }

        .bg-red-100 {
            background-color: #fee2e2 !important;
        }

        .text-green-800 {
            color: #15803d !important;
        }

        .text-yellow-800 {
            color: #854d0e !important;
        }

        .text-red-800 {
            color: #991b1b !important;
        }

        .text-red-600 {
            color: #dc2626 !important;
        }

        .text-green-600 {
            color: #16a34a !important;
        }

        /* Ensure text is visible */
        * {
            color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection
