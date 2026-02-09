<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use App\Helpers\DendaHelper;
use App\Helpers\ActivityHelper;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    // Halaman Daftar Alat
    public function toolsIndex()
    {
        $tools = Tool::all();
        return view('petugas.tools', compact('tools'));
    }

    // Halaman Setujui Peminjaman
    public function approveLoanIndex()
    {
        // Hanya tampilkan loan dengan status pending atau approved (exclude returned)
        $loans = Loan::with(['user', 'tool'])
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('petugas.approve-loans', compact('loans'));
    }

    // Setujui Peminjaman + Set Deadline
    public function approveLoan(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'durasi_hari' => 'required|integer|min:1|max:90'
        ]);

        // Set deadline berdasarkan durasi (cast ke int)
        $durasi_hari = (int) $validated['durasi_hari'];
        $tanggal_pinjam = \Carbon\Carbon::parse($loan->tanggal_pinjam);
        $tanggal_kembali_target = $tanggal_pinjam->copy()->addDays($durasi_hari);

        $loan->update([
            'status' => 'approved',
            'tanggal_kembali_target' => $tanggal_kembali_target
        ]);

        // Update stok alat
        $loan->tool->stok -= $loan->jumlah;
        $loan->tool->save();

        ActivityHelper::log(
            auth()->user(),
            'Menyetujui peminjaman alat',
            "Setujui peminjaman {$loan->tool->nama_alat} ({$durasi_hari} hari) sampai " . $tanggal_kembali_target->format('d M Y'),
            'update',
            'Loan',
            $loan->id
        );

        return redirect()->route('petugas.approve-loans')->with('success', 'Peminjaman disetujui sampai ' . $tanggal_kembali_target->format('d M Y'));
    }

    // Tolak Peminjaman
    public function rejectLoan(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('petugas.approve-loans')->with('success', 'Peminjaman ditolak');
    }

    // Halaman Validasi Pengembalian
    public function validateReturnIndex()
    {
        $loans = Loan::where('status', 'approved')
            ->with(['user', 'tool'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('petugas.validate-returns', compact('loans'));
    }

    // Validasi Pengembalian
    public function validateReturn(Request $request, Loan $loan)
    {
        $request->validate([
            'jumlah_kembali_baik' => 'required|integer|min:0',
            'jumlah_kembali_rusak' => 'required|integer|min:0',
            'jumlah_kembali_hilang' => 'required|integer|min:0',
            'harga_barang' => 'nullable|numeric|min:0'
        ]);

        $jumlahBaik = $request->jumlah_kembali_baik;
        $jumlahRusak = $request->jumlah_kembali_rusak;
        $jumlahHilang = $request->jumlah_kembali_hilang;
        $totalDikembalikan = $jumlahBaik + $jumlahRusak + $jumlahHilang;
        $harga_barang = (int) $request->harga_barang ?? 0;
        $tanggal_kembali = now();

        // Validasi total jumlah yang dikembalikan
        if ($totalDikembalikan != $loan->jumlah) {
            return redirect()->back()->with('error', 'Total barang yang dikembalikan tidak sesuai dengan jumlah pinjam!');
        }

        // Hitung denda hanya untuk yang rusak dan hilang
        $denda_total = 0;
        $denda_keterangan = [];

        if ($jumlahRusak > 0 && $harga_barang > 0) {
            $denda_rusak = ($jumlahRusak * $harga_barang * 0.5);
            $denda_total += $denda_rusak;
            $denda_keterangan[] = "Rusak ({$jumlahRusak}x): " . number_format($denda_rusak, 0, ',', '.');
        }

        if ($jumlahHilang > 0 && $harga_barang > 0) {
            $denda_hilang = ($jumlahHilang * $harga_barang * 1);
            $denda_total += $denda_hilang;
            $denda_keterangan[] = "Hilang ({$jumlahHilang}x): " . number_format($denda_hilang, 0, ',', '.');
        }

        // Hitung keterlambatan untuk yang baik
        if ($jumlahBaik > 0) {
            $denda_terlambat = DendaHelper::hitungDenda(
                'baik',
                $harga_barang,
                $loan->tanggal_kembali_target,
                $tanggal_kembali
            );
            if ($denda_terlambat['total'] > 0) {
                $denda_total += $denda_terlambat['total'];
                $denda_keterangan[] = "Keterlambatan: " . $denda_terlambat['keterangan'];
            }
        }

        // Tentukan status denda awal
        $denda_status = 'tidak_ada_denda';
        if ($denda_total > 0) {
            $denda_status = 'menunggu_pembayaran'; // Denda ada, menunggu siswa bayar
        }

        $denda_keterangan_text = implode('; ', $denda_keterangan) ?: 'Tidak ada denda';

        // Update loan dengan detil pengembalian dan denda
        $loan->update([
            'tanggal_kembali' => $tanggal_kembali,
            'jumlah_kembali_baik' => $jumlahBaik,
            'jumlah_kembali_rusak' => $jumlahRusak,
            'jumlah_kembali_hilang' => $jumlahHilang,
            'harga_barang' => $harga_barang,
            'alasan_denda' => $denda_keterangan_text,
            'denda' => $denda_total,
            'denda_status' => $denda_status,
            'status' => 'returned'
        ]);

        // Return stok untuk yang baik
        if ($jumlahBaik > 0) {
            $loan->tool->stok += $jumlahBaik;
            $loan->tool->save();
        }

        // Catat aktivitas
        $pesan_detail = "Baik: {$jumlahBaik}, Rusak: {$jumlahRusak}, Hilang: {$jumlahHilang}";
        $pesan_denda = $denda_total > 0 ? " - Denda: Rp " . number_format($denda_total, 0, ',', '.') : "";
        ActivityHelper::log(
            auth()->user(),
            'Memvalidasi pengembalian alat',
            "Validasi pengembalian alat ({$pesan_detail}){$pesan_denda}",
            'update',
            'Loan',
            $loan->id
        );

        return redirect()->route('petugas.validate-returns')->with('success', 'Pengembalian divalidasi. Denda: Rp ' . number_format($denda_total, 0, ',', '.'));
    }

    /**
     * Menandai denda pada loan sebagai lunas (petugas menerima pembayaran tunai)
     */
    public function markDendaLunas(Loan $loan)
    {
        // Authorize - hanya petugas
        if (auth()->user()->role !== 'petugas') {
            abort(403, 'Hanya petugas yang dapat melakukan aksi ini');
        }

        if (!$loan->denda || $loan->denda <= 0) {
            return redirect()->back()->with('error', 'Tidak ada denda untuk diproses');
        }

        // Jika ada pembayaran pending, verifikasi itu; jika tidak, buat payment baru sebagai tunai terverifikasi
        $pending = \App\Models\DendaPayment::where('loan_id', $loan->id)->where('status', 'menunggu_verifikasi')->first();

        if ($pending) {
            $pending->markAsVerified(auth()->id());
            $payment = $pending;
        } else {
            $payment = \App\Models\DendaPayment::create([
                'loan_id' => $loan->id,
                'jumlah_denda' => $loan->denda,
                'jumlah_bayar' => $loan->denda,
                'sisa_denda' => 0,
                'metode_pembayaran' => 'tunai',
                'status' => 'terverifikasi',
                'tanggal_pembayaran' => now(),
                'tanggal_verifikasi' => now(),
                'petugas_verifikasi_id' => auth()->id(),
            ]);
        }

        $loan->update([
            'denda' => 0,
            'denda_status' => 'lunas'
        ]);

        ActivityHelper::log(
            auth()->user(),
            'Menandai denda lunas',
            'Petugas menandai denda pinjaman #' . $loan->id . ' sebagai lunas',
            'update',
            'Loan',
            $loan->id
        );

        ActivityHelper::log(
            auth()->user(),
            'Pembayaran denda tunai',
            'Petugas menerima pembayaran tunai dari ' . $loan->user->name,
            'create',
            'DendaPayment',
            $payment->id
        );

        return redirect()->back()->with('success', 'Denda berhasil ditandai lunas');
    }

    // Cetak Struk Peminjaman (PDF)
    public function cetakStrukPeminjaman(Loan $loan)
    {
        $data = [
            'loan' => $loan,
            'tanggal_cetak' => now(),
        ];
        $pdf = \PDF::loadView('pdf.struk-peminjaman', $data);
        return $pdf->download('Struk-Peminjaman-' . $loan->id . '.pdf');
    }

    // Cetak Struk Pengembalian (PDF)
    public function cetakStrukPengembalian(Loan $loan)
    {
        $data = [
            'loan' => $loan,
            'tanggal_cetak' => now(),
        ];
        $pdf = \PDF::loadView('pdf.struk-pengembalian', $data);
        return $pdf->download('Struk-Return-' . $loan->id . '.pdf');
    }

    // Halaman Laporan
    public function reports(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        if ($filter === 'pending') {
            $loans = Loan::where('status', 'pending')->with(['user', 'tool'])->get();
        } elseif ($filter === 'approved') {
            $loans = Loan::where('status', 'approved')->with(['user', 'tool'])->get();
        } elseif ($filter === 'rejected') {
            $loans = Loan::where('status', 'rejected')->with(['user', 'tool'])->get();
        } else {
            $loans = Loan::with(['user', 'tool'])->get();
        }

        return view('petugas.reports', compact('loans'));
    }

    // Halaman Laporan Peminjaman (History/Completed)
    public function laporanPeminjaman(Request $request)
    {
        $query = Loan::where('status', 'returned')
            ->with(['user', 'tool']);

        // Filter by siswa
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by alat
        if ($request->filled('tool_id')) {
            $query->where('tool_id', $request->tool_id);
        }

        // Filter by tanggal
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_pinjam', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        $loans = $query->orderBy('tanggal_kembali', 'desc')->paginate(20);
        $users = \App\Models\User::where('role', 'siswa')->orderBy('name')->get();
        $tools = Tool::orderBy('nama_alat')->get();

        return view('petugas.laporan-peminjaman', compact('loans', 'users', 'tools'));
    }
}

