<?php
// Test Database Connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Connection Test</h1>";

require_once __DIR__ . '/config/db.php';

try {
    echo "<p>✅ db.php loaded successfully</p>";

    $pdo = getPDO();
    echo "<p>✅ PDO connection established</p>";

    // Test query - get database version
    $stmt = $pdo->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    echo "<p>✅ MySQL Version: " . htmlspecialchars($result['version']) . "</p>";

    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ 'users' table exists</p>";

        // Check column names
        $stmt = $pdo->query("DESCRIBE users");
        $columns = $stmt->fetchAll();
        echo "<p>📋 Users table columns:</p><ul>";
        foreach ($columns as $col) {
            echo "<li><strong>" . htmlspecialchars($col['Field']) . "</strong> (" . htmlspecialchars($col['Type']) . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>❌ 'users' table NOT found</p>";
    }

    // Check PlateBot tables
    $platebotTables = ['chat_conversations', 'chat_messages', 'user_preferences'];
    foreach ($platebotTables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p>✅ '$table' table exists</p>";
        } else {
            echo "<p>❌ '$table' table NOT found</p>";
        }
    }

    echo "<hr>";
    echo "<h2>✅ Database connection is working perfectly!</h2>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>File: " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
?>
