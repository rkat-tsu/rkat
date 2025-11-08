<?php
// database/migrations/2024_01_01_000000_create_program_kerja_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kerja', function (Blueprint $table) {
            $table->bigIncrements('id_proker'); 
            $table->string('kode', 10)->unique();
            $table->string('nama_proker', 255);
            $table->timestamps();
        });
    }
    // ... down()
};