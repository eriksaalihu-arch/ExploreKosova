<?php
declare(strict_types=1);

class AuthController
{

    public static function register(array $post): array
    {
        $name     = trim((string)($post['name'] ?? ''));
        $email    = strtolower(trim((string)($post['email'] ?? '')));
        $password = (string)($post['password'] ?? '');
        $confirm  = (string)($post['confirm'] ?? '');

        $errors = [];

        if ($name === '' || mb_strlen($name) < 2) {
            $errors[] = "Emri duhet të ketë të paktën 2 karaktere.";
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email i pavlefshëm.";
        }

        if (mb_strlen($password) < 6) {
            $errors[] = "Fjalëkalimi duhet të ketë minimum 6 karaktere.";
        }

        if ($password !== $confirm) {
            $errors[] = "Fjalëkalimet nuk përputhen.";
        }

        if ($email !== '' && User::findByEmail($email)) {
            $errors[] = "Ky email ekziston tashmë.";
        }

        if (!empty($errors)) {
            return [
                'ok'     => false,
                'errors' => $errors
            ];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $userId = User::create($name, $email, $passwordHash, 'user');

        return [
            'ok'      => true,
            'user_id' => $userId
        ];
    }

    public static function login(array $post): array
    {
        $email    = strtolower(trim((string)($post['email'] ?? '')));
        $password = (string)($post['password'] ?? '');

        $errors = [];

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email i pavlefshëm.";
        }

        if ($password === '') {
            $errors[] = "Fjalëkalimi është i detyrueshëm.";
        }

        if (!empty($errors)) {
            return [
                'ok'     => false,
                'errors' => $errors
            ];
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return [
                'ok'     => false,
                'errors' => ["Email ose fjalëkalim gabim."]
            ];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user'] = [
            'id'    => (int)$user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        return [
            'ok'   => true,
            'role' => $user['role']
        ];
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
    }
}