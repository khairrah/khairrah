<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Loan;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/dashboard');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {

    $user = Auth::user();

    if ($user->role === 'admin') return redirect('/admin/dashboard');
    if ($user->role === 'siswa') return redirect('/siswa/dashboard');
    if ($user->role === 'petugas') return redirect('/petugas/dashboard');

    abort(403);

})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

    // ✅ DASHBOARD
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalBooks'     => Book::count(),
            'totalLoans'     => Loan::count(),
            'borrowedBooks'  => Loan::where('status', 'dipinjam')->count(),
            'latestLoans'    => Loan::with(['book','user'])->latest()->take(5)->get(),
            'availableBooks' => Book::where('stok', '>', 0)->latest()->take(5)->get(),
        ]);
    })->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | BOOKS
    |--------------------------------------------------------------------------
    */
    Route::resource('books', BookController::class);


    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class);


    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */
    Route::resource('users', UserController::class);

    // ✅ PROFILE (PASSWORD)
    Route::get('/profile/password', function() {
        return view('admin.profile.password');
    })->name('admin.profile.password');


    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOGS
    |--------------------------------------------------------------------------
    */
    Route::resource('activity-logs', ActivityLogController::class);


    /*
    |--------------------------------------------------------------------------
    | LOANS (🔥 FIX FINAL)
    |--------------------------------------------------------------------------
    */
    Route::get('/loans', [LoanController::class, 'index'])
        ->name('admin.loans.index');

    Route::get('/loans/create', [LoanController::class, 'create'])
        ->name('admin.loans.create');

    Route::post('/loans', [LoanController::class, 'store'])
        ->name('admin.loans.store');

    // ✅ RETURN (proses klik tombol kembali)
    Route::post('/loans/{loan}/return', [LoanController::class, 'returnBook'])
        ->name('admin.loans.return');

    // ✅ HALAMAN KHUSUS DATA PENGEMBALIAN
    Route::get('/loans/returned', [LoanController::class, 'returned'])
        ->name('admin.loans.returned');

    // ✅ HALAMAN APPROVAL
    Route::get('/loans/approval', [LoanController::class, 'approval'])
        ->name('admin.loans.approval');

    // ✅ APPROVE
    Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])
        ->name('admin.loans.approve');

    // ✅ REJECT
    Route::post('/loans/{loan}/reject', [LoanController::class, 'reject'])
        ->name('admin.loans.reject');

});


/*
|--------------------------------------------------------------------------
| SISWA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

    Route::get('/dashboard', fn() => view('siswa.dashboard'))
        ->name('dashboard');

    Route::get('/books', function () {
        $search = request('search');
        $books = Book::with('category')
            ->when($search, function($query) use ($search) {
                $query->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('kode', 'LIKE', "%{$search}%")
                      ->orWhere('pengarang', 'LIKE', "%{$search}%");
            })
            ->get();
            
        $myActiveLoans = Loan::where('user_id', auth()->id())
            ->whereNull('tanggal_kembali')
            ->pluck('book_id')
            ->toArray();

        return view('siswa.books', compact('books', 'myActiveLoans'));
    })->name('books');

    Route::get('/loans', [LoanController::class, 'index'])
        ->name('loans.index');

    Route::get('/loans/create', [LoanController::class, 'create'])
        ->name('loans.create');

    Route::post('/loans', [LoanController::class, 'store'])
        ->name('loans.store');

        Route::get('/riwayat-peminjaman', [LoanController::class, 'history'])
        ->name('riwayat-peminjaman');

    // ✅ PROFILE (PASSWORD)
    Route::get('/profile/password', function() {
        return view('siswa.profile.password');
    })->name('profile.password');

});



/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])->group(function () {

    Route::get('/petugas/dashboard', fn() => view('petugas.dashboard'))
        ->name('petugas.dashboard');

});


require __DIR__.'/auth.php';