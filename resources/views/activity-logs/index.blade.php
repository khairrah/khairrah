@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Log Aktivitas</h1>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2" style="color: #374151;">No</th>
            <th class="px-4 py-2" style="color: #374151;">User</th>
            <th class="px-4 py-2" style="color: #374151;">Aksi</th>
            <th class="px-4 py-2" style="color: #374151;">Keterangan</th>
            <th class="px-4 py-2" style="color: #374151;">Waktu</th>
        </tr>
    </thead>
    <tbody>
        @forelse($logs as $index => $log)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $log->user->name ?? 'User Dihapus' }}</td>
            <td class="px-4 py-2">
                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded font-semibold text-sm">{{ $log->action }}</span>
            </td>
            <td class="px-4 py-2">{{ $log->description ?? '-' }}</td>
            <td class="px-4 py-2 text-sm" style="color: #6B7280;">{{ $log->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @empty
        <tr class="border-b">
            <td colspan="5" class="px-4 py-2 text-center" style="color: #6B7280;">Belum ada log aktivitas</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

@endsection
