<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магический Магазин — Волшебные Вещи для Всех</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php 
    $auth = new \App\Models\Auth();
    $isAuth = $auth->check();
    ?>

    <header class="header">
        <div class="container">
            <a class="logo" href="/" class="auth-link">Хвост дракона</a>  
            <div class="auth-cart">
                <a href="#" class="cart-link">🛒 Корзина (0)</a>
                <?php if($isAuth): ?>
                    <a href="/logout" class="auth-link">Выйти</a>    
                <?php else: ?>
                    <a href="/login" class="auth-link">Войти</a>   
                <?php endif ?>
            </div>
        </div>
    </header>

    <main class="main-content"></main>