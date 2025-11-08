<?php
// database/seeders/ProgramKerjaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramKerjaSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti nama tabel jika berbeda
        DB::table('program_kerja')->truncate(); 

        DB::table('program_kerja')->insert([
            ['id_proker' => 1, 'nama_proker' => 'Peningkatan Kualitas Dosen', 'kode' => 'A.01'],
            ['id_proker' => 2, 'nama_proker' => 'Kegiatan Mahasiswa', 'kode' => 'B.02'],
            ['id_proker' => 3, 'nama_proker' => 'Pengembangan Karakter', 'kode' => 'C.03'],
        ]);
    }
}