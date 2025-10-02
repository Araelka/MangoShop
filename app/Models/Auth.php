<?php

namespace App\Models;

use DB\Database;

class Auth {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Проверяет логин и пароль, возвращает ID пользователя или false
     * @param string $username Имя пользователя или электронная почта
     * @param string $password Пароль пользователя
     */
    public function attempt ($username, $password) {
        $user = new User();
        $user = $user->findByUserName($username);

        if ($user && password_verify($password, $user['password'])) {
            return $user['id'];
        }

        return false;
    }

    /**
     * Авторизует пользователя по ID
     * @param int $userId ID пользователя
     */
    public function login ($userId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $userId;
    }

    /**
     *  Проверяет, авторизован ли пользователь
     */
    public function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']); 
    }

    /**
     * Возвращает ID текущего пользователя или null
     */
    public function id() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Завершает сессию (выход)
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
    }
}