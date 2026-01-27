<?php
declare(strict_types=1);

class User
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            "SELECT id, name, email, password_hash, role
             FROM users
             WHERE email = :email
             LIMIT 1"
        );

        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function create(
        string $name,
        string $email,
        string $passwordHash,
        string $role = 'user'
    ): int {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            "INSERT INTO users (name, email, password_hash, role)
             VALUES (:name, :email, :password_hash, :role)"
        );

        $stmt->execute([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => $passwordHash,
            'role'          => $role
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            "SELECT id, name, email, role
             FROM users
             WHERE id = :id
             LIMIT 1"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }
}