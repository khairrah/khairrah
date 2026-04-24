<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // cek dulu biar tidak duplicate kalau pernah migrate
            if (!Schema::hasColumn('loans', 'book_id')) {
                $table->unsignedBigInteger('book_id')->after('user_id');

                $table->foreign('book_id')
                      ->references('id')
                      ->on('books')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'book_id')) {
                $table->dropForeign(['book_id']);
                $table->dropColumn('book_id');
            }
        });
    }
};