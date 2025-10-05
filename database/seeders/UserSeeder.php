<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Note: Users harus dibuat melalui Supabase Auth terlebih dahulu
     * Seeder ini hanya untuk membuat profile data jika diperlukan
     * Pastikan UUID sudah ada di auth.users sebelum menjalankan seeder ini
     */
    public function run(): void
    {
        // Seeder ini kosong karena users dibuat melalui Supabase Auth
        // Jika perlu membuat profile untuk user yang sudah ada di Supabase Auth,
        // uncomment dan sesuaikan UUID dengan yang ada di auth.users
        
        /*
        User::create([
            'id' => 'UUID_FROM_SUPABASE_AUTH', // Ganti dengan UUID dari Supabase Auth
            'email' => 'user@example.com',
            'full_name' => 'User Name',
            'role' => 'teacher', // atau 'student'
            'school_name' => 'Nama Sekolah',
            'grade_level' => '1-6',
            'avatar_url' => null,
        ]);
        */
    }
}
