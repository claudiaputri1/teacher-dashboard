<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Teacher;
use App\Services\SupabaseService;
use App\Http\Controllers\DashboardController;

echo "Testing Dashboard API...\n\n";

try {
    // Login as first teacher
    $teacher = Teacher::first();
    if (!$teacher) {
        echo "❌ No teacher found. Please register first.\n";
        exit(1);
    }

    // Manually authenticate
    Auth::login($teacher);

    echo "✅ Logged in as: " . $teacher->full_name . " (ID: " . $teacher->id . ")\n\n";

    // Test SupabaseService
    echo "Testing SupabaseService...\n";
    $supabase = new SupabaseService();
    echo "Supabase URL: " . env('SUPABASE_URL') . "\n";
    echo "Supabase Key: " . substr(env('SUPABASE_ANON_KEY'), 0, 20) . "...\n\n";

    // Test getDashboardStats
    echo "Testing getDashboardStats...\n";
    $stats = $supabase->getDashboardStats($teacher->id);
    print_r($stats);
    echo "\n";

    // Test DashboardController
    echo "Testing DashboardController->getDashboardStats()...\n";
    $controller = new DashboardController($supabase);
    $response = $controller->getDashboardStats();
    $data = json_decode($response->getContent(), true);

    echo "Response:\n";
    print_r($data);
    echo "\n";

    if ($data['total_students'] > 0 || $data['avg_progress'] > 0) {
        echo "✅ Stats API is working!\n";
    } else {
        echo "⚠️ Stats API returns 0 values. Ini normal jika belum ada data siswa di Supabase.\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
