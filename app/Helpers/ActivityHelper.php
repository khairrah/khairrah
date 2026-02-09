<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityHelper
{
    /**
     * Mencatat aktivitas ke database
     * 
     * @param string $action - Nama aksi (CREATE, UPDATE, DELETE, LOGIN, dll)
     * @param string $description - Deskripsi detail aksi
     * @return void
     */
    public static function log($action, $description = null)
    {
        try {
            if (Auth::check()) {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => $action,
                    'description' => $description
                ]);
            }
        } catch (\Exception $e) {
            // Silent fail - jangan interrupt operasi utama
            \Log::error('Activity logging failed: ' . $e->getMessage());
        }
    }
}
