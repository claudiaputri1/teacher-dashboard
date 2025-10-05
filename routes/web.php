<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Jika user sudah login, redirect ke dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Jika belum login, redirect ke login
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard-fixed');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard profile update (session-based)
    Route::post('/dashboard/profile/update', [ProfileController::class, 'updateProfile'])->name('dashboard.profile.update');
    
    // Dashboard student management (session-based)
    Route::post('/dashboard/students/add', [\App\Http\Controllers\Api\StudentController::class, 'store'])->name('dashboard.students.add');
    Route::get('/dashboard/classes', [ClassController::class, 'index'])->name('dashboard.classes');
    
    // Dashboard API endpoints (session-based for web interface)
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getDashboardStats']);
    Route::get('/api/dashboard/classroom', [DashboardController::class, 'getClassroomData']);
    Route::get('/api/dashboard/progress', [DashboardController::class, 'getProgressData']);
    Route::get('/api/dashboard/assessment', [DashboardController::class, 'getAssessmentData']);
    Route::get('/api/dashboard/analytics', [DashboardController::class, 'getAnalyticsData']);
    
    // Debug route to test authentication
    Route::get('/debug/auth', function() {
        return response()->json([
            'authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name ?? 'No name',
            'user_full_name' => auth()->user()?->full_name ?? 'No full_name',
            'user_email' => auth()->user()?->email ?? 'No email'
        ]);
    });
    
    // Debug route to test Supabase connection
    Route::get('/debug/supabase', function() {
        $supabase = new \App\Services\SupabaseService();
        try {
            $response = $supabase->makeRequest('GET', 'profiles', null, [
                'select' => 'id,full_name,role',
                'limit' => '5'
            ]);
            
            return response()->json([
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->successful() ? $response->json() : null,
                'error' => !$response->successful() ? $response->body() : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    });
    
    // Debug route to test dashboard API
    Route::get('/debug/dashboard', function() {
        $controller = new \App\Http\Controllers\DashboardController(new \App\Services\SupabaseService());
        try {
            $stats = $controller->getDashboardStats();
            $classroom = $controller->getClassroomData();
            
            return response()->json([
                'stats' => $stats->getData(),
                'classroom' => $classroom->getData()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    });
});

require __DIR__.'/auth.php';
