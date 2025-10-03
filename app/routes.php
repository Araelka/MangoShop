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

    $userId = $auth->id();

    if (!$auth->check() || !$user->isAdmin($user->findByID($userId))) {
        header('Location: /');
        exit;
    }

    $controller = new AdminController();
    $controller->index();

}

elseif ($url === "/admin/users/create") {
    $auth = new Auth();
    $user = new User();

    $userId = $auth->id();

    if (!$auth->check() || !$user->isAdmin($user->findByID($userId))) {
        header('Location: /');
        exit;
    }

    $controller = new AdminController();

    if ($method === 'POST') {
        $controller->userCreate();
    } else {
        $controller->showUserCreateForm();
    }
}

elseif (preg_match('#^/admin/users/(\d+)/edit$#', $url, $matches)) {

    $auth = new Auth();
    $user = new User();

    $userId = $auth->id();

    if (!$auth->check() || !$user->isAdmin($user->findByID($userId))) {
        header('Location: /');
        exit;
    }

    $userId = $matches[1];

    $controller = new AdminController();

    if ($method === 'POST') {
        $controller->userUpdate($userId);
    } else {
        $controller->showUserEditForm($userId);
    }
}

elseif (preg_match('#^/admin/users/(\d+)/delete$#', $url, $matches) && $method === 'POST') {
    $auth = new Auth();
    $user = new User();

    $userId = $auth->id();

    if (!$auth->check() || !$user->isAdmin($user->findByID($userId))) {
        header('Location: /');
        exit;
    }

    $userId = $matches[1];

    $controller = new AdminController();
    $controller->delete($userId);
}

else {
    http_response_code(404);
    echo "Page not found";
}