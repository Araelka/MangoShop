<?php include_once __DIR__ . '/../layout/header.php' ?>

<div class="container">
    <h1 class="page-title">📝 Регистрация нового пользователя</h1>
    <div class="auth-form-container">
        <form class="auth-form" id="registerForm">
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" required placeholder="Ваше имя или ник">
            </div>
            <div class="form-group">
                <label for="email">Электронная почта</label>
                <input type="email" id="email" name="email" required placeholder="Электронная почта">
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required placeholder="******">
            </div>
            <div class="form-group">
                <label for="confirmPassword">Подтвердите пароль</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="******">
            </div>
            <button type="submit" class="submit-btn">Зарегистрироваться</button>
        </form>
    </div>
</div>

