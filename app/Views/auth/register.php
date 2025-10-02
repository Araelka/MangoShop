<title>Регистрация</title>
<?php include_once __DIR__ . '/../layout/header.php' ?>

<div class="container">
    <div class="auth-form-container">
        <form action="/register" method="POST" class="auth-form" id="registerForm">
            <h1 class="page-title">Регистрация</h1>

            <?php if(isset($error)):?>
                <div class="error">
                    <?=  htmlspecialchars($error); ?>
                </div>
            <?php endif ?>

            <input type="hidden" name="csrf_token" value=<?= htmlspecialchars($csrfToken) ?>>
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" required placeholder="Имя пользователя" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                <?php if(isset($errors['username'])):?>
                    <div class="register-error">
                        <?=  htmlspecialchars($errors['username']); ?>
                    </div>
                <?php endif ?>
            </div>
            <div class="form-group">
                <label for="email">Электронная почта</label>
                <input type="email" id="email" name="email" required placeholder="Электронная почта" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <?php if(isset($errors['email'])):?>
                    <div class="register-error">
                        <?=  htmlspecialchars($errors['email']); ?>
                    </div>
                <?php endif ?>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required placeholder="******">
                <?php if(isset($errors['password'])):?>
                    <div class="register-error">
                        <?=  htmlspecialchars($errors['password']); ?>
                    </div>
                <?php endif ?>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Подтвердите пароль</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="******">
                <?php if(isset($errors['confirmPassword'])):?>
                    <div class="register-error">
                        <?=  htmlspecialchars($errors['confirmPassword']); ?>
                    </div>
                <?php endif ?>
            </div>
            <button type="submit" class="submit-btn">Зарегистрироваться</button>
        </form>
    </div>
</div>

