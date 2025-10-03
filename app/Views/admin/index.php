<title>Админ-панель</title>
<?php include_once __DIR__ . '/../layout/header.php' ?>
<link rel="stylesheet" href="/css/admin.css">


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
            <a href="/admin/users/create" class="table-tab active">Создать</a>
        </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif ?>

        <!-- Таблица пользователей -->
        <div class="table-content">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>
                            <a href="?sort=id&order=<?= $sortField == 'id' && $sortOrder == 'ASC' ? 'DESC' : 'ASC' ?>">
                            ID <?= $sortField == 'id' ? ($sortOrder == "ASC" ? '↓' : '↑') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=username&order=<?= $sortField == 'username' && $sortOrder == 'ASC' ? 'DESC' : 'ASC' ?>">
                            Имя <?= $sortField == 'username' ? ($sortOrder == "ASC" ? '↓' : '↑') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=email&order=<?= $sortField == 'email' && $sortOrder == 'ASC' ? 'DESC' : 'ASC' ?>">
                            Email <?= $sortField == 'email' ? ($sortOrder == "ASC" ? '↓' : '↑') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=role_id&order=<?= $sortField == 'role_id' && $sortOrder == 'ASC' ? 'DESC' : 'ASC' ?>">
                            Роль <?= $sortField == 'role_id' ? ($sortOrder == "ASC" ? '↓' : '↑') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=created_at&order=<?= $sortField == 'created_at' && $sortOrder == 'ASC' ? 'DESC' : 'ASC' ?>">
                            Дата регистрации <?= $sortField == 'created_at' ? ($sortOrder == "ASC" ? '↓' : '↑') : '' ?>
                            </a>
                        </th>
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
                            <div class="action-btn-td">
                            <a href="/admin/users/<?= htmlspecialchars($user['id']) ?>/edit" class="action-btn edit-btn">
                                <svg width="20px" height="20px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M470.7 20L368.2 49.81l41.5-28.09c-26.2 5.92-59.3 17.5-100.9 36.19l-67.9 70.79L265 79.25c-23.3 12.96-48 29.95-71.8 49.85l-15.8 64.3-3.4-47.6c-23.5 21.6-45.6 45.6-63.9 70.9-19.23 26.5-34.26 54.5-41.79 82.4l-28.12-18.8c2.52 23.7 10.31 44.3 23.09 63.2l-33.62-10.3c7.64 23.5 20.13 38.7 41.25 51-11.83 33.3-17.38 68.1-23.34 102.8l18.4 3.1C87.31 277.4 237.9 141.8 374 81.72l6.9 17.38c-121.7 54.5-216.3 146.5-265.8 279.1 18.1.1 35.8-2.1 52.2-6.3l4.9-60.9 13.1 55.5c10.9-4 20.9-8.8 29.8-14.4l-20.7-43.5 32.8 34.8c8-6.4 14.6-13.6 19.6-21.5 30.4-47.5 62.2-94.7 124.8-134.2l-45.7-16.2 70.1 2.1c11.4-5.8 23.4-12.9 32.5-19.6l-49.7-4 74.7-17.6c5.8-5.8 11.2-11.9 16.1-18 17.3-21.94 29-44.78 26.2-65.55-1.3-10.39-7.5-20.16-17.6-25.63-2.5-1.3-5.2-2.45-7.5-3.22z"/></svg>
                                Редактировать</a>
                            <form action="/admin/users/<?= htmlspecialchars($user['id']) ?>/delete" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                <button class="action-btn delete-btn">
                                    <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M12,2 C17.5228475,2 22,6.4771525 22,12 C22,17.5228475 17.5228475,22 12,22 C6.4771525,22 2,17.5228475 2,12 C2,6.4771525 6.4771525,2 12,2 Z M12,4 C7.581722,4 4,7.581722 4,12 C4,16.418278 7.581722,20 12,20 C16.418278,20 20,16.418278 20,12 C20,7.581722 16.418278,4 12,4 Z M7.29325,7.29325 C7.65417308,6.93232692 8.22044527,6.90456361 8.61296051,7.20996006 L8.70725,7.29325 L12.00025,10.58625 L15.29325,7.29325 C15.68425,6.90225 16.31625,6.90225 16.70725,7.29325 C17.0681731,7.65417308 17.0959364,8.22044527 16.7905399,8.61296051 L16.70725,8.70725 L13.41425,12.00025 L16.70725,15.29325 C17.09825,15.68425 17.09825,16.31625 16.70725,16.70725 C16.51225,16.90225 16.25625,17.00025 16.00025,17.00025 C15.7869167,17.00025 15.5735833,16.9321944 15.3955509,16.796662 L15.29325,16.70725 L12.00025,13.41425 L8.70725,16.70725 C8.51225,16.90225 8.25625,17.00025 8.00025,17.00025 C7.74425,17.00025 7.48825,16.90225 7.29325,16.70725 C6.93232692,16.3463269 6.90456361,15.7800547 7.20996006,15.3875395 L7.29325,15.29325 L10.58625,12.00025 L7.29325,8.70725 C6.90225,8.31625 6.90225,7.68425 7.29325,7.29325 Z"/>
                                    </svg>
                                Удалить</button>
                            </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div>
                <div class="pagination">
                    <?php for ($i=1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="page active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>&sort=<?= $sortField ?>&order=<?= $sortOrder ?>&limit=<?= $limit ?>" class="page"><?= $i ?></a>
                        <?php endif ?>
                    <?php endfor ?>
                </div>

                <div>
                    <div>
                        На странице:
                        <?php foreach([5, 10, 20, 50, 100] as $lim): ?>
                            <a href="?sort=<?= $sortField ?>&order=<?= $sortOrder ?>&limit=<?= $lim ?>"><?= $lim ?></a>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
