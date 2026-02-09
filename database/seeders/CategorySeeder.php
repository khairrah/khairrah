<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Perkakas Tangan', 'deskripsi' => 'Alat-alat perkakas manual seperti palu, obeng, tang, dan kunci'],
            ['nama_kategori' => 'Peralatan Laboratorium', 'deskripsi' => 'Peralatan untuk praktik laboratorium dan penelitian'],
            ['nama_kategori' => 'Peralatan Komputer', 'deskripsi' => 'Hardware dan aksesori komputer'],
            ['nama_kategori' => 'Peralatan Olahraga', 'deskripsi' => 'Alat-alat untuk kegiatan olahraga dan kebugaran'],
            ['nama_kategori' => 'Peralatan Mesin', 'deskripsi' => 'Mesin-mesin dan peralatan workshop'],
            ['nama_kategori' => 'Peralatan Elektronika', 'deskripsi' => 'Peralatan untuk praktik dan reparasi elektronika'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['nama_kategori' => $category['nama_kategori']],
                ['deskripsi' => $category['deskripsi']]
            );
        }
    }
}
