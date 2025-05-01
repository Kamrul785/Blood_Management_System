<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blood_management');
define('DB_PORT', 3307);

// Create database connection
try {
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Check connection
    if (mysqli_connect_errno()) {
        throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    
    // Set charset to utf8mb4
    mysqli_set_charset($con, "utf8mb4");
    
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?> 