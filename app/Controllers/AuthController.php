<?php

namespace App\Controllers;

use App\Models\Auth;
use App\Models\CsrfToken;
use App\Models\User;

/**
 * Контроллер для авторизации и выхода из системы.
 */
class AuthController {

    private $auth;
    private $csrfToken;

    public function __construct() {
        $this->auth = new Auth();
        $this->csrfToken = new CsrfToken();
    }

    /**
     * Отображает форму входа.
     * Перенаправляет авторизованных пользователей на главную.
     */
    public function showLoginForm() {

        if ($this->auth->check()) {
            header('Location: /');
            exit;
        }

        $csrfToken = $this->csrfToken->generateCsrfToken();
        require __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Обрабатывает попытку входа.
     * Проверяет CSRF, валидность логина/пароля и создаёт сессию.
     */
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

        $old = [
                'username' => $username,
                'password' => $password
            ];

        if (empty($username) || empty($password)) {

            $error = 'Поле "Имя пользователя" или "Пароль" не заполнено';
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $userId = $this->auth->attempt($username, $password);

        if ($userId) {
            $this->auth->login($userId);
            $user = new User();
            $user = $user->findByID($userId);
            header('Location: /');
            exit;
        } else {
            $error = 'Неверное "Имя пользователя" или "Пароль"';
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }
        
    }

     /**
     * Завершает сессию и перенаправляет на форму входа.
     */
    public function logout(){
        $this->auth->logout();
        header('Location: /login');
        exit;
    }
}