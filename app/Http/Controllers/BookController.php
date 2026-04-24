<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Helpers\ActivityHelper;

class BookController extends Controller
{
    // 🔹 Tampilkan semua data buku
    public function index(Request $request)
    {
        $search = $request->search;
        $books = Book::with('category')
            ->when($search, function($query) use ($search) {
                $query->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('kode', 'LIKE', "%{$search}%")
                      ->orWhere('pengarang', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();
            
        return view('admin.books.index', compact('books'));
    }

    // 🔹 Form tambah buku
    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    // 🔹 Simpan data buku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'       => 'required',
            'category_id' => 'required',
            'pengarang'   => 'required',
            'penerbit'    => 'required',
            'tahun'       => 'required|integer',
            'stok'        => 'required|integer'
        ]);

        // 🔥 AUTO GENERATE KODE
        $validated['kode'] = 'BK' . str_pad(Book::count() + 1, 3, '0', STR_PAD_LEFT);

        $book = Book::create($validated);

        ActivityHelper::log('TAMBAH_BUKU', "Menambahkan buku baru: " . $book->judul);

        return redirect()->route('books.index')
                         ->with('success', 'Buku berhasil ditambahkan');
    }

    // 🔹 Form edit buku
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();

        return view('admin.books.edit', compact('book', 'categories'));
    }

    // 🔹 Update data buku
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'judul'       => 'required',
            'category_id' => 'required',
            'pengarang'   => 'required',
            'penerbit'    => 'required',
            'tahun'       => 'required|integer',
            'stok'        => 'required|integer'
        ]);

        $book->update($validated);

        ActivityHelper::log('EDIT_BUKU', "Mengupdate data buku: " . $book->judul);

        return redirect()->route('books.index')
                         ->with('success', 'Buku berhasil diupdate');
    }

    // 🔹 Hapus buku
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $judul = $book->judul;
        $book->delete();


        ActivityHelper::log('HAPUS_BUKU', "Menghapus buku: " . $judul);

        return redirect()->route('books.index')
                         ->with('success', 'Buku berhasil dihapus');
    }
}