<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Status column already exists in loans table, no need to add
    }

    public function down()
    {
        // No changes to undo
    }
};
