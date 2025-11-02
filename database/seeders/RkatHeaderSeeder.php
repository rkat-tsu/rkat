<?php
// database/seeders/RkatHeaderSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RkatHeader;

class RkatHeaderSeeder extends Seeder
{
    public function run(): void
    {
        RkatHeader::truncate();
        
        // Asumsi User ID 1 adalah pengaju/inputer yang sah
        $pengajuId = 1; 

        // * RKAT 1: Prodi Informatika
        RkatHeader::create([
            'id_header' => 101, 
            'id_unit' => 2, 
            'id_proker' => 1, 
            'tahun_anggaran' => 2025,
            'status_persetujuan' => 'Menunggu_Dekan_Kepala',
            'total_biaya' => 5000000,
            'diajukan_oleh' => $pengajuId, // <-- TAMBAHKAN BARIS INI
        ]);

        // * RKAT 2: Unit Kemahasiswaan
        RkatHeader::create([
            'id_header' => 102, 
            'id_unit' => 3, 
            'id_proker' => 2, 
            'tahun_anggaran' => 2025,
            'status_persetujuan' => 'Menunggu_Dekan_Kepala',
            'total_biaya' => 8000000,
            'diajukan_oleh' => $pengajuId, // <-- TAMBAHKAN BARIS INI
        ]);
        
        // * RKAT 3: CDC (Non-Akademik)
        RkatHeader::create([
            'id_header' => 103, 
            'id_unit' => 4, 
            'id_proker' => 3, 
            'tahun_anggaran' => 2025,
            'status_persetujuan' => 'Menunggu_Dekan_Kepala',
            'total_biaya' => 3000000,
            'diajukan_oleh' => $pengajuId, // <-- TAMBAHKAN BARIS INI
        ]);
        
        // * RKAT 4: Sudah disetujui WR1 (untuk WR3)
        RkatHeader::create([
            'id_header' => 104, 
            'id_unit' => 3,
            'id_proker' => 2, 
            'tahun_anggaran' => 2025,
            'status_persetujuan' => 'Menunggu_WR3', 
            'total_biaya' => 9000000,
            'diajukan_oleh' => $pengajuId, // <-- TAMBAHKAN BARIS INI
        ]);
    }
}