<?php

namespace DB;

use DB\Database;
use PDO;
use PDOException;

/**
 * Базовый класс для миграций.
 * Предоставляет методы для создания и удаления таблиц.
 */
class Migration {
    protected PDO $pdo;

    /**
     * Получение соединения с базой данных для миграций.
     * @return PDO Соединение с базой данных.
     */
    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    /**
     * Функция для создания новой таблицы
     * @param string $tableName Имя таблицы.
     * @param array $columns Колонки таблицы.
     * @param string $options Дополнительные параметры таблицы.
     */
    public function createTable($tableName, $columns, $options = '') {
        $columnsSql = implode(', ', $columns);
        $sql = "CREATE TABLE IF NOT EXISTS  `$tableName` ($columnsSql) $options";
        try {
            $this->pdo->exec($sql);
            echo "Таблица '$tableName' была успешно создана\n";
        } catch (PDOException $e){
            echo "Ошибка при создании таблицы '$tableName': " . $e->getMessage();
        }
    }

    /**
     * Функция для удадения таблицы.
     * @param string $tableName Имя таблицы
     */
    public function dropTable($tableName){
        $sql = "DROP TABLE IF EXISTS `$tableName`";
        $this->pdo->exec($sql);
        try {
            echo "Таблица '$tableName' была успешно удалена\n";
        } catch (PDOException $e){
            echo "Ошибка при создании таблицы '$tableName': " . $e->getMessage();
        }
    }

}

