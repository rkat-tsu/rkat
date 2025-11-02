<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // Tambahkan ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. NONAKTIFKAN pemeriksaan Foreign Key
        Schema::disableForeignKeyConstraints(); 

        $this->call([
            ProgramKerjaSeeder::class,
            UnitSeeder::class,
            UserSeeder::class,
            RkatHeaderSeeder::class,
        ]);

        // 2. AKTIFKAN kembali pemeriksaan Foreign Key
        Schema::enableForeignKeyConstraints();
    }
}