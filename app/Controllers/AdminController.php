<?php

namespace App\Controllers;

use App\Models\Auth;
use App\Models\CsrfToken;
use App\Models\User;

class AdminController {
    private $csrfToken;

    private $auth;

    public function __construct(){
        $this->auth = new Auth();
        $this->csrfToken = new CsrfToken();
    }

    public function index(){

        if (!$this->auth->check()) {
            header('Location: /');
            exit;
        }

        $user = new User();

        if (!$user->isAdmin($user->findByID($this->auth->id()))) {
            header('Location: /');
            exit;
        }

        $users = $user->findALL();

        require __DIR__ . '/../Views/admin/index.php';
    }
}