<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Buat user untuk testing API
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'school_name' => 'Test School',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
