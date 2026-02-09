@extends('layouts.siswa')

@section('content')

<!-- Header -->
<div class="mb-8 rounded-lg p-8 shadow-lg" style="background-color: #CDEDEA;">
    <h1 class="text-4xl font-bold drop-shadow-md" style="color: #374151;">
        Halo, {{ auth()->user()->name }}! 👋
    </h1>
    <p class="mt-2 drop-shadow-sm" style="color: #374151;">
        Selamat datang di sistem Inventaris
    </p>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Peminjaman Aktif -->
    <div class="rounded-lg shadow-lg p-6" style="background-color: #FFF1E6;">
        <p class="text-sm font-semibold" style="color: #374151;">📦 Peminjaman Aktif</p>
        <p class="text-3xl font-bold mt-2" style="color: #374151;">
            {{ \App\Models\Loan::where('user_id', auth()->id())
                ->whereNull('tanggal_kembali')
                ->count() }}
        </p>
    </div>

    <!-- Total Peminjaman -->
    <div class="rounded-lg shadow-lg p-6" style="background-color: #FFF1E6;">
        <p class="text-sm font-semibold" style="color: #374151;">📊 Total Peminjaman</p>
        <p class="text-3xl font-bold mt-2" style="color: #374151;">
            {{ \App\Models\Loan::where('user_id', auth()->id())->count() }}
        </p>
    </div>

    <!-- Sudah Dikembalikan -->
    <div class="rounded-lg shadow-lg p-6" style="background-color: #FFF1E6;">
        <p class="text-sm font-semibold" style="color: #374151;">✅ Sudah Dikembalikan</p>
        <p class="text-3xl font-bold mt-2" style="color: #374151;">
            {{ \App\Models\Loan::where('user_id', auth()->id())
                ->whereNotNull('tanggal_kembali')
                ->count() }}
        </p>
    </div>

    <!-- Total Denda -->
    @php
        // Hitung total denda yang masih harus dibayar (tidak termasuk yang sudah lunas)
        // 1. Dari DendaPayment yang menunggu_verifikasi (sudah dibayar tapi belum diverifikasi)
        $totalDendaMenungguVerifikasi = \App\Models\DendaPayment::whereHas('loan', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->where('status', 'menunggu_verifikasi')
            ->sum('sisa_denda');

        // 2. Dari Loan yang menunggu_pembayaran (belum dibayar sama sekali)
        $totalDendaMenungguPembayaran = \App\Models\Loan::where('user_id', auth()->id())
            ->where('denda_status', 'menunggu_pembayaran')
            ->sum('denda');

        // Total denda yang masih aktif (belum lunas)
        $totalDenda = $totalDendaMenungguVerifikasi + $totalDendaMenungguPembayaran;
    @endphp
    <div class="rounded-lg shadow-lg p-6" style="background-color: {{ $totalDenda > 0 ? '#FEE2E2' : '#DCFCE7' }};">
        <p class="text-sm font-semibold" style="color: #374151;">💰 Total Denda</p>
        <p class="text-3xl font-bold mt-2" style="color: {{ $totalDenda > 0 ? '#991B1B' : '#166534' }};">
            Rp {{ number_format($totalDenda, 0, ',', '.') }}
        </p>
        @if ($totalDenda > 0)
            <form method="POST" action="{{ route('siswa.denda-payments.pay-now') }}" id="payNowForm">
                @csrf
                <button type="button" id="openPayNowModalBtn" class="text-sm mt-2 inline-block px-4 py-2 rounded font-semibold shadow" style="background: linear-gradient(90deg,#1e40af 0%,#4f46e5 100%); color: white;">
                    Bayar Sekarang
                </button>
            </form>

            <!-- Modal Konfirmasi Bayar Sekarang (Improved) -->
            <div id="payNowModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" aria-hidden="true">
                <!-- Backdrop -->
                <div id="payNowBackdrop" class="absolute inset-0 bg-black bg-opacity-45 transition-opacity"></div>

                <!-- Modal Panel -->
                <div id="payNowPanel" class="relative bg-white rounded-xl shadow-xl max-w-md w-full transform transition-all duration-200 ease-out opacity-0 scale-95" role="dialog" aria-modal="true" aria-labelledby="payNowTitle">
                    <div class="px-6 py-4 rounded-t-xl text-gray-800" style="background: linear-gradient(90deg,#CDEDEA 0%,#A7EFD9 100%);">
                        <div class="flex items-center gap-3 w-full">
                            <!-- Icon -->
                            <svg class="w-6 h-6 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.866 0-7 1.79-7 4v4h14v-4c0-2.21-3.134-4-7-4z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v6"></path></svg>
                            <div class="flex items-center justify-between w-full">
                                <div>
                                    <h3 id="payNowTitle" class="text-lg font-bold">Ajukan Pembayaran Denda</h3>
                                    <p class="text-gray-700 text-sm mt-1">Pembayaran akan diajukan dan menunggu verifikasi petugas</p>
                                </div>
                                <span class="ml-3 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">Baru</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 mb-4">Apakah Anda ingin mengajukan pembayaran denda untuk semua pinjaman yang memiliki denda? Setelah diajukan, petugas akan memverifikasi pembayaran Anda di halaman <strong>Verifikasi Denda</strong>.</p>

                        <div class="flex gap-3">
                            <button type="button" id="confirmPayNowBtn" class="flex-1 text-gray-800 font-semibold py-2 rounded-lg shadow" style="background: linear-gradient(90deg,#CDEDEA 0%,#A7EFD9 100%);">Ya, Ajukan</button>
                            <button type="button" id="cancelPayNowBtn" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <script>
            const openBtn = document.getElementById('openPayNowModalBtn');
            const modal = document.getElementById('payNowModal');
            const panel = document.getElementById('payNowPanel');
            const backdrop = document.getElementById('payNowBackdrop');
            const confirmBtn = document.getElementById('confirmPayNowBtn');
            const cancelBtn = document.getElementById('cancelPayNowBtn');

            function showModal() {
                if (!modal || !panel) return;
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
                // Trigger animation
                requestAnimationFrame(() => {
                    panel.classList.remove('opacity-0', 'scale-95');
                    panel.classList.add('opacity-100', 'scale-100');
                });
            }

            function hideModal() {
                if (!modal || !panel) return;
                panel.classList.remove('opacity-100', 'scale-100');
                panel.classList.add('opacity-0', 'scale-95');
                panel.addEventListener('transitionend', function onEnd() {
                    panel.removeEventListener('transitionend', onEnd);
                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                });
            }

            openBtn?.addEventListener('click', showModal);
            cancelBtn?.addEventListener('click', hideModal);
            confirmBtn?.addEventListener('click', function() {
                document.getElementById('payNowForm').submit();
            });

            // Click outside to close
            modal?.addEventListener('click', function(e) {
                if (e.target === modal || e.target === backdrop) hideModal();
            });

            // Close on Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') hideModal();
            });
        </script>
    </div>
