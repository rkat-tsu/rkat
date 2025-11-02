<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        // --- Data Inputer/Pengaju ---
        User::create(['id_user' => 1, 'username' => 'pengaju', 'email' => 'pengaju@example.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Pengaju RKAT', 'peran' => 'User', 'id_unit' => 2]); // Dari Prodi Informatika

        // --- Data Approver (WR) ---
        User::create(['id_user' => 2, 'username' => 'wr1', 'email' => 'wr1@example.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Wakil Rektor 1 (Akademik)', 'peran' => 'WR_1', 'id_unit' => null]);
        User::create(['id_user' => 3, 'username' => 'wr3', 'email' => 'wr3@example.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Wakil Rektor 3 (Non-Akademik)', 'peran' => 'WR_3', 'id_unit' => null]);
        User::create(['id_user' => 9, 'username' => 'wr2', 'email' => 'wr2@example.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Wakil Rektor 2 (Dana)', 'peran' => 'WR_2', 'id_unit' => null]);

        // --- Data Approver (Dekan/Kepala Unit) ---
        // ID ini harus sesuai dengan 'id_kepala' di UnitSeeder
        User::create(['id_user' => 5, 'username' => 'dekan_ft', 'email' => 'dekan@ft.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Dekan Fakultas Teknik', 'peran' => 'Dekan', 'id_unit' => 1]);
        User::create(['id_user' => 6, 'username' => 'kaprodi_if', 'email' => 'kaprodi@if.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Kaprodi Informatika', 'peran' => 'Kepala_Unit', 'id_unit' => 2]);
        User::create(['id_user' => 7, 'username' => 'kepala_kmh', 'email' => 'kepala@kmh.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Kepala Unit Kemahasiswaan', 'peran' => 'Kepala_Unit', 'id_unit' => 3]);
        User::create(['id_user' => 8, 'username' => 'kepala_cdc', 'email' => 'kepala@cdc.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Kepala CDC', 'peran' => 'Kepala_Unit', 'id_unit' => 4]);

        // --- Data Admin ---
        User::create(['id_user' => 4, 'username' => 'admin', 'email' => 'admin@admin.com', 'password' => Hash::make('password'), 'nama_lengkap' => 'Administrator Sistem', 'peran' => 'Admin', 'id_unit' => null]);
    }
}