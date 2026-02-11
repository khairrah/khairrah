<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_alat',
        'merk',
        'category_id',
        'id_alat',
        'nama_alat',
        'lokasi',
        'kondisi',
        'jurusan',
        'stok',
        'tanggal',
        'harga'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
