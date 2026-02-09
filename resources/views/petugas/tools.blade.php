@extends('layouts.petugas')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">📦 Daftar Alat</h1>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
    <table class="w-full border">
        <thead style="background-color: #DCEBFA;">
            <tr>
                <th class="px-4 py-2" style="color: #374151;">No</th>
                <th class="px-4 py-2" style="color: #374151;">Nama Alat</th>
                <th class="px-4 py-2" style="color: #374151;">Kategori</th>
                <th class="px-4 py-2" style="color: #374151;">Stok</th>
                <th class="px-4 py-2" style="color: #374151;">Jurusan</th>
                <th class="px-4 py-2" style="color: #374151;">Tanggal Input</th>
                <th class="px-4 py-2" style="color: #374151;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tools as $index => $tool)
            <tr class="border-b text-center" style="background-color: #FFF7E6;">
                <td class="px-4 py-2">{{ $index + 1 }}</td>
                <td class="px-4 py-2" style="color: #374151;">{{ $tool->nama_alat ?? '-' }}</td>
                <td class="px-4 py-2" style="color: #374151;">{{ $tool->category->nama_kategori ?? '-' }}</td>
                <td class="px-4 py-2">
                    @if($tool->stok > 10)
                        <span class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #DCFCE7; color: #166534;">{{ $tool->stok }} unit</span>
                    @elseif($tool->stok > 0)
                        <span class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #FEF3C7; color: #92400E;">{{ $tool->stok }} unit</span>
                    @else
                        <span class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #FEE2E2; color: #991B1B;">Habis</span>
                    @endif
                </td>
                <td class="px-4 py-2" style="color: #374151;">{{ $tool->jurusan ?? '-' }}</td>
                <td class="px-4 py-2" style="color: #374151;">{{ $tool->tanggal ? $tool->tanggal->format('d M Y') : '-' }}</td>
                <td class="px-4 py-2">
                    @if($tool->stok > 0)
                        <span class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #CDEDEA; color: #374151;">Tersedia</span>
                    @else
                        <span class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #FCA5A5; color: #7F1D1D;">Tidak Tersedia</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr class="border-b">
                <td colspan="7" class="px-4 py-2 text-center" style="color: #6B7280;">Tidak ada data alat</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
    <div class="rounded-lg shadow-lg p-6" style="background-color: #DCEBFA;">
        <p class="text-sm font-semibold" style="color: #374151;">Total Alat</p>
        <p class="text-3xl font-bold mt-2" style="color: #3B82F6;">{{ $tools->count() }}</p>
    </div>
    
    <div class="rounded-lg shadow-lg p-6" style="background-color: #DCFCE7;">
        <p class="text-sm font-semibold" style="color: #374151;">Total Stok</p>
        <p class="text-3xl font-bold mt-2" style="color: #059669;">{{ $tools->sum('stok') }} unit</p>
    </div>
    
    <div class="rounded-lg shadow-lg p-6" style="background-color: #FEE2E2;">
        <p class="text-sm font-semibold" style="color: #374151;">Stok Habis</p>
        <p class="text-3xl font-bold mt-2" style="color: #991B1B;">{{ $tools->where('stok', 0)->count() }}</p>
    </div>
</div>
@endsection
