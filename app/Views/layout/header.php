<?php 

use App\Models\Auth;
use App\Models\User;

$auth = new Auth();
$userModel = new User();
$user = $userModel->findByID($auth->id());
$isAdmin = $userModel->isAdmin($user);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лавка дракона</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Cormorant+Garamond:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/main.js"></script> 
</head>
<body>



    <header class="header">
        <div class="container">
            <a class="logo" href="/" class="auth-link">Лавка дракона</a>  
            <div class="auth-cart">
                <?php if(isset($user) && !empty($user)): ?>
                    <a href="#" class="cart-link">
                        <svg class="cart-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M5 4H7L8.5 2H15.5L17 4H19V6H5V4ZM19 8H5L4 20H20L19 8Z"/>
                        </svg>
                        Корзина (0)
                    </a>
                    <div class="user-menu-container">
                        <a role="button" class="user-menu-button">
                            <?= htmlspecialchars($user['username']) ?> ▾
                        </a>
                        <div class="user-dropdown-menu">
                            <?php if($isAdmin): ?>
                                <a href="/admin" class="dropdown-item">Админ-панель</a>
                            <?php endif ?>
                            <a href="#" class="dropdown-item">Настройки</a>
                            <a href="#" class="dropdown-item">Заказы</a>
                            <a href="/logout" class="dropdown-item">Выйти</a>
                        </div>
                    </div> 
                <?php else: ?>
                    <a href="/login" class="auth-link">Войти</a>   
                <?php endif ?>
            </div>
        </div>
    </header>

    <main class="main-content">