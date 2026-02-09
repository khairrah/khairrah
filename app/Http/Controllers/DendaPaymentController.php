<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\DendaPayment;
use App\Helpers\ActivityHelper;
use Illuminate\Http\Request;

class DendaPaymentController extends Controller
{
    /**
     * Tampilkan daftar denda yang perlu dibayar
     */
    public function index()
    {
        $payments = DendaPayment::whereHas('loan', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Hitung total denda yang belum lunas
        // 1. Dari DendaPayment yang menunggu_verifikasi (sudah dibayar tapi belum diverifikasi)
        $totalDendaMenungguVerifikasi = DendaPayment::whereHas('loan', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->where('status', 'menunggu_verifikasi')
            ->sum('sisa_denda');

        // 2. Dari Loan yang menunggu_pembayaran (belum dibayar sama sekali)
        $totalDendaMenungguPembayaran = Loan::where('user_id', auth()->id())
            ->where('denda_status', 'menunggu_pembayaran')
            ->sum('denda');

        // Total keseluruhan
        $totalDendaBelumLunas = $totalDendaMenungguVerifikasi + $totalDendaMenungguPembayaran;

        return view('siswa.denda-payments.index', compact('payments', 'totalDendaBelumLunas'));
    }

    /**
     * Form bayar denda untuk loan tertentu
     */
    public function create($loanId)
    {
        $loan = Loan::findOrFail($loanId);

        // Authorize - hanya siswa pemilik loan
        if ($loan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if denda exists
        if (!$loan->denda || $loan->denda <= 0) {
            return redirect()->route('siswa.loans.index')->with('error', 'Tidak ada denda untuk pinjaman ini');
        }

        // Check jika sudah ada pembayaran pending
        $pendingPayment = DendaPayment::where('loan_id', $loanId)
            ->where('status', 'menunggu_verifikasi')
            ->first();

        if ($pendingPayment) {
            return redirect()->route('siswa.denda-payments.show', $pendingPayment->id)
                ->with('warning', 'Anda sudah memiliki pembayaran denda yang menunggu verifikasi');
        }

        return view('siswa.denda-payments.create', compact('loan'));
    }

    /**
     * Simpan pembayaran denda
     */
    public function store(Request $request, $loanId)
    {
        $loan = Loan::findOrFail($loanId);

        // Authorize
        if ($loan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1000|max:' . ($loan->denda + 1000),
            'metode_pembayaran' => 'required|in:tunai,transfer',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $jumlahDenda = $loan->denda;
        $jumlahBayar = (float) str_replace('.', '', $validated['jumlah_bayar']); // Remove formatting
        $sisaDenda = max(0, $jumlahDenda - $jumlahBayar);

        // Handle bukti pembayaran
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('denda-payments/' . date('Y/m/d'), 'public');
        }

        // Create payment record
        $payment = DendaPayment::create([
            'loan_id' => $loanId,
            'jumlah_denda' => $jumlahDenda,
            'jumlah_bayar' => $jumlahBayar,
            'sisa_denda' => $sisaDenda,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'bukti_pembayaran' => $buktiPath,
            'status' => 'menunggu_verifikasi',
            'tanggal_pembayaran' => now(),
        ]);

        // Update loan status denda menjadi menunggu_verifikasi
        $loan->update([
            'denda_status' => 'menunggu_verifikasi'
        ]);

        // Log activity
        ActivityHelper::log(
            auth()->user(),
            'Mengajukan pembayaran denda',
            'Siswa mengajukan pembayaran denda Rp' . number_format($jumlahBayar, 0, ',', '.'),
            'create',
            'DendaPayment',
            $payment->id
        );

        return redirect()->route('siswa.denda-payments.show', $payment->id)
            ->with('success', 'Pembayaran denda berhasil diajukan. Menunggu verifikasi petugas.');
    }

    /**
     * Detail pembayaran denda
     */
    public function show(DendaPayment $dendaPayment)
    {
        // Authorize
        if ($dendaPayment->loan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('siswa.denda-payments.show', compact('dendaPayment'));
    }

    /**
     * Petugas verifikasi pembayaran
     */
    public function verify(Request $request, DendaPayment $dendaPayment)
    {
        // Authorize - hanya petugas
        if (auth()->user()->role !== 'petugas') {
            abort(403, 'Hanya petugas yang dapat memverifikasi');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        // If action == reject, require catatan server-side
        if ($validated['action'] === 'reject' && empty(trim($validated['catatan'] ?? ''))) {
            return redirect()->back()->withErrors(['catatan' => 'Catatan penolakan harus diisi'])->withInput();
        }

        if ($validated['action'] === 'approve') {
            $dendaPayment->markAsVerified(auth()->id());

            // Update loan denda_status menjadi lunas jika sisa_denda sudah 0
            if ($dendaPayment->sisa_denda <= 0) {
                $dendaPayment->loan->update([
                    'denda_status' => 'lunas',
                    'denda' => 0
                ]);
            } else {
                // Jika masih ada sisa denda, status kembali ke menunggu_pembayaran
                $dendaPayment->loan->update([
                    'denda_status' => 'menunggu_pembayaran',
                    'denda' => $dendaPayment->sisa_denda
                ]);
            }

            ActivityHelper::log(
                auth()->user(),
                'Verifikasi pembayaran denda',
                'Petugas memverifikasi pembayaran denda dari ' . $dendaPayment->loan->user->name,
                'update',
                'DendaPayment',
                $dendaPayment->id
            );

            return redirect()->back()->with('success', 'Pembayaran denda berhasil diverifikasi');
        } else {
            $dendaPayment->markAsRejected($validated['catatan'] ?? '', auth()->id());

            // Jika pembayaran ditolak, kembali ke status menunggu_pembayaran
            $dendaPayment->loan->update([
                'denda_status' => 'menunggu_pembayaran'
            ]);

            ActivityHelper::log(
                auth()->user(),
                'Menolak pembayaran denda',
                'Petugas menolak pembayaran denda dari ' . $dendaPayment->loan->user->name,
                'update',
                'DendaPayment',
                $dendaPayment->id
            );

            return redirect()->back()->with('success', 'Pembayaran denda berhasil ditolak');
        }

        if ($validated['action'] === 'approve') {
            // Simpan perubahan langsung untuk memastikan perubahan status tidak hilang
            $dendaPayment->update([
                'status' => 'terverifikasi',
                'tanggal_verifikasi' => now(),
                'petugas_verifikasi_id' => auth()->id(),
            ]);

            // Update loan denda_status menjadi lunas jika sisa_denda sudah 0
            $loan = $dendaPayment->loan()->first();
            if ($loan) {
                if ($dendaPayment->sisa_denda <= 0) {
                    $loan->update([
                        'denda_status' => 'lunas',
                        'denda' => 0
                    ]);
                } else {
                    // Jika masih ada sisa denda, status kembali ke menunggu_pembayaran
                    $loan->update([
                        'denda_status' => 'menunggu_pembayaran',
                        'denda' => $dendaPayment->sisa_denda
                    ]);
                }
            } else {
                // fallback: jika relasi loan tidak tersedia, update via query
                if ($dendaPayment->sisa_denda <= 0) {
                    \App\Models\Loan::where('id', $dendaPayment->loan_id)->update(['denda_status' => 'lunas', 'denda' => 0]);
                } else {
                    \App\Models\Loan::where('id', $dendaPayment->loan_id)->update(['denda_status' => 'menunggu_pembayaran', 'denda' => $dendaPayment->sisa_denda]);
                }
            }

            $siswaName = $loan?->user->name ?? \App\Models\Loan::where('id', $dendaPayment->loan_id)->with('user')->first()?->user->name ?? 'Siswa';

            ActivityHelper::log(
                auth()->user(),
                'Verifikasi pembayaran denda',
                'Petugas memverifikasi pembayaran denda dari ' . $siswaName,
                'update',
                'DendaPayment',
                $dendaPayment->id
            );

            return redirect()->back()->with('success', 'Pembayaran denda berhasil diverifikasi');
        } else {
            $dendaPayment->markAsRejected($validated['catatan'] ?? '', auth()->id());

            // Jika pembayaran ditolak, kembali ke status menunggu_pembayaran
            $loan = $dendaPayment->loan()->first();
            if ($loan) {
                $loan->update(['denda_status' => 'menunggu_pembayaran']);
                $siswaName = $loan->user->name ?? 'Siswa';
            } else {
                \App\Models\Loan::where('id', $dendaPayment->loan_id)->update(['denda_status' => 'menunggu_pembayaran']);
                $siswaName = \App\Models\Loan::where('id', $dendaPayment->loan_id)->with('user')->first()?->user->name ?? 'Siswa';
            }

            ActivityHelper::log(
                auth()->user(),
                'Menolak pembayaran denda',
                'Petugas menolak pembayaran denda dari ' . $siswaName,
                'update',
                'DendaPayment',
                $dendaPayment->id
            );

            return redirect()->back()->with('success', 'Pembayaran denda berhasil ditolak');
        }
    }

    /**
     * Ajukan pembayaran otomatis untuk semua loan siswa yang memiliki denda (Bayar Sekarang)
     */
    public function payNow(Request $request)
    {
        // Hanya siswa
        if (auth()->user()->role !== 'siswa') {
            abort(403, 'Unauthorized');
        }

        $loans = Loan::where('user_id', auth()->id())
            ->where('denda', '>', 0)
            ->where('denda_status', 'menunggu_pembayaran')
            ->get();

        if ($loans->isEmpty()) {
            return redirect()->route('siswa.denda-payments.index')->with('error', 'Tidak ada denda untuk dibayar');
        }

        foreach ($loans as $loan) {
            // Jika sudah ada pembayaran pending, lewati
            $existing = DendaPayment::where('loan_id', $loan->id)->where('status', 'menunggu_verifikasi')->first();
            if ($existing) {
                $loan->update(['denda_status' => 'menunggu_verifikasi']);
                continue;
            }

            $payment = DendaPayment::create([
                'loan_id' => $loan->id,
                'jumlah_denda' => $loan->denda,
                'jumlah_bayar' => $loan->denda,
                'sisa_denda' => 0,
                'metode_pembayaran' => 'tunai',
                'status' => 'menunggu_verifikasi',
                'tanggal_pembayaran' => now(),
            ]);

            $loan->update(['denda_status' => 'menunggu_verifikasi']);

            ActivityHelper::log(
                auth()->user(),
                'Mengajukan pembayaran denda',
                'Siswa mengajukan pembayaran denda untuk pinjaman #' . $loan->id,
                'create',
                'DendaPayment',
                $payment->id
            );
        }

        return redirect()->route('siswa.denda-payments.index')->with('success', 'Pembayaran denda diajukan. Menunggu verifikasi petugas.');
    }

    /**
     * Cetak Bukti Pembayaran Denda (PDF)
     */
    public function cetakBuktiPembayaran(DendaPayment $dendaPayment)
    {
        // Authorize - hanya siswa pemilik atau petugas
        if ($dendaPayment->loan->user_id !== auth()->id() && auth()->user()->role !== 'petugas') {
            abort(403, 'Unauthorized');
        }

        $data = [
            'payment' => $dendaPayment,
            'tanggal_cetak' => now(),
        ];
        $pdf = \PDF::loadView('pdf.bukti-pembayaran', $data);
        return $pdf->download('Bukti-Pembayaran-' . $dendaPayment->id . '.pdf');
    }

    /**
     * Petugas view pembayaran denda untuk diverifikasi
     */
    public function pendingList()
    {
        $pendingPayments = DendaPayment::where('status', 'menunggu_verifikasi')
            ->with(['loan.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas.verify-denda-payments', compact('pendingPayments'));
    }

    /**
     * Petugas menandai denda sebagai lunas secara tunai (langsung di petugas)
     */

}
