<?php

namespace DB\Migrations;

use DB\Migration;

class CreateUsersTable extends Migration {
    public function up(){
        $this->createTable('users', [
            'id INT PRIMARY KEY AUTO_INCREMENT',
            'login VARCHAR(100) NOT NULL UNIQUE',
            'email VARCHAR(100) NOT NULL UNIQUE',
            'password VARCHAR(255) NOT NULL',
            'role_id INT',
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL'
        ]);
    }

    public function down(){
        $this->dropTable('users');
    }
}