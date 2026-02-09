<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\DendaPaymentController;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/dashboard');
});

// DEBUG ROUTE
Route::get('/debug-user', function () {
    if (!auth()->check()) {
        return "Not logged in";
    }
    $user = auth()->user();
    return "User: {$user->name}, Email: {$user->email}, Role: " . ($user->role ?? 'NULL');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT SESUAI ROLE
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    }

    if ($user->role === 'siswa') {
        return redirect('/siswa/dashboard');
    }

    if ($user->role === 'petugas') {
        return redirect('/petugas/dashboard');
    }

    abort(403);
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // DATA ALAT
    Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
    Route::get('/tools/create', [ToolController::class, 'create'])->name('tools.create');
    Route::post('/tools', [ToolController::class, 'store'])->name('tools.store');
    Route::get('/tools/{tool}/edit', [ToolController::class, 'edit'])->name('tools.edit');
    Route::put('/tools/{tool}', [ToolController::class, 'update'])->name('tools.update');
    Route::delete('/tools/{tool}', [ToolController::class, 'destroy'])->name('tools.destroy');

    // PEMINJAMAN
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::post('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');

    // PENGEMBALIAN
    Route::get('/returns', [LoanController::class, 'returns'])->name('returns.index');
    Route::post('/returns/{loan}', [LoanController::class, 'processReturn'])->name('returns.process');

    // KATEGORI
    Route::resource('categories', CategoryController::class);

    // MANAJEMEN USER
    Route::resource('users', UserController::class);

    // LOG AKTIVITAS
    Route::get('/activity-logs', function () {
        $logs = \App\Models\ActivityLog::orderBy('created_at', 'desc')->paginate(20);
        return view('activity-logs.index', compact('logs'));
    })->name('activity-logs.index');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| SISWA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/dashboard', function () {
        return view('siswa.dashboard');
    })->name('siswa.dashboard');

    // SISWA - DAFTAR ALAT
    Route::get('/siswa/tools', function () {
        $tools = \App\Models\Tool::all();
        return view('siswa.tools', compact('tools'));
    })->name('siswa.tools');

    // SISWA - PEMINJAMAN
    Route::get('/siswa/loans/create', function () {
        $tools = \App\Models\Tool::where('stok', '>', 0)->get();
        return view('siswa.create-loan', compact('tools'));
    })->name('siswa.loans.create');
    Route::post('/siswa/loans', [LoanController::class, 'store'])->name('siswa.loans.store');
    Route::patch('/siswa/loans/{loan}/alasan', [LoanController::class, 'updateAlasan'])->name('siswa.loans.alasan');
    Route::get('/siswa/loans', function () {
        $loans = \App\Models\Loan::where('user_id', auth()->id())->get();
        return view('siswa.loans', compact('loans'));
    })->name('siswa.loans.index');
    
    // SISWA - RIWAYAT PEMINJAMAN
    Route::get('/siswa/riwayat-peminjaman', function () {
        $historyLoans = \App\Models\Loan::where('user_id', auth()->id())
            ->where('status', 'returned')
            ->with('tool')
            ->orderBy('tanggal_kembali', 'desc')
            ->paginate(20);
        
        $totalDendaBayar = \App\Models\Loan::where('user_id', auth()->id())
            ->where('status', 'returned')
            ->where('denda_status', 'lunas')
            ->sum('denda');
        
        return view('siswa.riwayat-peminjaman', compact('historyLoans', 'totalDendaBayar'));
    })->name('siswa.riwayat-peminjaman');

    // SISWA - PEMBAYARAN DENDA
    Route::get('/siswa/denda-payments', [DendaPaymentController::class, 'index'])->name('siswa.denda-payments.index');
    Route::get('/siswa/denda-payments/create/{loan}', [DendaPaymentController::class, 'create'])->name('siswa.denda-payments.create');
    Route::post('/siswa/denda-payments/pay-now', [DendaPaymentController::class, 'payNow'])->name('siswa.denda-payments.pay-now');
    Route::post('/siswa/denda-payments/{loan}', [DendaPaymentController::class, 'store'])->name('siswa.denda-payments.store');
    Route::get('/siswa/denda-payments/{dendaPayment}', [DendaPaymentController::class, 'show'])->name('siswa.denda-payments.show');
    Route::get('/siswa/denda-payments/{dendaPayment}/cetak', [DendaPaymentController::class, 'cetakBuktiPembayaran'])->name('siswa.denda-payments.cetak');
});

/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard');
    })->name('petugas.dashboard');

    // DAFTAR ALAT
    Route::get('/petugas/tools', [PetugasController::class, 'toolsIndex'])->name('petugas.tools');

    // SETUJUI PEMINJAMAN
    Route::get('/petugas/approve-loans', [PetugasController::class, 'approveLoanIndex'])->name('petugas.approve-loans');
    Route::put('/petugas/loans/{loan}/approve', [PetugasController::class, 'approveLoan'])->name('petugas.approve-loan');
    Route::delete('/petugas/loans/{loan}/reject', [PetugasController::class, 'rejectLoan'])->name('petugas.reject-loan');

    // VALIDASI PENGEMBALIAN
    Route::get('/petugas/validate-returns', [PetugasController::class, 'validateReturnIndex'])->name('petugas.validate-returns');
    Route::post('/petugas/loans/{loan}/validate-return', [PetugasController::class, 'validateReturn'])->name('petugas.validate-return');
    // Petugas menandai denda lunas offline (tunai)
    Route::post('/petugas/loans/{loan}/mark-lunas', [PetugasController::class, 'markDendaLunas'])->name('petugas.mark-denda-lunas');

    // LAPORAN
    Route::get('/petugas/reports', [PetugasController::class, 'reports'])->name('petugas.reports');
    Route::get('/petugas/laporan-peminjaman', [PetugasController::class, 'laporanPeminjaman'])->name('petugas.laporan-peminjaman');

    // CETAK STRUK PEMINJAMAN & PENGEMBALIAN
    Route::get('/petugas/loans/{loan}/cetak-peminjaman', [PetugasController::class, 'cetakStrukPeminjaman'])->name('petugas.cetak-peminjaman');
    Route::get('/petugas/loans/{loan}/cetak-pengembalian', [PetugasController::class, 'cetakStrukPengembalian'])->name('petugas.cetak-pengembalian');

    // VERIFIKASI PEMBAYARAN DENDA
    Route::get('/petugas/verify-denda-payments', [DendaPaymentController::class, 'pendingList'])->name('petugas.verify-denda-payments');
    Route::post('/petugas/denda-payments/{dendaPayment}/verify', [DendaPaymentController::class, 'verify'])->name('petugas.verify-denda-payment');


});

require __DIR__.'/auth.php';
