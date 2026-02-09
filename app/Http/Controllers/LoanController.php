<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use App\Helpers\ActivityHelper;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // Tampilkan semua peminjaman
    public function index()
    {
        $loans = Loan::with('tool')->get();
        return view('loans.index', compact('loans'));
    }

    // Form peminjaman
    public function create()
    {
        $tools = Tool::all();
        return view('loans.create', compact('tools'));
    }

    // USER mengajukan peminjaman (status = pending)
    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date'
        ]);

        $tool = Tool::findOrFail($request->tool_id);

        if ($request->jumlah > $tool->stok) {
            return back()->with('error', 'Stok tidak cukup');
        }

        // Cek apakah user siswa atau admin
        $isAdmin = auth()->user()->role === 'admin';
        $redirectRoute = $isAdmin ? 'loans.index' : 'siswa.loans.index';

        // Jangan set deadline di sini, petugas yang akan set saat approve
        Loan::create([
            'nama_peminjam' => auth()->user()->name,
            'user_id' => auth()->id(),
            'tool_id' => $request->tool_id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_target' => null,  // Akan diset saat petugas approve
            'catatan' => $request->catatan ?? null,
            'status' => 'pending'
        ]);
        
        // Catat aktivitas
        ActivityHelper::log('CREATE_PEMINJAMAN', "Ajukan peminjaman alat: {$tool->nama_alat}");

        return redirect()->route($redirectRoute)->with('success', 'Peminjaman berhasil diajukan, menunggu persetujuan');
    }

    // PETUGAS menyetujui peminjaman + set deadline
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'durasi_hari' => 'required|integer|min:1|max:90'
        ]);

        $loan = Loan::findOrFail($id);
        $tool = $loan->tool;

        if ($loan->status != 'pending') {
            return back();
        }

        if ($loan->jumlah > $tool->stok) {
            return back()->with('error', 'Stok tidak cukup');
        }

        // Set deadline berdasarkan input durasi
        $tanggal_pinjam = \Carbon\Carbon::parse($loan->tanggal_pinjam);
        $tanggal_kembali_target = $tanggal_pinjam->copy()->addDays($validated['durasi_hari']);

        $loan->status = 'approved';
        $loan->tanggal_kembali_target = $tanggal_kembali_target;
        $loan->save();

        $tool->stok -= $loan->jumlah;
        $tool->save();

        ActivityHelper::log('APPROVE_PEMINJAMAN', "Setujui peminjaman {$tool->nama_alat} sampai " . $tanggal_kembali_target->format('d M Y'));

        return back()->with('success', 'Peminjaman disetujui sampai ' . $tanggal_kembali_target->format('d M Y'));
    }

    // ADMIN menolak peminjaman
    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->status = 'rejected';
        $loan->save();

        return back()->with('success', 'Peminjaman ditolak');
    }

    // ADMIN memproses pengembalian
    public function return($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status != 'approved') {
            return back()->with('error', 'Belum disetujui atau sudah dikembalikan');
        }

        $tool = $loan->tool;
        $tool->stok += $loan->jumlah;
        $tool->save();

        $loan->status = 'returned';
        $loan->tanggal_kembali = now();
        $loan->save();

        return back()->with('success', 'Alat berhasil dikembalikan');
    }

    // Halaman pengembalian
    public function returns()
    {
        $loans = Loan::with('tool')->whereNull('tanggal_kembali')->get();
        return view('returns.index', compact('loans'));
    }

    // Proses pengembalian dari halaman returns
    public function processReturn($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status != 'approved') {
            return back()->with('error', 'Peminjaman ini tidak bisa dikembalikan');
        }

        $tool = $loan->tool;
        $tool->stok += $loan->jumlah;
        $tool->save();

        $loan->status = 'returned';
        $loan->tanggal_kembali = now();
        $loan->save();

        return redirect()->route('returns.index')->with('success', 'Alat berhasil diterima kembali');
    }

    // Update alasan kerusakan/hilang dari siswa
    public function updateAlasan(\Illuminate\Http\Request $request, Loan $loan)
    {
        // Validasi: hanya siswa yang meminjam atau admin yang bisa update
        if (auth()->id() !== $loan->user_id && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Tidak authorized');
        }

        $request->validate([
            'alasan_siswa' => 'required|string|min:10|max:500'
        ]);

        $loan->update([
            'alasan_siswa' => $request->alasan_siswa
        ]);

        return back()->with('success', 'Laporan kerusakan/hilang berhasil disimpan');
    }
}
