<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Nutrition Explorer</h1>";

// Check API key
echo "<h2>1. Checking API Keys</h2>";
$apiFile = __DIR__ . '/config/api-keys.php';
if (file_exists($apiFile)) {
    echo "✅ api-keys.php exists<br>";
    require_once $apiFile;

    if (defined('FDC_API_KEY')) {
        echo "✅ FDC_API_KEY defined: " . substr(FDC_API_KEY, 0, 10) . "...<br>";
    } else {
        echo "❌ FDC_API_KEY NOT defined<br>";
    }
} else {
    echo "❌ api-keys.php NOT found<br>";
}

// Check if nutrition-explorer.php exists
echo "<h2>2. Checking File</h2>";
$nutritionFile = __DIR__ . '/Nutrition Explore Page/nutrition-explorer.php';
if (file_exists($nutritionFile)) {
    echo "✅ nutrition-explorer.php exists<br>";
} else {
    echo "❌ nutrition-explorer.php NOT found<br>";
}

// Try to load it
echo "<h2>3. Loading nutrition-explorer.php...</h2>";
try {
    ob_start();
    require_once $nutritionFile;
    $output = ob_get_clean();
    echo "✅ File loaded successfully!<br>";
    echo "<hr>";
    echo $output;
} catch (Exception $e) {
    ob_end_clean();
    echo "❌ ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>