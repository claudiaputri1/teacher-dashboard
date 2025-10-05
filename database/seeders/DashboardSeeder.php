<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Module;
use App\Models\StudentProgress;
use App\Models\ClassSiswa;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder is disabled - using Supabase database with existing data
        // All data (classes, modules, students, progress) should exist in Supabase
        // and be managed through the application interface
        
        // If you need to seed data, do it directly in Supabase or through the application
    }
}
