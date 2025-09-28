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
        // Skip modules for now since table creation is having issues

        // Get current user (teacher) if exists
        $teacher = DB::table('teachers')->first();
        
        if ($teacher) {
            // Create sample class
            $classId = DB::table('classes')->insertGetId([
                'name' => 'XII IPA 1',
                'teacher_id' => $teacher->id,
                'academic_year' => '2025/2026',
                'max_capacity' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create sample students
            $students = [
                [
                    'name' => 'Ahmad Rizki Pratama',
                    'email' => 'ahmad.rizki@student.com',
                    'nis' => '12345001',
                    'class_id' => $classId,
                    'teacher_id' => $teacher->id,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Siti Nurhaliza',
                    'email' => 'siti.nurhaliza@student.com',
                    'nis' => '12345002',
                    'class_id' => $classId,
                    'teacher_id' => $teacher->id,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Budi Santoso',
                    'email' => 'budi.santoso@student.com',
                    'nis' => '12345003',
                    'class_id' => $classId,
                    'teacher_id' => $teacher->id,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            foreach ($students as $studentData) {
                $studentId = DB::table('students')->insertGetId($studentData);
                
                // Skip progress for now since modules table doesn't exist
            }

            // Skip assignments and other tables for now - focus on basic structure
        }
    }
}
