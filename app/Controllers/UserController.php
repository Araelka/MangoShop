<?php

namespace App\Controllers;

use App\Models\Auth;
use App\Models\CsrfToken;
use App\Models\User;
use App\Validators\UserValidator;

class UserController {
    private $csrfToken;

    private $auth;

    public function __construct(){
        $this->auth = new Auth();
        $this->csrfToken = new CsrfToken();
        
    }

    public function showCreateForm(){
        if ($this->auth->check()) {
            header('Location: /');
            exit;
        }

        $csrfToken = $this->csrfToken->generateCsrfToken();
        require __DIR__ . '/../Views/auth/register.php';
    }

    public function create(){
        if ($this->auth->check()) {
            header('Location: /');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->csrfToken->validateCsrfToken($csrfToken)){
            $error = 'Неверный CSRF-токен. Попробуйте снова.';
            require __DIR__ . '/../Views/auth/register.php';
            return;
        }


        $userdata = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'confirmPassword' => $_POST['confirmPassword'],
            'role_id' => 3
        ];

        $validator = new UserValidator();

        $errors = $validator->validate($userdata);
        if (!empty($errors)) {
            $old = [
                'username' => $userdata['username'],
                'email' => $userdata['email']
            ];
            require __DIR__ . '/../Views/auth/register.php';
            return;
        }

        $user = new User();
        $userId = $user->create($userdata);

        if ($userId) {
            $this->auth->login($userId);
            header('Location: /');
            exit;
        } else {
            $error = 'Ошибка при регистрации. Попробуйте позже';
            require __DIR__ . '/../Views/auth/register.php';
            return;
        }
    }
}