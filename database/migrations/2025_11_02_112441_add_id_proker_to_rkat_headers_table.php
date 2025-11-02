<?php
// database/migrations/...add_id_proker_to_rkat_headers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rkat_headers', function (Blueprint $table) {
            // Definisikan id_proker, asumsikan UNSIGNED BIGINT
            $table->unsignedBigInteger('id_proker')->nullable()->after('id_unit'); 

            // Tambahkan Foreign Key Constraint (opsional tapi disarankan)
            // Ganti 'program_kerja' jika nama tabel Proker Anda berbeda
            $table->foreign('id_proker')
                  ->references('id_proker') 
                  ->on('program_kerja') // Pastikan nama tabel ini benar
                  ->onDelete('set null'); // Atur menjadi NULL jika Program Kerja dihapus
        });
    }

    public function down(): void
    {
        Schema::table('rkat_headers', function (Blueprint $table) {
            // Hapus foreign key sebelum menghapus kolom
            $table->dropForeign(['id_proker']); 
            $table->dropColumn('id_proker');
        });
    }
};