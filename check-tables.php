<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking available tables in database...\n\n";

try {
    $tables = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' ORDER BY tablename");

    echo "Available tables:\n";
    echo "================\n";
    foreach ($tables as $table) {
        echo "- " . $table->tablename . "\n";
    }

    echo "\n\nChecking if 'teachers' table exists...\n";
    $teachersExists = collect($tables)->contains(fn($t) => $t->tablename === 'teachers');

    if ($teachersExists) {
        echo "✅ 'teachers' table EXISTS\n";

        // Check table structure
        $columns = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'teachers' ORDER BY ordinal_position");
        echo "\nTable structure:\n";
        foreach ($columns as $col) {
            echo "  - {$col->column_name} ({$col->data_type})\n";
        }
    } else {
        echo "❌ 'teachers' table DOES NOT EXIST\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
