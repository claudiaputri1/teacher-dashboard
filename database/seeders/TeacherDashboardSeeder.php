<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder is disabled - using Supabase database with existing data
        // Teachers, classes, and students should exist in Supabase
        // and be managed through Supabase Auth and the application interface
        
        // All data should be created through:
        // 1. Supabase Auth for user authentication
        // 2. Application interface for classes, assignments, etc.
    }
}
