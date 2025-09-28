<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        $teacherId = auth()->id();
        $classes = $this->supabase->getClasses($teacherId);
        
        return response()->json([
            'success' => true,
            'data' => $classes->data ?? []
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'max_capacity' => 'integer|min:1|max:100'
        ]);

        try {
            $data = [
                'name' => $request->name,
                'teacher_id' => auth()->id(),
                'academic_year' => $request->academic_year,
                'max_capacity' => $request->max_capacity ?? 30
            ];

            $result = $this->supabase->createClass($data);

            return response()->json([
                'success' => true,
                'message' => 'Class created successfully',
                'data' => $result->data ?? []
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create class',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        // TODO: Implement show method
        return response()->json(['message' => 'Show class method not implemented yet']);
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update method
        return response()->json(['message' => 'Update class method not implemented yet']);
    }

    public function destroy($id)
    {
        // TODO: Implement destroy method
        return response()->json(['message' => 'Delete class method not implemented yet']);
    }

    public function addStudent(Request $request, $classId)
    {
        // TODO: Implement add student to class
        return response()->json(['message' => 'Add student to class method not implemented yet']);
    }
}
