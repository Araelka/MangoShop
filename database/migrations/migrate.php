<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Config\DBConfig;
use DB\Migrations\CreateRolesTable;
use DB\Migrations\CreateUsersTable;

try {
    $conf = 'mysql:host=' . DBConfig::DB_HOST . ';charset=' . DBConfig::DB_CHARSET;
    $pdo = new PDO($conf, 
    DBConfig::DB_USER, 
    DBConfig::DB_PASSWORD, 
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DBConfig::DB_NAME . "` CHARACTER SET `" . DBConfig::DB_CHARSET . "` COLLATE utf8mb4_unicode_ci");
    echo "База данных успешно создана\n";
} catch (Exception $e) {
    die("Ошибка при создании базы данных:" . $e->getMessage() . "\n");
}

$migrations = [
    CreateRolesTable::class,
    CreateUsersTable::class
];

echo "Запуск миграции...\n";

foreach ($migrations as $migrationClass) {
    try {
        $migration = new $migrationClass();
        $migration->up();
        echo "Миграция " . basename(str_replace('\\', '/', $migrationClass)) . " успешно применена\n";
    } catch  (Exception $e) {
        echo "Ошибка при применении миграции " . basename(str_replace('\\', '/', $migrationClass)) . "\n";
        exit(1);
    }
}

echo "Все миграции успешно применены\n";