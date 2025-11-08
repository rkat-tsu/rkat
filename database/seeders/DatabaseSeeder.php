<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema; // Harus ada

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. NONAKTIFKAN pemeriksaan Foreign Key sebelum seeding
        Schema::disableForeignKeyConstraints(); 

        // Hapus data lama (opsional, tapi disarankan saat seeding)
        // User::truncate();
        // Unit::truncate(); 
        
        // Buat User Admin
        User::create([
            'username' => 'admin',
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'peran' => 'Admin',
            'id_unit' => null, // Admin tidak terikat unit
            'is_aktif' => true,
            'password' => Hash::make('password'),
            'no_telepon' => '08123456789',
            'remember_token' => Str::random(10),
        ]);

        // Panggil Seeder Lain
        $this->call([
            // Pastikan urutan seeding benar (Unit harus ada sebelum User/RKAT)
            UnitSeeder::class, 
            UserSeeder::class, // Jika ada User lain selain Admin
            ProgramKerjaSeeder::class,
            RkatHeaderSeeder::class,
            // Tambahkan seeder master data lain (seperti TahunAnggaranSeeder jika ada)
        ]);

        // 2. AKTIFKAN kembali pemeriksaan Foreign Key
        Schema::enableForeignKeyConstraints();
    }
}