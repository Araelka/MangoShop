<?php

namespace App\Models;

use DB\Database;
use PDO;
use PDOException;

class User {
    private $pdo;

    private $fields = [
        'username',
        'email',
        'password',
        'role_id'
    ];

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function findALL($sortField = 'id', $sortOrder = 'ASC'){
        $sqlQuery = "
            SELECT user.* ,
            role.description as role_name
            FROM users user
            LEFT JOIN roles role ON user.role_id = role.id
            ORDER BY user.{$sortField} {$sortOrder}
        ";

        $sql = $this->pdo->prepare($sqlQuery);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Находит пользователя по имени пользователя
     */
    public function findByUserName ($username) {
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $sql->execute([$username, $username]);

        return $sql->fetch();
    }

    /**
     * Находит пользователя по ID
     */
    public function findByID ($userId) {
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $sql->execute([$userId]);

        return $sql->fetch();
    }

    /**
     * Проверяет, существует ли пользователь с таким логином
     */
    public function existsByUserName ($username) {
        $sql = $this->pdo->prepare("SELECT 1 FROM users WHERE username = ? LIMIT 1");
        $sql->execute([$username]);
        return $sql->fetchColumn();
    }

    /**
     * Проверяет, существует ли пользователь с таким email
     */
    public function existsByEmail ($email) {
        $sql = $this->pdo->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
        $sql->execute([$email]);
        return $sql->fetchColumn();
    }

    /**
     * Проверяет, является ли пользователь администратором
     */
    public function isAdmin($user) {
        return is_array($user) && isset($user['role_id']) && $user['role_id'] == 1;
    }

    /**
     * Проверяет, является ли пользователь редактором
     */
    public function isEditor($user) {
        return is_array($user) && isset($user['role_id']) && $user['role_id'] == 2;
    }

    /**
     * Создаёт нового пользователя
     */
    public function create($userdata){

        foreach ($this->fields as $field) {
            if (!isset($userdata[$field]) || empty($userdata[$field])){
                error_log("Поле '$field' обязательно для создания пользователя");
                return false;
            }
        }

        try {
            $sql = $this->pdo->prepare("
                INSERT INTO users (username, email, password, role_id, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ");

            $result =  $sql->execute([
                $userdata['username'],
                $userdata['email'],
                password_hash($userdata['password'], PASSWORD_DEFAULT),
                $userdata['role_id']
            ]);

            if ($result) {
                return $this->pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            error_log("Ошибка создания пользователя: " . $e->getMessage());
            return false;
        }
    }
}