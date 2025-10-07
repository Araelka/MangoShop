<?php

namespace DB;

use Config\DBConfig;
use PDO;
use PDOException;

/**
 * Singleton-класс для подключения к базе данных через PDO.
 * Гарантирует одно соединение на весь запрос.
 */
class Database {
    private static $conn;

    /**
     * Приватный конструктор дял предотвращения от создания новых классов
     */
    private function __construct() {}

    /**
     * Функция для установления содениннеия с базой данных.
     */
    public static function getConnection(){
        if (self::$conn === null){
            try {
                $conf = 'mysql:host=' . DBConfig::DB_HOST .
                ';dbname=' . DBConfig::DB_NAME . 
                ';charset=' . DBConfig::DB_CHARSET;

                self::$conn = new PDO($conf, 
                DBConfig::DB_USER, 
                DBConfig::DB_PASSWORD, 
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Ошибка соединения: " . $e->getMessage());
            }
        }

        return self::$conn;
    }
}
