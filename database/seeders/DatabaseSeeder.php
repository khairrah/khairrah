<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed categories first
        $this->call(CategorySeeder::class);

        // Create test users
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Siswa Test',
            'email' => 'siswa@test.com',
            'password' => bcrypt('password123'),
            'role' => 'siswa'
        ]);
    }
}
