@extends('layouts.petugas')

@section('content')
<div class="p-6" style="background-color: #FFF7E6;">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Pembayaran Denda</h1>
            <p class="text-gray-600">Periksa dan verifikasi pembayaran denda dari siswa</p>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-2 border-green-200 rounded-lg text-green-800">
                ✓ {{ session('success') }}
            </div>
        @endif

        <!-- Tabel Pembayaran Pending -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if ($pendingPayments->count() > 0)
                <table class="w-full">
                    <thead style="background: linear-gradient(90deg,#CDEDEA 0%,#A7EFD9 100%); color: #374151;">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Siswa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Alat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah Denda</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah Bayar</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Metode</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($pendingPayments as $payment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $payment->loan->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->loan->tool->nama }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-red-600">Rp {{ number_format($payment->jumlah_denda, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-blue-600">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm capitalize">{{ $payment->metode_pembayaran }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->tanggal_pembayaran?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                        <button onclick="openVerifyModal({{ $payment->id }}, '{{ $payment->loan->user->name }}', '{{ $payment->bukti_pembayaran }}')"
                                            class="px-3 py-2 text-gray-800 text-xs font-semibold rounded-lg shadow transition" style="background: linear-gradient(90deg,#CDEDEA 0%,#A7EFD9 100%);">Periksa</button>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-8 text-center text-gray-500">
                    <p>✓ Tidak ada pembayaran yang menunggu verifikasi</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($pendingPayments->hasPages())
            <div class="mt-8">
                {{ $pendingPayments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Verifikasi (Improved) -->
<div id="verifyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" aria-hidden="true">
    <!-- Backdrop -->
    <div id="verifyBackdrop" class="absolute inset-0 bg-black bg-opacity-45 transition-opacity"></div>

    <!-- Panel -->
    <div id="verifyPanel" class="relative bg-white rounded-xl shadow-xl max-w-md w-full transform transition-all duration-200 ease-out opacity-0 scale-95" role="dialog" aria-modal="true" aria-labelledby="verifyTitle">
        <div class="px-6 py-4 rounded-t-xl text-gray-800" style="background: linear-gradient(90deg,#CDEDEA 0%,#A7EFD9 100%);">
            <div class="flex items-center gap-3 w-full">
                <svg class="w-6 h-6 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.866 0-7 1.79-7 4v4h14v-4c0-2.21-3.134-4-7-4z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v6"></path></svg>
                <div class="flex items-center justify-between w-full">
                    <div>
                        <h3 id="verifyTitle" class="text-lg font-bold">Verifikasi Pembayaran</h3>
                        <p id="siswaName" class="text-gray-700 text-sm mt-1">Dari: -</p>
                    </div>
                    <span class="ml-3 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">Baru</span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Preview Bukti -->
            <div id="buktiPreview" class="mb-4 bg-gray-50 p-3 rounded-lg" style="display:none;">
                <p class="text-sm text-gray-600 mb-2">Preview Bukti Pembayaran:</p>
                <img id="buktiImage" src="" alt="Bukti Pembayaran" class="w-full h-auto rounded-md">
            </div>

            <p class="text-gray-700 mb-4"><strong>Apakah Anda yakin pembayaran ini valid?</strong></p>

            <form id="verifyForm" method="POST">
                @csrf
                <input type="hidden" name="action" id="actionInput" value="">

                <div id="catatanContainer" class="mb-4" style="display:none;">
                    <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Penolakan</label>
                    <textarea name="catatan" id="catatan" rows="3" class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Contoh: Bukti tidak jelas"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" id="verifyAcceptBtn" class="flex-1 text-gray-800 font-semibold py-2 rounded-lg shadow" style="background: linear-gradient(90deg,#CDEDEA 0%,#A7EFD9 100%);">✓ Terima</button>
                    <button type="button" id="verifyRejectBtn" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg shadow">✗ Tolak</button>
                    <button type="button" id="verifyCancelBtn" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 rounded-lg">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentPaymentId = null;
    const verifyModal = document.getElementById('verifyModal');
    const verifyPanel = document.getElementById('verifyPanel');
    const verifyBackdrop = document.getElementById('verifyBackdrop');

    function openVerifyModal(paymentId, siswaName, buktiPembayaran) {
        currentPaymentId = paymentId;
        document.getElementById('siswaName').textContent = 'Dari: ' + siswaName;
        document.getElementById('actionInput').value = '';
        document.getElementById('catatan').value = '';
        document.getElementById('catatanContainer').style.display = 'none';

        document.getElementById('verifyForm').action = `/petugas/denda-payments/${paymentId}/verify`;

        if (buktiPembayaran) {
            document.getElementById('buktiPreview').style.display = 'block';
            document.getElementById('buktiImage').src = `/storage/${buktiPembayaran}`;
        } else {
            document.getElementById('buktiPreview').style.display = 'none';
        }

        // show modal with animation
        verifyModal.classList.remove('hidden');
        verifyModal.setAttribute('aria-hidden', 'false');
        requestAnimationFrame(() => {
            verifyPanel.classList.remove('opacity-0', 'scale-95');
            verifyPanel.classList.add('opacity-100', 'scale-100');
        });
    }

    function hideVerifyModal() {
        verifyPanel.classList.remove('opacity-100', 'scale-100');
        verifyPanel.classList.add('opacity-0', 'scale-95');
        verifyPanel.addEventListener('transitionend', function onEnd() {
            verifyPanel.removeEventListener('transitionend', onEnd);
            verifyModal.classList.add('hidden');
            verifyModal.setAttribute('aria-hidden', 'true');
        });
    }

    document.getElementById('verifyAcceptBtn')?.addEventListener('click', function() {
        document.getElementById('actionInput').value = 'approve';
        document.getElementById('verifyForm').submit();
    });

    document.getElementById('verifyRejectBtn')?.addEventListener('click', function() {
        // show catatan field and require input
        document.getElementById('catatanContainer').style.display = 'block';
        const cat = document.getElementById('catatan');
        if (!cat.value.trim()) {
            cat.focus();
            return;
        }
        document.getElementById('actionInput').value = 'reject';
        document.getElementById('verifyForm').submit();
    });

    document.getElementById('verifyCancelBtn')?.addEventListener('click', hideVerifyModal);

    // Click outside to close
    verifyModal?.addEventListener('click', function(e) {
        if (e.target === verifyModal || e.target === verifyBackdrop) hideVerifyModal();
    });

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') hideVerifyModal();
    });
</script>

@endsection
