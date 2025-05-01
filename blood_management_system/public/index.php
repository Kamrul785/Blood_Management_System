<?php
// Define the base path
define('BASE_PATH', dirname(__DIR__));

// Load configuration
require_once BASE_PATH . '/app/config/config.php';

// Load helper functions
require_once BASE_PATH . '/app/helpers/functions.php';

// Start session
session_start();

// Load the router
require_once BASE_PATH . '/app/config/router.php';

// Get the requested route
$route = $_GET['route'] ?? 'home';

// Include the appropriate controller
$controllerFile = BASE_PATH . '/app/controllers/' . $route . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
} else {
    // Handle 404 error
    header("HTTP/1.0 404 Not Found");
    require_once BASE_PATH . '/app/views/errors/404.php';
} 