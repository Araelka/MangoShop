<?php

use App\Controllers\AuthController;

if (!defined('ROUTES_LOADED')) {
    die('Доступ запрещён');
}

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($url === '/') {
    require __DIR__ . '/../app/Views/index.php';
}

elseif ($url === '/login') {
    $controller = new AuthController();
    if ($method === "POST") {
        $controller->login();
    } else {
        $controller->showLoginForm();
    }
} 

elseif ($url === '/logout') {
    $controller = new AuthController();
    $controller->logout();
}

elseif ($url === '/register') {
    require __DIR__ . '/../app/Views/auth/register.php';
} 


else {
    http_response_code(404);
    echo "Page not found";
}