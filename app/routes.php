<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Models\Auth;
use App\Models\User;

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
    $controller = new UserController();
    if ($method === 'POST'){
        $controller->create();
    } else {
        $controller->showCreateForm();
    }
} 

elseif ($url === '/admin') {
    $user = new User();
    $auth = new Auth();

    if ($user->isAdmin($user->findByID($auth->id()))) {
        $controller = new AdminController();
        $controller->index();
    } else {
        header('Location: /');
        exit;
    }

    
}

else {
    http_response_code(404);
    echo "Page not found";
}