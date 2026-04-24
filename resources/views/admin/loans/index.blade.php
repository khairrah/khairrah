@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-6" style="color: #374151;">
    Data Peminjaman Buku
</h1>

@if(session('success'))
    <div class="mb-4 p-3 rounded" style="background-color:#CDEDEA; color:#374151;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-3 rounded bg-red-100 text-red-600">
        {{ session('error') }}
    </div>
@endif

{{-- 🔥 DIMATIKAN (biar tidak muncul tapi kode tetap ada) --}}
{{-- 
<a href="{{ route('admin.loans.create') }}"
   class="inline-block mb-4 px-4 py-2 rounded font-semibold"
   style="background-color: #CDEDEA; color: #374151;">
    + Pinjam Buku
</a>
--}}

<div class="overflow-x-auto rounded shadow"
     style="background-color: #FFF7E6;">

<table class="w-full border">

    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2">Nama</th>
            <th class="px-4 py-2">Judul Buku</th>
            <th class="px-4 py-2">Jumlah</th>
            <th class="px-4 py-2">Tanggal</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($loans as $loan)
        <tr class="text-center border-t">

            <td class="px-4 py-2">
                {{ $loan->user->name ?? '-' }}
            </td>

            <td class="px-4 py-2">
                {{ $loan->book->judul ?? '-' }}
            </td>

            <td class="px-4 py-2">
                {{ $loan->jumlah }}
            </td>

            <td class="px-4 py-2">
                {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d-m-Y') }}
            </td>

            <td class="px-4 py-2">
                @if($loan->status == 'pending')
                    <span class="px-2 py-1 bg-yellow-100 rounded text-sm">Pending</span>
                @elseif($loan->status == 'approved')
                    <span class="px-2 py-1 bg-green-100 rounded text-sm">Approved</span>
                @elseif($loan->status == 'rejected')
                    <span class="px-2 py-1 bg-red-100 rounded text-sm">Rejected</span>
                @endif
            </td>

            <td class="px-4 py-2 space-x-2">

                {{-- ✅ APPROVE --}}
                @if($loan->status == 'pending')
                <form action="{{ route('admin.loans.approve', $loan->id) }}"
                      method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-3 py-1 rounded text-sm"
                            style="background-color:#BBF7D0;">
                        ✔ Approve
                    </button>
                </form>
                @endif

                {{-- ✅ RETURN --}}
                @if($loan->status == 'approved')
                <form action="{{ route('admin.loans.return', $loan->id) }}"
                      method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-3 py-1 rounded text-sm"
                            style="background-color:#BFDBFE;">
                        ↩ Kembali
                    </button>
                </form>
                @endif

            </td>

        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center py-4">
                Tidak ada data
            </td>
        </tr>
        @endforelse
    </tbody>

</table>
</div>

@endsection