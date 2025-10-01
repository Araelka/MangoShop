<?php include_once __DIR__ . '/../layout/header.php' ?>


<div class="container">
    <h1 class="page-title">Авторизация</h1>
    <div class="auth-form-container">
        <form action="/login" method="POST" class="auth-form" id="loginForm">

            <?php if(isset($error)):?>
                <div class="error">
                    <?=  htmlspecialchars($error); ?>
                </div>
            <?php endif ?>

            <input type="hidden" name="csrf_token" value=<?= htmlspecialchars($csrfToken) ?>>
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" required placeholder="Имя пользователя">
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required placeholder="******">
            </div>
            <button type="submit" class="submit-btn">Войти</button>
        </form>
        <div class="auth-links">
            <a href="/register">Ещё нет аккаунта? Зарегистрируйтесь</a>
        </div>
    </div>
</div>

