<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUserRoles extends Command
{
    protected $signature = 'fix:user-roles';
    protected $description = 'Fix all NULL roles in users table';

    public function handle()
    {
        // Update all NULL roles to 'siswa' (default untuk semua peminjam)
        $updated = DB::table('users')
            ->whereNull('role')
            ->update(['role' => 'siswa']);

        $this->info("✅ Fixed {$updated} users with NULL role → 'siswa'");
        
        // Show all users
        $users = User::all();
        $this->table(['ID', 'Name', 'Email', 'Role'], 
            $users->map(fn($u) => [$u->id, $u->name, $u->email, $u->role])->toArray()
        );
    }
}
