<?php

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