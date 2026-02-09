<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Helpers\ActivityHelper;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::all();
        return view('tools.index', compact('tools'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('tools.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_alat' => 'required',
            'nama_alat' => 'required',
            'merk' => 'required',
            'lokasi' => 'required',
            'kondisi' => 'required',
            'stok' => 'required|integer'
        ]);

        $tool = Tool::create($request->all());
        
        // Catat aktivitas
        ActivityHelper::log('CREATE_ALAT', "Tambah alat: {$tool->nama_alat}");

        return redirect()->route('tools.index')->with('success', 'Alat berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tool = Tool::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_alat' => 'required',
            'nama_alat' => 'required',
            'merk' => 'required',
            'lokasi' => 'required',
            'kondisi' => 'required',
            'stok' => 'required|integer'
        ]);

        $tool = Tool::findOrFail($id);
        $tool->update($request->all());
        
        // Catat aktivitas
        ActivityHelper::log('UPDATE_ALAT', "Edit alat: {$tool->nama_alat}");

        return redirect()->route('tools.index')->with('success', 'Alat berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tool = Tool::findOrFail($id);
        $nama_alat = $tool->nama_alat;
        $tool->delete();
        
        // Catat aktivitas
        ActivityHelper::log('DELETE_ALAT', "Hapus alat: {$nama_alat}");

        return redirect()->route('tools.index')->with('success', 'Alat berhasil dihapus');
    }
}
