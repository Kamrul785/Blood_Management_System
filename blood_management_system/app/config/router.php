<?php
// Define routes
$routes = [
    'home' => [
        'controller' => 'HomeController',
        'action' => 'index'
    ],
    'login' => [
        'controller' => 'AuthController',
        'action' => 'login'
    ],
    'register' => [
        'controller' => 'AuthController',
        'action' => 'register'
    ],
    'logout' => [
        'controller' => 'AuthController',
        'action' => 'logout'
    ],
    'profile' => [
        'controller' => 'UserController',
        'action' => 'profile'
    ],
    'dashboard' => [
        'controller' => 'DashboardController',
        'action' => 'index'
    ],
    'donate' => [
        'controller' => 'DonationController',
        'action' => 'index'
    ],
    'request' => [
        'controller' => 'RequestController',
        'action' => 'index'
    ]
];

// Function to get the current route
function getCurrentRoute() {
    global $routes;
    $route = $_GET['route'] ?? 'home';
    return $routes[$route] ?? $routes['home'];
}

// Function to generate URL
function url($route, $params = []) {
    $queryString = !empty($params) ? '&' . http_build_query($params) : '';
    return APP_URL . '/index.php?route=' . $route . $queryString;
}

// Function to redirect
function redirect($route, $params = []) {
    header('Location: ' . url($route, $params));
    exit;
} 