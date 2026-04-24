<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Book extends Model
{
    protected $fillable = [
        'kode',
        'judul',
        'category_id',
        'pengarang',
        'penerbit',
        'tahun',
        'stok'
    ];

    // 🔥 RELASI KE CATEGORY
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}