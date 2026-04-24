@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-6" style="color: #374151;">
    Approval Peminjaman
</h1>

<div class="overflow-x-auto rounded shadow"
     style="background-color: #FFF7E6;">

<table class="w-full border">

    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2">Nama</th>
            <th class="px-4 py-2">Buku</th>
            <th class="px-4 py-2">Jumlah</th>
            <th class="px-4 py-2">Tanggal</th>
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
                {{ $loan->tanggal_pinjam }}
            </td>

            <td class="px-4 py-2 space-x-2">

                <!-- APPROVE -->
                <form action="{{ route('admin.loans.approve', $loan->id) }}"
                      method="POST" class="inline">
                    @csrf
                    <button class="px-3 py-1 bg-green-200 rounded text-sm">
                        ✔ Approve
                    </button>
                </form>

                <!-- REJECT -->
                <form action="{{ route('admin.loans.reject', $loan->id) }}"
                      method="POST" class="inline">
                    @csrf
                    <button class="px-3 py-1 bg-red-200 rounded text-sm">
                        ✖ Reject
                    </button>
                </form>

            </td>

        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-4">
                Tidak ada data
            </td>
        </tr>
        @endforelse
    </tbody>

</table>
</div>

@endsection