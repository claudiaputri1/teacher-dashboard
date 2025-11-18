<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

echo "Testing Teacher Registration...\n\n";

try {
    // Test 1: Check if we can connect to teacher table
    $count = Teacher::count();
    echo "✅ Connected to 'teacher' table\n";
    echo "   Current teachers count: {$count}\n\n";

    // Test 2: Check if email already exists
    $testEmail = 'test-' . time() . '@example.com';
    echo "Testing with email: {$testEmail}\n";

    $exists = Teacher::where('email', $testEmail)->exists();
    if ($exists) {
        echo "❌ Email already exists\n";
    } else {
        echo "✅ Email is available\n";
    }

    // Test 3: Try to create a teacher
    echo "\nCreating test teacher...\n";
    $teacher = Teacher::create([
        'id' => Str::uuid(),
        'full_name' => 'Test Teacher',
        'email' => $testEmail,
        'password' => Hash::make('password123'),
        'is_active' => true,
    ]);

    echo "✅ Teacher created successfully!\n";
    echo "   ID: {$teacher->id}\n";
    echo "   Name: {$teacher->full_name}\n";
    echo "   Email: {$teacher->email}\n";

    // Clean up - delete test teacher
    echo "\nCleaning up test data...\n";
    $teacher->delete();
    echo "✅ Test teacher deleted\n";

    echo "\n✅ ALL TESTS PASSED! Registration should work now.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
