<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_plate_db');
define('DB_USER', 'root');

if (file_exists('/Applications/AMPPS')) {
    // AMPPS detected
    define('DB_PASS', 'mysql');
} elseif (file_exists('/Applications/MAMP')) {
    // MAMP detected
    define('DB_PASS', 'root');
} else {
    // Default to XAMPP (empty password)
    define('DB_PASS', '');
}
