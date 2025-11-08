<?php
// database/migrations/...add_total_biaya_to_rkat_headers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rkat_headers', function (Blueprint $table) {
            // Menggunakan unsignedBigInteger untuk total biaya (tanpa nilai minus)
            $table->unsignedBigInteger('total_biaya')->default(0)->after('tahun_anggaran'); 
        });
    }

    public function down(): void
    {
        Schema::table('rkat_headers', function (Blueprint $table) {
            $table->dropColumn('total_biaya');
        });
    }
};