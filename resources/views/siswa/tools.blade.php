@extends('layouts.siswa')

@section('content')

<!-- Header -->
<div class="mb-8 rounded-lg p-8 shadow-lg" style="background-color: #CDEDEA;">
    <h1 class="text-4xl font-bold drop-shadow-md" style="color: #374151;">
        📦 Daftar Alat
    </h1>
    <p class="mt-2 drop-shadow-sm" style="color: #374151;">
        Lihat daftar alat yang tersedia untuk dipinjam
    </p>
</div>

<!-- Tools List -->
<div class="overflow-x-auto rounded-lg shadow-lg" style="background-color: #DCEBFA;">
    <table class="w-full border-collapse">
        <thead style="background-color: #CDEDEA;">
            <tr>
                <th class="px-4 py-3 text-left" style="color: #374151;">Kode</th>
                <th class="px-4 py-3 text-left" style="color: #374151;">Nama Alat</th>
                <th class="px-4 py-3 text-left" style="color: #374151;">Merk</th>
                <th class="px-4 py-3 text-left" style="color: #374151;">Lokasi</th>
                <th class="px-4 py-3 text-left" style="color: #374151;">Kondisi</th>
                <th class="px-4 py-3 text-left" style="color: #374151;">Jurusan</th>
                <th class="px-4 py-3 text-center" style="color: #374151;">Stok</th>
                <th class="px-4 py-3 text-center" style="color: #374151;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tools as $tool)
            <tr class="border-b" style="background-color: #FFF7E6;">
                <td class="px-4 py-3" style="color: #374151;">{{ $tool->kode_alat }}</td>
                <td class="px-4 py-3 font-semibold" style="color: #374151;">{{ $tool->nama_alat }}</td>
                <td class="px-4 py-3" style="color: #374151;">{{ $tool->merk }}</td>
                <td class="px-4 py-3" style="color: #374151;">{{ $tool->lokasi }}</td>
                <td class="px-4 py-3" style="color: #374151;">{{ $tool->kondisi }}</td>
                <td class="px-4 py-3" style="color: #374151;">{{ $tool->jurusan }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-3 py-1 rounded-full font-semibold text-sm" 
                          style="background-color: {{ $tool->stok > 0 ? '#CDEDEA' : '#EEF4FF' }}; color: #374151;">
                        {{ $tool->stok }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    @if($tool->stok > 0)
                    <a href="{{ route('siswa.loans.create', ['tool_id' => $tool->id]) }}" 
                       class="inline-block px-3 py-1 rounded text-sm font-semibold transition"
                       style="background-color: #CDEDEA; color: #374151;">
                        Pinjam
                    </a>
                    @else
                    <span class="inline-block px-3 py-1 rounded text-sm font-semibold" style="background-color: #EEF4FF; color: #374151;">
                        Habis
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-8" style="color: #374151;">
                    <p class="text-lg font-semibold">Tidak ada alat tersedia</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
