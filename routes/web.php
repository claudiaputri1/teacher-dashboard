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
});

require __DIR__.'/auth.php';
