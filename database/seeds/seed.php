<?php

/**
 * Сидеры для начального заполнения БД:
 * - RoleSeeder: создаёт роли (admin, editor, user)
 * - UserSeeder: создаёт учётную запись администратора (логин: admin, пароль: admin)
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use DB\Seeds\RoleSeeder;
use DB\Seeds\UserSeeder;

$seeds = [
    RoleSeeder::class,
    UserSeeder::class
];

foreach ($seeds as $seedClass) {
    try {
        $seed = new $seedClass();
        $seed->run();
    } catch  (Exception $e) {
        exit(1);
    }
}