<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom peran menjadi VARCHAR dengan panjang 50.
            // Anda mungkin perlu menyesuaikan tipe data default Laravel Anda.
            // Asumsi peran saat ini adalah string:
            $table->string('peran', 50)->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke panjang sebelumnya jika diperlukan (misal: 25)
            $table->string('peran', 25)->change(); 
        });
    }
};