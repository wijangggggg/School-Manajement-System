<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 1. Akun Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Teacher
        User::create([
            'name' => 'Budi Guru',
            'username' => 'guru',
            'email' => 'guru@sekolah.com',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
        ]);

        // 3. Akun Student
        User::create([
            'name' => 'Ani Siswa',
            'username' => 'siswa',
            'email' => 'siswa@sekolah.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);
    }
}
