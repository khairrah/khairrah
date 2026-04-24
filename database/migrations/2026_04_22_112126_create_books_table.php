<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('judul');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('pengarang');
            $table->string('penerbit');
            $table->integer('tahun');
            $table->integer('stok');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};