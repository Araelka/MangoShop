<?php

namespace App\Models;

/**
 * Генерация и валидация CSRF-токенов для защиты от межсайтовой подделки запросов.
 */
class CsrfToken {
    
    /**
     * Генерирует и сохраняет токен в сессии, если его ещё нет.
     */
    public function generateCsrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Безопасно сравнивает переданный токен с сохранённым в сессии.
     */
    public function validateCsrfToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}