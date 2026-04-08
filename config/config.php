<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_plate_db');
define('DB_USER', 'root');

if (file_exists('C:\xampp')) {
    // XAMPP on Windows
    $db_pass = '';
    $db_port = '3306';
} 
elseif (file_exists('C:\Program Files\Ampps')) {
    // AMPPS on Windows
    $db_pass = 'mysql';
    $db_port = '3306';
} 
elseif (file_exists('/Applications/AMPPS')) {
    // AMPPS on macOS
    $db_pass = 'mysql';
    $db_port = '3306';
} 
elseif (file_exists('/Applications/MAMP')) {
    // MAMP on macOS
    $db_pass = 'root';
    $db_port = '8889';
} 
else {
    // Default (e.g. XAMPP)
    $db_pass = '';
    $db_port = '3306';
}

//Define Constants
define('DB_PASS', $db_pass);
define('DB_PORT', $db_port);