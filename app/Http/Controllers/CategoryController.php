<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Helpers\ActivityHelper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|unique:categories',
            'deskripsi' => 'nullable|string'
        ]);

        $category = Category::create($validated);
        
        // Catat aktivitas
        ActivityHelper::log('CREATE_KATEGORI', "Tambah kategori: {$category->nama_kategori}");
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.create', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|unique:categories,nama_kategori,' . $category->id,
            'deskripsi' => 'nullable|string'
        ]);

        $category->update($validated);
        
        // Catat aktivitas
        ActivityHelper::log('UPDATE_KATEGORI', "Edit kategori: {$category->nama_kategori}");
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $nama_kategori = $category->nama_kategori;
        $category->delete();
        
        // Catat aktivitas
        ActivityHelper::log('DELETE_KATEGORI', "Hapus kategori: {$nama_kategori}");
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}
