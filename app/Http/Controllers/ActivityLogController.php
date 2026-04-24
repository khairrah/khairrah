<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * 🔹 Tampilkan daftar log aktivitas
     */
    public function index()
    {
        $logs = \App\Models\ActivityLog::with('user')->latest()->get();

        return view('admin.activity-logs.index', compact('logs'));
    }


    /**
     * ❌ Tidak dipakai
     */
    public function create()
    {
        abort(404);
    }

    /**
     * ❌ Tidak dipakai
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * ❌ Tidak dipakai
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * ❌ Tidak dipakai
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * ❌ Tidak dipakai
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * ❌ Tidak dipakai
     */
    public function destroy(string $id)
    {
        abort(404);
    }
}