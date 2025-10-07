<?php

namespace App\Controllers;

use App\Models\Auth;
use App\Models\CsrfToken;
use App\Models\User;
use App\Validators\UserValidator;

/**
 * Контроллер для управления пользователями в админ-панели.
 * Доступ только для авторизованных пользователей с ролью "администратор" (role_id = 1).
 */
class AdminController {
    private $csrfToken;

    private $auth;

    public function __construct(){
        $this->auth = new Auth();
        $this->csrfToken = new CsrfToken();
    }

    /**
     * Отображает список всех пользователей с пагинацией и сортировкой.
     * Проверяет права доступа и генерирует CSRF-токен для форм.
     */
    public function index(){

        $user = new User();

        if (!$this->auth->check() || !$user->isAdmin($user->findByID($this->auth->id()))) {
            header('Location: /');
            exit;
        }

        $sortFields = ['id', 'username', 'email' ,'role_id', 'created_at'];
        $sortOrders = ['ASC', 'DESC'];

        $sortField = $_GET['sort'] ?? 'id';
        $sortOrder = $_GET['order'] ?? 'ASC';
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = min(100, intval($_GET['limit'] ?? 5));
        $offset = ($page - 1) * $limit;

        if (!in_array($sortField, $sortFields)) {
            $sortField = 'id';
        }

        if (!in_array($sortOrder, $sortOrders)) {
            $sortOrder = 'ASC';
        }

        $usersCount = $user->count();

        $totalPages = ceil($usersCount['count'] / $limit);

        $users = $user->findALL($sortField, $sortOrder, $limit, $offset);
        $csrfToken = $this->csrfToken->generateCsrfToken();

        require __DIR__ . '/../Views/admin/index.php';
    }

    /**
     * Отображает форму редактирования пользователя по ID.
     * Защищает от изменения суперпользователя (ID = 1) обычными админами.
     */
    public function showUserEditForm ($userId) {

        $userModel = new User();

        if (!$this->auth->check() || !$userModel->isAdmin($userModel->findByID($this->auth->id()))) {
            header('Location: /');
            exit;
        }


        $user = $userModel->findByID($userId);

        if ($user) {
            $csrfToken = $this->csrfToken->generateCsrfToken();
            $roles = $userModel->findAllRoles();
            require __DIR__ . '/../Views/admin/user.php';
        } else {
            header('Location: /admin');
            exit;
        }
    }


    /**
     * Обрабатывает обновление данных пользователя.
     * Валидирует входные данные, проверяет CSRF и права доступа.
     */
    public function userUpdate($userId) {
        $userModel = new User();

        if (!$this->auth->check() || !$userModel->isAdmin($userModel->findByID($this->auth->id()))) {
            header('Location: /');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin');
            exit;
        }

        $user = $userModel->findByID($userId);
        $currentUser = $userModel->findByID($this->auth->id());
        $roles = $userModel->findAllRoles();

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->csrfToken->validateCsrfToken($csrfToken)){
            $error = 'Неверный CSRF-токен. Попробуйте снова.';
            require __DIR__ . '/../Views/admin/user.php';
            return;
        }

        if ($user['id'] == 1 && $currentUser != 1) {
            $error = 'Недостаточно прав для изменения пользователя.';
            require __DIR__ . '/../Views/admin/user.php';
            return;
        }

        $userdata = [
            'id' => $userId,
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'role_id' => $_POST['role_id']
        ];

        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $userdata['password'] = $_POST['password'];
            $userdata['confirmPassword'] = $_POST['password'];
        }

        $validator = new UserValidator();

        $errors = $validator->updateValidate($userdata);

        if (!empty($errors)) {
            require __DIR__ . '/../Views/admin/user.php';
            return;
        }

        if ($userModel->update($userdata)) {
            header('Location: /admin');
            exit;
        } else {
            $error = 'Ошибка при изменении пользователя. Попробуйте позже';
            require __DIR__ . '/../Views/admin/user.php';
            return;
        }
    }

    /**
     * Отображает форму создания нового пользователя.
     */
    public function showUserCreateForm () {

        $userModel = new User();

        if (!$this->auth->check() || !$userModel->isAdmin($userModel->findByID($this->auth->id()))) {
            header('Location: /');
            exit;
        }

        $csrfToken = $this->csrfToken->generateCsrfToken();
        $roles = $userModel->findAllRoles();
        require __DIR__ . '/../Views/admin/user.php';

    }

    /**
     * Обрабатывает создание нового пользователя.
     * Валидирует данные и сохраняет в БД.
     */
    public function userCreate() {
        $userModel = new User();

        if (!$this->auth->check() || !$userModel->isAdmin($userModel->findByID($this->auth->id()))) {
            header('Location: /');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin');
            exit;
        }

        $roles = $userModel->findAllRoles();

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->csrfToken->validateCsrfToken($csrfToken)){
            $error = 'Неверный CSRF-токен. Попробуйте снова.';
            require __DIR__ . '/../Views/admin/user.php';
            return;
        }

        $userdata = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'role_id' => $_POST['role_id'],
            'password' => $_POST['password'],
            'confirmPassword' => $_POST['password']
        ];

        $validator = new UserValidator();

        $errors = $validator->validate($userdata);

        if (!empty($errors)) {
            require __DIR__ . '/../Views/admin/user.php';
            return;
        }

        if ($userModel->create($userdata)) {
            header('Location: /admin');
            exit;
        } else {
            $error = 'Ошибка при изменении пользователя. Попробуйте позже';
            require __DIR__ . '/../Views/admin/user.php';
            return;
        }
    }

    /**
     * Удаляет пользователя по ID.
     * Запрещает удаление себя и суперпользователя (ID = 1).
     */
    public function delete ($userId) {
        $userModel = new User();

        if (!$this->auth->check() || !$userModel->isAdmin($userModel->findByID($this->auth->id()))) {
            header('Location: /');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin');
            exit;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->csrfToken->validateCsrfToken($csrfToken)){
            $error = 'Неверный CSRF-токен. Попробуйте снова.';
            $users = $userModel->findALL();
            require __DIR__ . '/../Views/admin/index.php';
            return;
        }

        $user = $userModel->findByID($userId);
        $currentUser = $userModel->findByID($this->auth->id());

        if ($user && $user['id'] != $currentUser['id'] && $user['id'] != 1) {
            if ($userModel->delete($userId)){
                header('Location: /admin');
                exit;
            } else {
            $error = 'Ошибка при удалении пользователя. Попробуйте позже';
            require __DIR__ . '/../Views/admin/user.php';
            return;
            }
        } else {
            $users = $userModel->findALL();
            $error = 'Недостаточно прав для удаления пользователя.';
            require __DIR__ . '/../Views/admin/index.php';
            return;
        }
    }

}