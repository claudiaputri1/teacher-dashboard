<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    public function index()
    {
        try {
            $teacherId = auth()->id();
            $students = DB::table('students')
                ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
                ->where('students.teacher_id', $teacherId)
                ->select('students.*', 'classes.name as class_name')
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
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'nis' => 'nullable|string|max:50',
            'class_id' => 'nullable|integer|exists:classes,id'
        ]);

        try {
            $studentId = DB::table('students')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'nis' => $request->nis,
                'class_id' => $request->class_id,
                'teacher_id' => auth()->id(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $student = DB::table('students')
                ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
                ->where('students.id', $studentId)
                ->select('students.*', 'classes.name as class_name')
                ->first();

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
        // TODO: Implement show method
        return response()->json(['message' => 'Show student method not implemented yet']);
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
        // TODO: Implement get student progress
        return response()->json(['message' => 'Get student progress method not implemented yet']);
    }
}
