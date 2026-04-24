@extends('layouts.admin')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div class="flex items-center gap-4">
        <div class="text-3xl">
            📜
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                Log Aktivitas
            </h1>
            <p class="text-[12px] text-gray-400">
                Memantau setiap tindakan yang dilakukan di sistem
            </p>
        </div>
    </div>
</div>

<!-- CARD TABLE -->
<div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">

    <!-- TITLE BAR -->
    <div class="p-4 border-b border-gray-50 bg-gray-50/30">
        <h2 class="font-bold text-gray-400 text-[11px] uppercase tracking-widest px-1">
            Riwayat Aktivitas
        </h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-[13px]">
            <thead class="bg-white text-gray-400 text-[10px] uppercase tracking-widest border-b border-gray-50">
                <tr>
                    <th class="p-4 text-left font-bold">Waktu</th>
                    <th class="p-4 text-left font-bold">Pengguna</th>
                    <th class="p-4 text-left font-bold">Aksi</th>
                    <th class="p-4 text-left font-bold">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="p-4 text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('d/m/y, H:i') }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-[10px] border border-blue-100">
                                    {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-bold text-gray-700">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                @if(in_array($log->action, ['PEMINJAMAN', 'TAMBAH_BUKU'])) bg-green-50 text-green-600 border border-green-100
                                @elseif(in_array($log->action, ['REJECTION', 'HAPUS_BUKU', 'DELETE'])) bg-red-50 text-red-600 border border-red-100
                                @elseif(in_array($log->action, ['APPROVAL', 'EDIT_BUKU', 'UPDATE'])) bg-blue-50 text-blue-600 border border-blue-100
                                @else bg-gray-50 text-gray-500 border border-gray-100 @endif">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-500">
                            {{ $log->description }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-gray-300">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-4xl">📂</span>
                                <span class="text-sm font-medium">Belum ada aktivitas</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection