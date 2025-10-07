<?php

namespace App\Models;

use DB\Database;

/**
 * Модель для управления аутентификацией через сессии.
 */
class Auth {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Проверяет учётные данные и возвращает ID пользователя при успехе.
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
     * Сохраняет ID пользователя в сессии.
     */
    public function login ($userId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $userId;
    }

    /**
     * Проверяет наличие активной сессии.
     */
    public function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']); 
    }

    /**
     * Возвращает ID текущего пользователя из сессии.
     */
    public function id() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Уничтожает сессию (выход из системы).
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
    }
}