<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
Schema::create('loans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id');
    $table->foreignId('tool_id');
    $table->date('tanggal_pinjam');
    $table->date('tanggal_kembali')->nullable();   // ← ini
    $table->string('status')->default('dipinjam');
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
