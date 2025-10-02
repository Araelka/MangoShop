<?php

namespace DB\Seeds;

use DB\Database;
use PDOException;

class UserSeeder {
    private $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function run() {
        echo "Запуск UserSeeder...\n";

        $user = [
            'username' => 'admin', 
            'email' => 'admin@ex.ex',
            'password' => password_hash('admin', PASSWORD_DEFAULT), 
            'role_id' => 1,
        ];

        try {
            $sql = $this->pdo->prepare("INSERT IGNORE INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");

            $sql->execute([$user['username'], $user['email'], $user['password'], $user['role_id']]);
        } catch (PDOException $e) {
            echo "Ошибка при выполнении UserSeeder: " . $e->getMessage() . "\n";
        }

        echo "UserSeeder завершён!\n";
    }
}