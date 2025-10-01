<?php

namespace DB\Migrations;

use DB\Migration;

class CreateRolesTable extends Migration {
    public function up(){
        $this->createTable('roles', [
            'id INT PRIMARY KEY AUTO_INCREMENT',
            'name VARCHAR(50) NOT NULL UNIQUE',
            'description VARCHAR(50)'
        ]);
    }

    public function down(){
        $this->dropTable('roles');
    }
}