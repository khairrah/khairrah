<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Helpers\ActivityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Tampilkan semua data peminjaman
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'siswa') {
            return view('siswa.loans'); // Data diambil inline di blade
        }

        // Admin
        $loans = Loan::with(['book', 'user'])
            ->whereNull('tanggal_kembali')
            ->latest()
            ->get();
        return view('admin.loans.index', compact('loans'));
    }


    /**
     * 🔥 TAMBAHAN DATA PENGEMBALIAN
     */
    public function returned()
    {
        $loans = Loan::with(['book', 'user'])
            ->whereNotNull('tanggal_kembali')
            ->latest()
            ->get();

        return view('admin.loans.returned', compact('loans'));
    }

    /**
     * Form tambah peminjaman (ADMIN)
     */
    public function create()
{
    $books = \App\Models\Book::where('stok', '>', 0)->get();

    return view('siswa.loans.create', compact('books'));
}
    /**
     * Simpan peminjaman
     */
    public function store(Request $request)
{
    $request->validate([
        'book_id' => 'required',
        'jumlah' => 'required|integer|min:1',
        'tanggal_pinjam' => 'required',
        'tanggal_kembali' => 'required',
    ]);

    \App\Models\Loan::create([
        'user_id' => auth()->id(),
        'book_id' => $request->book_id,
        'jumlah' => $request->jumlah,
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'tanggal_kembali_target' => $request->tanggal_kembali,
        'status' => 'pending',
    ]);

    ActivityHelper::log('PEMINJAMAN', "Siswa " . auth()->user()->name . " meminjam buku " . ($request->book_id ? \App\Models\Book::find($request->book_id)->judul : '-'));

    return redirect()->route('siswa.dashboard')
        ->with('success', 'Berhasil pinjam buku');
}
    /**
     * 🔥 TAMBAHAN (APPROVAL ADMIN)
     */
    public function approval()
    {
        $loans = Loan::with(['book', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.loans.approval', compact('loans'));
    }

    /**
     * ✅ APPROVE
     */
    public function approve(Loan $loan)
    {
        // Kurangi stok buku
        if ($loan->book) {
            if ($loan->book->stok < $loan->jumlah) {
                return redirect()->back()->with('error', 'Stok buku tidak mencukupi');
            }
            $loan->book->decrement('stok', $loan->jumlah);
        }

        $loan->update(['status' => 'approved']);

        ActivityHelper::log('APPROVAL', "Admin menyetujui peminjaman buku " . ($loan->book->judul ?? '-') . " oleh " . ($loan->user->name ?? '-'));

        return redirect()->back()->with('success', 'Peminjaman disetujui');
    }

    /**
     * ✅ REJECT
     */
    public function reject(Loan $loan)
    {
        $loan->update(['status' => 'rejected']);

        ActivityHelper::log('REJECTION', "Admin menolak peminjaman buku " . ($loan->book->judul ?? '-') . " oleh " . ($loan->user->name ?? '-'));

        return redirect()->back()->with('success', 'Peminjaman ditolak');
    }

    /**
     * ✅ RETURN (KEMBALIKAN)
     */
    public function returnBook(Loan $loan)
    {
        $loan->update([
            'tanggal_kembali' => now(),
            'status' => 'returned'
        ]);

        // Kembalikan stok buku
        if ($loan->book) {
            $loan->book->increment('stok', $loan->jumlah);
        }

        ActivityHelper::log('PENGEMBALIAN', "Buku " . ($loan->book->judul ?? '-') . " telah dikembalikan oleh " . ($loan->user->name ?? '-'));

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan');
    }

    /**

     * ✅ RIWAYAT PEMINJAMAN (SISWA)
     */
    public function history()
    {
        $historyLoans = Loan::with(['book', 'tool'])
            ->where('user_id', auth()->id())
            ->whereNotNull('tanggal_kembali')
            ->latest()
            ->paginate(10);

        $totalDendaBayar = Loan::where('user_id', auth()->id())
            ->where('denda_status', 'menunggu_pembayaran')
            ->sum('denda');

        return view('siswa.riwayat-peminjaman', compact('historyLoans', 'totalDendaBayar'));
    }
}