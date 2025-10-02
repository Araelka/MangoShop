<title>Админ-панель</title>
<?php include_once __DIR__ . '/../layout/header.php' ?>
<link rel="stylesheet" href="css/admin.css">


<div class="container">
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Админ-панель</h1>
        </div>

        <!-- Табы -->
        <div class="table-tabs-menu">
        <div class="table-tabs">
            <a class="table-tab active">Пользователи</a>
            <!-- Позже можно добавить другие табы -->
            <!-- <div class="table-tab">Товары</div> -->
            <!-- <div class="table-tab">Заказы</div> -->
        </div>

        <div class="table-tabs">
            <a class="table-tab active">Создать</a>
        </div>
        </div>

        <!-- Таблица пользователей -->
        <div class="table-content">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="role-badge role-<?= htmlspecialchars($user['role_id']) ?>">
                                <?= ucfirst(htmlspecialchars($user['role_name'])) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                            <a href="/admin/user/<?= htmlspecialchars($user['id']) ?>/edit" class="action-btn edit-btn">✏️ Редактировать</a>
                            <button class="action-btn delete-btn">🗑 Удалить</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
