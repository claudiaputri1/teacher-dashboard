<?php
// Test database connection from web server
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test PDO PostgreSQL
    echo "<h2>Testing PostgreSQL Connection</h2>";

    // Check if pdo_pgsql is loaded
    if (extension_loaded('pdo_pgsql')) {
        echo "✅ pdo_pgsql extension is LOADED<br>";
    } else {
        echo "❌ pdo_pgsql extension is NOT LOADED<br>";
    }

    if (extension_loaded('pgsql')) {
        echo "✅ pgsql extension is LOADED<br>";
    } else {
        echo "❌ pgsql extension is NOT LOADED<br>";
    }

    echo "<br>";

    // Test Laravel DB connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Laravel Database Connection: <strong>SUCCESS</strong><br>";
    echo "Database: " . DB::connection()->getDatabaseName() . "<br>";

    // Test query
    $result = DB::table('teacher')->count();
    echo "✅ Query Test: Found {$result} teacher in database<br>";

    echo "<br><strong style='color: green;'>Database is working properly!</strong>";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
    echo "<br><br>Loaded Extensions: <br>";
    print_r(get_loaded_extensions());
}
