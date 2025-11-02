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
        Schema::table('unit', function (Blueprint $table) {
            // Tambahkan kolom setelah 'tipe_unit'
            // Nilai defaultnya bisa disesuaikan, misal 'akademik'
            $table->string('jalur_persetujuan')->default('akademik')->after('tipe_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit', function (Blueprint $table) {
            $table->dropColumn('jalur_persetujuan');
        });
    }
};