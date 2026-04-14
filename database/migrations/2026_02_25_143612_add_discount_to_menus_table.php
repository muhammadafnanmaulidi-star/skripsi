<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Diskon dalam persen (0 = tidak ada diskon, 10 = diskon 10%, dst.)
            $table->unsignedTinyInteger('discount_percent')->default(0)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
        });
    }
};
