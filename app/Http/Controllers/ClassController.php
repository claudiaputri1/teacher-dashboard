<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $teacherId = auth()->id();
            $classes = DB::table('classrooms')
                ->where('teacher_id', $teacherId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch classes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $teacherId = auth()->id();
            $class = DB::table('classrooms')
                ->where('id', $id)
                ->where('teacher_id', $teacherId)
                ->first();
            
            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found'
                ], 404);
            }
            
            return response()->json($class);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch class',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $teacherId = auth()->id();
            
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'is_active' => 'sometimes|boolean',
            ]);
            
            $updated = DB::table('classrooms')
                ->where('id', $id)
                ->where('teacher_id', $teacherId)
                ->update(array_merge($validated, ['updated_at' => now()]));
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found or no changes made'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update class',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a student to the class.
     */
    public function addStudent(Request $request, string $classId)
    {
        try {
            $teacherId = auth()->id();
            
            // Verify class belongs to teacher
            $class = DB::table('classrooms')
                ->where('id', $classId)
                ->where('teacher_id', $teacherId)
                ->first();
            
            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found'
                ], 404);
            }
            
            $validated = $request->validate([
                'student_id' => 'required|uuid|exists:profiles,id'
            ]);
            
            // Check if student already in class
            $exists = DB::table('classroom_members')
                ->where('classroom_id', $classId)
                ->where('student_id', $validated['student_id'])
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student already in this class'
                ], 400);
            }
            
            // Add student to class
            DB::table('classroom_members')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'classroom_id' => $classId,
                'student_id' => $validated['student_id'],
                'joined_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Student added to class successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add student to class',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
