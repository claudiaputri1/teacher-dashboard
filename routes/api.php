<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Api\AssessmentController;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Generate API Token for testing
Route::post('/auth/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});

// Test endpoint without auth
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!', 'timestamp' => now()]);
});

Route::middleware('auth:sanctum')->group(function () {
    // Dashboard endpoints
    Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'getDashboardStats']);
    Route::get('/dashboard/classroom', [App\Http\Controllers\DashboardController::class, 'getClassroomData']);
    Route::get('/dashboard/progress', [App\Http\Controllers\DashboardController::class, 'getProgressData']);
    Route::get('/dashboard/assessment', [App\Http\Controllers\DashboardController::class, 'getAssessmentData']);
    Route::get('/dashboard/assignments', [App\Http\Controllers\DashboardController::class, 'getAssignmentData']);
    Route::get('/dashboard/analytics', [App\Http\Controllers\DashboardController::class, 'getAnalyticsData']);
    
    // Profile endpoints
    Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'updateProfile']);
    
    // Classes
    Route::apiResource('classes', ClassController::class);
    Route::post('classes/{class}/students', [ClassController::class, 'addStudent']);
    
    // Students
    Route::apiResource('students', \App\Http\Controllers\Api\StudentController::class);
    Route::get('students/{student}/progress', [\App\Http\Controllers\Api\StudentController::class, 'getProgress']);
    
    // Assignments
    Route::apiResource('assignments', AssignmentController::class);
    Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit']);
    
    // AI Assessments
    Route::get('assessments', [AssessmentController::class, 'index']);
    Route::post('assessments/{assessment}/review', [AssessmentController::class, 'review']);
    Route::post('assessments/{assessment}/approve', [AssessmentController::class, 'approve']);
});
