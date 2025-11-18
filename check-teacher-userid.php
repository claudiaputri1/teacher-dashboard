<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Teacher;

echo "Checking teacher user_id...\n\n";

$teacher = Teacher::first();

if ($teacher) {
    echo "Teacher ID: " . $teacher->id . "\n";
    echo "Teacher user_id: " . ($teacher->user_id ?? 'NULL') . "\n";
    echo "Teacher email: " . $teacher->email . "\n";
    echo "Teacher full_name: " . $teacher->full_name . "\n";

    if (empty($teacher->user_id)) {
        echo "\n❌ MASALAH: user_id is NULL or empty!\n";
        echo "Dashboard tidak bisa mengambil data karena user_id kosong.\n";
    } else {
        echo "\n✅ user_id is set\n";
    }
} else {
    echo "❌ No teacher found in database\n";
}
