<?php

namespace DB\Seeds;

use DB\Database;
use PDOException;

class RoleSeeder {
    private $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function run() {
        echo "Запуск RoleSeeder...\n";

        $roles = [
            ['name' => 'admin', 'description' => 'Администратор'],
            ['name' => 'user', 'description' => 'Пользователь']
        ];

        try {
            $sql = $this->pdo->prepare("INSERT IGNORE INTO roles (name, description) VALUES (?, ?)");

            foreach ($roles as $role) {
                $sql->execute([$role['name'], $role['description']]);
            }
        } catch (PDOException $e) {
            echo "Ошибка при выполнении RoleSeeder: " . $e->getMessage() . "\n";
        }

        echo "RoleSeeder завершён!";
    }
}