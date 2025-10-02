<title>Админ-панель</title>
<?php include_once __DIR__ . '/../layout/header.php' ?>
<link rel="stylesheet" href="/css/admin.css">

<div class="container">
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Редактировать пользователя</h1>
            <a href="/admin" class="back-link">&larr; Назад к списку</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif ?>

        <form action="/admin/users/<?= htmlspecialchars($userToEdit['id']) ?>/edit" method="POST" class="edit-user-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" required 
                       value="<?= htmlspecialchars($userToEdit['username']) ?>">
                <?php if (isset($errors['username'])): ?>
                    <div class="register-error"><?= htmlspecialchars($errors['username']) ?></div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="email">Электронная почта</label>
                <input type="email" id="email" name="email" required 
                       value="<?= htmlspecialchars($userToEdit['email']) ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="register-error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="role_id">Роль</label>
                <select id="role_id" name="role_id" class="role-select">
                    <option value="1" <?= $userToEdit['role_id'] == 1 ? 'selected' : '' ?>>Администратор</option>
                    <option value="2" <?= $userToEdit['role_id'] == 2 ? 'selected' : '' ?>>Редактор</option>
                    <option value="3" <?= $userToEdit['role_id'] == 3 ? 'selected' : '' ?>>Пользователь</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Новый пароль (оставьте пустым, чтобы не менять)</label>
                <input type="password" id="password" name="password">
                <?php if (isset($errors['password'])): ?>
                    <div class="register-error"><?= htmlspecialchars($errors['password']) ?></div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Подтвердите пароль</label>
                <input type="password" id="confirmPassword" name="confirmPassword">
                <?php if (isset($errors['confirmPassword'])): ?>
                    <div class="register-error"><?= htmlspecialchars($errors['confirmPassword']) ?></div>
                <?php endif ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">Сохранить изменения</button>
                <a href="/admin" class="cancel-btn">Отмена</a>
            </div>
        </form>
    </div>
</div>