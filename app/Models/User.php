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

    public function findALL($sortField = 'id', $sortOrder = 'ASC', $limit = null, $offset = null){

        $sortFields = ['id', 'username', 'email', 'role_id', 'created_at',];
        $sortOrders = ['ASC', 'DESC'];

        if (!in_array($sortField, $sortFields)){
            $sortField = 'id';
        }

        if (!in_array(strtoupper($sortOrder), $sortOrders)) {
            $sortOrder = 'ASC';
        }

        $sqlQuery = "
            SELECT user.* ,
            role.description as role_name
            FROM users user
            LEFT JOIN roles role ON user.role_id = role.id
            ORDER BY user.{$sortField} {$sortOrder}
        ";

        if ($limit != null) {
            $sqlQuery .= " LIMIT {$limit}";
            if ($offset != null) {
                $sqlQuery .= " OFFSET {$offset}";
            }
        }

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

    public function findAllRoles() {
        $sql = $this->pdo->prepare("SELECT * FROM roles");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Проверяет, существует ли пользователь с таким логином
     */
    public function existsByUserName ($username) {
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $sql->execute([$username]);
        return $sql->fetchColumn();
    }

    /**
     * Проверяет, существует ли пользователь с таким email
     */
    public function existsByEmail ($email) {
        $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
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
        return is_array($user) && isset($user['role_id']) && ($user['role_id'] == 2 || $user['role_id'] == 1 );
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

    public function update($userdata) {
        try {
            $acceptableFields = ['username', 'email', 'role_id'];
            $fields = [];
            $values = [];

            foreach ($acceptableFields as $field){
                if(isset($userdata[$field])){
                    $fields[] = "$field = ?";
                    $values[] = $userdata[$field];
                    }
                }

            if (isset($userdata['password'])) {
                $fields[] = "password = ?";
                $values[] = password_hash($userdata['password'], PASSWORD_DEFAULT);
            }

            $fields[] = "updated_at = NOW()";
            $values[] = $userdata['id'];

            $sqlQuery = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
            print_r($userdata);
            print_r($sqlQuery);
            $sql = $this->pdo->prepare($sqlQuery);
            $sql->execute($values);
            return true;
        } catch (PDOException $e) {
            error_log("Ошибка обновлении данных пользователя: " . $e->getMessage());
            return false;
        }
    }

    public function delete ($userId) {
        try {
            $sql = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $sql->execute([$userId]);
            return true;
        } catch (PDOException $e) {
            error_log("Ошибка удалении пользователя: " . $e->getMessage());
            return false;
        }
    }

    public function count() {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as count FROM users");
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}