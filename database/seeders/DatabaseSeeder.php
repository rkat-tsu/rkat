<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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
        ]);
    }
}
