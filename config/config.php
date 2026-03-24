<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_plate_db');
define('DB_USER', 'root');
/*define('DB_PASS', 'root');*/
define('DB_PORT', '8889');

if (file_exists('C:\Program Files\Ampps')) {
    // AMPPS on Windows
    // define('DB_PASS', 'mysql');
    $db_pass = 'mysql';
} elseif (file_exists('/Applications/AMPPS')) {
    // AMPPS on macOS
    // define('DB_PASS', 'mysql');
    $db_pass = 'mysql';
} elseif (file_exists('/Applications/MAMP')) {
    // MAMP on macOS
    //define('DB_PASS', 'root');
    $db_pass = 'root';
} else {
    // Default (e.g. XAMPP)
    //define('DB_PASS', '');
    $db_pass = '';
}

define('DB_PASS', $db_pass);