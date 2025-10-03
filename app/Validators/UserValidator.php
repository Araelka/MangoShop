<?php

namespace App\Validators;

use App\Models\User;

class UserValidator {
    private $fields = [
        'username' => 'Имя пользователя',
        'email' => 'Электронная почта',
        'password' => 'Пароль',
        'confirmPassword' => 'Подтвердите пароль',
        'role_id' => 'Роль'
    ];

    private $errors = [];

    public function validate($userdata){

        $user = new User();

        foreach ($this->fields as $field => $fieldName) {
            if (!isset($userdata[$field]) || empty($userdata[$field])){
                $this->errors[$field] = "Поле '$fieldName' обязательно для заполнения";
            }
        }

        if (isset($userdata['username'])) {
            if (strlen($userdata['username']) < 3) {
                $this->errors['username'] = "Имя пользователя должно быть больше 3 символов";
            } elseif (strlen($userdata['username']) > 255) {
                $this->errors['username'] = "Имя пользователя не должно быть больше 255 символов";
            } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $userdata['username'])) {
                $this->errors['username'] = "Имя пользователя может содержать только буквы, цифры, подчёркивание и дефис";
            } elseif ($user->existsByUserName($userdata['username'])) {
                $this->errors['username'] = 'Пользователь с таким логином уже существует';
            }
        }

        if (isset($userdata['email'])) {
            if (strlen($userdata['email']) > 255) {
                $this->errors['email'] = "Электронная почта не должна быть больше 255 символов";
            } elseif (!filter_var($userdata['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = "Неверный формат электронной почты";
            } elseif ($user->existsByEmail($userdata['email'])) {
                $this->errors['email'] = 'Пользователь с такой электронной почтой уже существует';
            }
        }

        if (isset($userdata['password'])) {
            if (strlen($userdata['password']) < 6) {
                $this->errors['password'] = "Пароль должен быть больше 6 символов";
            } elseif (strlen($userdata['password']) > 255) {
                $this->errors['password'] = "Пароль не должен быть больше 255 символов";
            }
        }

        if (isset($userdata['password']) && isset($userdata['confirmPassword'])){
            if ($userdata['password'] !== $userdata['confirmPassword']) {
                $this->errors['confirmPassword'] = 'Пароли не совпадают';
            }
        }

        if (isset($userdata['role_id'])) {
            $roles = $user->findAllRoles();    
            $roleIds = []; 
            foreach ($roles as $role) {
                $roleIds[] = $role['id'];
            }   
            if (!filter_var($userdata['role_id'], FILTER_VALIDATE_INT)) {
                $this->errors['role'] = "Роль должна быть числом";
            } elseif (!in_array($userdata['role_id'], $roleIds)) {
                $this->errors['role'] = "Неопределённая роль";
            }
        }

        return $this->errors;
    }

    public function updateValidate($userdata){

        $user = new User();
        
        if (isset($userdata['username'])) {
            if (strlen($userdata['username']) < 3) {
                $this->errors['username'] = "Имя пользователя должно быть больше 3 символов";
            } elseif (strlen($userdata['username']) > 255) {
                $this->errors['username'] = "Имя пользователя не должно быть больше 255 символов";
            } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $userdata['username'])) {
                $this->errors['username'] = "Имя пользователя может содержать только буквы, цифры, подчёркивание и дефис";
            } elseif ($user->existsByUserName($userdata['username'])) {
                $existingUser = $user->existsByUserName($userdata['username']);
                if ($existingUser && $existingUser != $userdata['id']) {
                    $this->errors['username'] = 'Пользователь с таким логином уже существует';
                }
            }
        } elseif (!isset($userdata['username']) || empty($userdata['username'])) {
            $this->errors['username'] = "Поле 'Имя пользователя' обязательно для заполнения";
        }

        if (isset($userdata['email'])) {
            if (strlen($userdata['email']) > 255) {
                $this->errors['email'] = "Электронная почта не должна быть больше 255 символов";
            } elseif (!filter_var($userdata['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = "Неверный формат электронной почты";
            } elseif ($user->existsByEmail($userdata['email'])) {
                $existingUser = $user->existsByEmail($userdata['email']);
                if ($existingUser && $existingUser != $userdata['id']) {
                    $this->errors['email'] = 'Пользователь с таким логином уже существует';
                }
            }
        } elseif (!isset($userdata['email']) || empty($userdata['username'])) {
            $this->errors['email'] = "Поле 'Электронная почта' обязательно для заполнения";
        }

        if (isset($userdata['password'])) {
            if (strlen($userdata['password']) < 6) {
                $this->errors['password'] = "Пароль должен быть больше 6 символов";
            } elseif (strlen($userdata['password']) > 255) {
                $this->errors['password'] = "Пароль не должен быть больше 255 символов";
            }
        }

        if (isset($userdata['password']) && isset($userdata['confirmPassword'])){
            if ($userdata['password'] !== $userdata['confirmPassword']) {
                $this->errors['confirmPassword'] = 'Пароли не совпадают';
            }
        }

        if (isset($userdata['role_id'])) {
            $roles = $user->findAllRoles();    
            $roleIds = []; 
            foreach ($roles as $role) {
                $roleIds[] = $role['id'];
            }   
            if (!filter_var($userdata['role_id'], FILTER_VALIDATE_INT)) {
                $this->errors['role'] = "Роль должна быть числом";
            } elseif (!in_array($userdata['role_id'], $roleIds)) {
                $this->errors['role'] = "Неопределённая роль";
            }
        }

        return $this->errors;
        
    }
}