</div>

<!-- Peminjaman Aktif -->
<div class="rounded-lg shadow-lg p-6 mb-8" style="background-color: #DCEBFA;">
    <h2 class="text-2xl font-bold mb-4" style="color: #374151;">📋 Peminjaman Saya</h2>

    @php
        $myLoans = \App\Models\Loan::where('user_id', auth()->id())
            ->whereNull('tanggal_kembali')
            ->with('tool')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();
    @endphp

    @if($myLoans->count())
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead style="background-color: #CDEDEA;">
                    <tr>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Alat</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Jumlah</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Tanggal Pinjam</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myLoans as $loan)
                    <tr class="border-b hover:" style="background-color: #FFF7E6;">
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tool->nama_alat ?? '-' }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->jumlah }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tanggal_pinjam }}</td>
                        <td class="px-4 py-2">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #CDEDEA; color: #374151;">Dipinjam</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8" style="color: #374151;">
            <p class="text-lg">Tidak ada peminjaman aktif</p>
            <p class="text-sm mt-2">Anda dapat memulai peminjaman alat melalui halaman peminjaman</p>
        </div>
    @endif
</div>

<!-- Riwayat Peminjaman -->
<div class="rounded-lg shadow-lg p-6" style="background-color: #DCEBFA;">
    <h2 class="text-2xl font-bold mb-4" style="color: #374151;">📚 Riwayat Peminjaman</h2>

    @php
        $historyLoans = \App\Models\Loan::where('user_id', auth()->id())
            ->whereNotNull('tanggal_kembali')
            ->with('tool')
            ->orderBy('tanggal_kembali', 'desc')
            ->get();
    @endphp

    @if($historyLoans->count())
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead style="background-color: #CDEDEA;">
                    <tr>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Alat</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Jumlah</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Tanggal Pinjam</th>
                        <th class="px-4 py-2 text-left" style="color: #374151;">Tanggal Kembali</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyLoans as $loan)
                    <tr class="border-b" style="background-color: #FFF7E6;">
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tool->nama_alat ?? '-' }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->jumlah }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tanggal_pinjam }}</td>
                        <td class="px-4 py-2" style="color: #374151;">{{ $loan->tanggal_kembali }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8" style="color: #374151;">
            <p class="text-lg">Belum ada riwayat peminjaman</p>
        </div>
    @endif
</div>

@endsection
