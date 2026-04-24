<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Fiksi', 'deskripsi' => 'Buku cerita rekaan seperti novel dan cerpen'],
            ['nama_kategori' => 'Non-Fiksi', 'deskripsi' => 'Buku berdasarkan fakta dan kejadian nyata'],
            ['nama_kategori' => 'Pendidikan', 'deskripsi' => 'Buku pelajaran dan referensi sekolah'],
            ['nama_kategori' => 'Teknologi', 'deskripsi' => 'Buku tentang komputer, IT, dan teknologi'],
            ['nama_kategori' => 'Sejarah', 'deskripsi' => 'Buku tentang peristiwa sejarah'],
            ['nama_kategori' => 'Agama', 'deskripsi' => 'Buku tentang keagamaan dan spiritual'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['nama_kategori' => $category['nama_kategori']],
                ['deskripsi' => $category['deskripsi']]
            );
        }
    }
}