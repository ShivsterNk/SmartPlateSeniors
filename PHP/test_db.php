<?php
echo "PHP is working<br>";
echo "Looking for config at: " . realpath(__DIR__ . '/../includes/config.php') . "<br>";

$configExists = file_exists(__DIR__ . '/../includes/config.php');
echo "Config file found: " . ($configExists ? 'YES' : 'NO') . "<br>";

$dbExists = file_exists(__DIR__ . '/../includes/db.php');
echo "DB file found: " . ($dbExists ? 'YES' : 'NO') . "<br>";

echo "AMPPS Windows path exists: " . (file_exists('C:/Program Files/Ampps') ? 'YES' : 'NO') . "<br>";
echo "Current directory: " . __DIR__ . "<br>";

require_once __DIR__ . '/../includes/config.php';
echo "DB_HOST: " . DB_HOST . "<br>";
echo "DB_NAME: " . DB_NAME . "<br>";
echo "DB_USER: " . DB_USER . "<br>";
echo "DB_PASS: " . DB_PASS . "<br>";

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS
    );
    echo "✅ Database connected successfully!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>