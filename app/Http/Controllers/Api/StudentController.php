<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    public function index()
    {
        try {
            $teacherId = auth()->id();
            
            // Get students from profiles table through classroom_members
            $students = User::students()
                ->leftJoin('classroom_members', 'profiles.id', '=', 'classroom_members.student_id')
                ->leftJoin('classrooms', 'classroom_members.classroom_id', '=', 'classrooms.id')
                ->where('classrooms.teacher_id', $teacherId)
                ->select('profiles.*', 'classrooms.name as class_name', 'classroom_members.joined_at')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles,email',
            'school_name' => 'nullable|string|max:255',
            'grade_level' => 'nullable|string|max:50',
            'classroom_id' => 'nullable|string|exists:classrooms,id'
        ]);

        try {
            // Create student profile
            $student = User::create([
                'id' => \Str::uuid(),
                'full_name' => $request->full_name,
                'email' => $request->email,
                'role' => 'student',
                'school_name' => $request->school_name,
                'grade_level' => $request->grade_level,
            ]);

            // Add to classroom if specified
            if ($request->classroom_id) {
                DB::table('classroom_members')->insert([
                    'classroom_id' => $request->classroom_id,
                    'student_id' => $student->id,
                    'joined_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $student
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan siswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $student = User::students()->find($id);
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $student->getStudentProfile()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update method
        return response()->json(['message' => 'Update student method not implemented yet']);
    }

    public function destroy($id)
    {
        // TODO: Implement destroy method
        return response()->json(['message' => 'Delete student method not implemented yet']);
    }

    public function getProgress($studentId)
    {
        try {
            $student = User::students()->find($studentId);
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            $progress = $student->progress()->with('lesson.module')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'student' => $student->getStudentProfile(),
                    'progress' => $progress
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student progress',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
