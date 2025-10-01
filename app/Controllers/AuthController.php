<?php

namespace App\Controllers;

use App\Models\Auth;
use App\Models\CsrfToket;

class AuthController {

    private $auth;
    private $csrfToken;

    public function __construct() {
        $this->auth = new Auth();
        $this->csrfToken = new CsrfToket();
    }

    public function showLoginForm() {

        if ($this->auth->check()) {
            header('Location: /');
            exit;
        }

        $csrfToken = $this->csrfToken->generateCsrfToken();
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login () {

        if ($this->auth->check()) {
            header('Location: /');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->csrfToken->validateCsrfToken($csrfToken)){
            $error = 'Неверный CSRF-токен. Попробуйте снова.';
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $username = trim($_POST['username']);
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Поле "Имя пользователя" или "Пароль" не заполнено';
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $user = $this->auth->attempt($username, $password);

        if ($user) {
            $this->auth->login($user);
            require __DIR__ . '/../Views/index.php';
        } else {
            $error = 'Неверное "Имя пользователя" или "Пароль"';
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }
        
    }

    public function logout(){
        $this->auth->logout();
        header('Location: /login');
        exit;
    }
}