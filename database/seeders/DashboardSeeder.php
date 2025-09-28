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
        // Create sample classes
        $classes = [
            ['name' => 'XII IPA 1', 'description' => 'Kelas 12 IPA 1'],
            ['name' => 'XII IPA 2', 'description' => 'Kelas 12 IPA 2'],
            ['name' => 'XI IPA 1', 'description' => 'Kelas 11 IPA 1'],
        ];

        foreach ($classes as $classData) {
            ClassSiswa::firstOrCreate(['name' => $classData['name']], $classData);
        }

        // Create sample modules
        $modules = [
            ['title' => 'Geometri 3D', 'description' => 'Pembelajaran tentang bangun ruang tiga dimensi'],
            ['title' => 'Bangun Ruang', 'description' => 'Mengenal berbagai jenis bangun ruang'],
            ['title' => 'Transformasi Geometri', 'description' => 'Translasi, rotasi, dan refleksi'],
            ['title' => 'Koordinat 3D', 'description' => 'Sistem koordinat tiga dimensi'],
            ['title' => 'Limas dan Prisma', 'description' => 'Menghitung volume dan luas permukaan'],
        ];

        foreach ($modules as $moduleData) {
            Module::firstOrCreate(['title' => $moduleData['title']], $moduleData);
        }

        // Create sample students
        $students = [
            ['name' => 'Ahmad Nurul', 'email' => 'ahmad.nurul@example.com'],
            ['name' => 'Siti Putri', 'email' => 'siti.putri@example.com'],
            ['name' => 'Rizki Fadli', 'email' => 'rizki.fadli@example.com'],
            ['name' => 'Dewi Maya', 'email' => 'dewi.maya@example.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@example.com'],
            ['name' => 'Rina Sari', 'email' => 'rina.sari@example.com'],
        ];

        foreach ($students as $studentData) {
            Student::firstOrCreate(['email' => $studentData['email']], $studentData);
        }

        // Create sample progress data
        $students = Student::all();
        $modules = Module::all();

        foreach ($students as $student) {
            foreach ($modules->random(rand(2, 4)) as $module) {
                StudentProgress::firstOrCreate([
                    'student_id' => $student->id,
                    'module_id' => $module->id,
                ], [
                    'completed' => rand(15, 100),
                    'xp' => rand(50, 500),
                    'time_spent' => rand(30, 300),
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subHours(rand(1, 48)),
                ]);
            }
        }
    }
}
