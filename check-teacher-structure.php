<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking 'teacher' table structure...\n\n";

try {
    $columns = DB::select("SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_name = 'teacher' ORDER BY ordinal_position");

    echo "Table: teacher\n";
    echo "================\n";
    foreach ($columns as $col) {
        echo "{$col->column_name} ({$col->data_type}) - nullable: {$col->is_nullable}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
