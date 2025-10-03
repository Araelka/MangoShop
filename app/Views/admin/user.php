<?php if(isset($user)): ?>
    <title>Редактирование пользователя <?= $user['username'] ?></title>
<?php else: ?>
    <title>Создание пользователя</title>
<?php endif ?>

<?php include_once __DIR__ . '/../layout/header.php' ?>
<link rel="stylesheet" href="/css/admin.css">

<div class="container">
    <div class="admin-container">
        <div class="admin-header">
            <?php if(isset($user)): ?>
                <h1 class="admin-title">Редактирование пользователя</h1>
            <?php else: ?>
                <h1 class="admin-title">Создание пользователя</h1>
            <?php endif ?>

            <a href="/admin" class="back-link">&larr; Назад к списку</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif ?>

        
        <form action="<?= isset($user) ? '/admin/users/' . htmlspecialchars($user['id']) . '/edit' : '/admin/users/create' ?>"
        method="POST" class="edit-user-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" required 
                       value="<?= isset($user) ? htmlspecialchars($user['username']) : ' ' ?>">
                <?php if (isset($errors['username'])): ?>
                    <div class="register-error"><?= htmlspecialchars($errors['username']) ?></div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="email">Электронная почта</label>
                <input type="email" id="email" name="email" required 
                       value="<?= isset($user) ? htmlspecialchars($user['email']) : ' ' ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="register-error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="role_id">Роль</label>
                <select id="role_id" name="role_id" class="role-select">
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= isset($user) ? $user['role_id'] == $role['id'] ? 'selected' : '' : '' ?>><?= htmlspecialchars($role['description']) ?></option>
                    <?php endforeach ?>
                </select>
                <?php if (isset($errors['role'])): ?>
                    <div class="register-error"><?= htmlspecialchars($errors['role']) ?></div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="password">Пароль (оставьте пустым, чтобы не менять)</label>
                <input type="password" id="password" name="password">
                <?php if (isset($errors['password'])): ?>
                    <div class="register-error"><?= htmlspecialchars($errors['password']) ?></div>
                <?php endif ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">Сохранить</button>
                <a href="/admin" class="cancel-btn">Отмена</a>
            </div>
        </form>
    </div>
</div>