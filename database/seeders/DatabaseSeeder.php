<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
=======
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // Tambahkan ini
>>>>>>> 73dee42e94c50733d75a184c9e887f1b1c673824

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        User::create([
            'username' => 'admin',
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'peran' => 'Admin',
            'id_unit' => null,
            'is_aktif' => true,
            'password' => Hash::make('password'),
            'no_telepon' => '08123456789',
            'remember_token' => Str::random(10),
=======
        // 1. NONAKTIFKAN pemeriksaan Foreign Key
        Schema::disableForeignKeyConstraints(); 

        $this->call([
            ProgramKerjaSeeder::class,
            UnitSeeder::class,
            UserSeeder::class,
            RkatHeaderSeeder::class,
>>>>>>> 73dee42e94c50733d75a184c9e887f1b1c673824
        ]);

        // 2. AKTIFKAN kembali pemeriksaan Foreign Key
        Schema::enableForeignKeyConstraints();
    }
}