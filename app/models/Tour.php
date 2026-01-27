<?php
declare(strict_types=1);

class Tour
{
    public static function all(): array
    {
        $pdo = Database::connection();
        return $pdo->query("SELECT * FROM tours ORDER BY id DESC")->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            INSERT INTO tours (title, short_description, content, image_path, pdf_path, created_by_name)
            VALUES (:title, :short_description, :content, :image_path, :pdf_path, :created_by_name)
        ");
        $stmt->execute($data);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $data['id'] = $id;

        $stmt = $pdo->prepare("
            UPDATE tours
            SET title=:title,
                short_description=:short_description,
                content=:content,
                image_path=:image_path,
                pdf_path=:pdf_path,
                updated_by_name=:updated_by_name
            WHERE id=:id
        ");
        $stmt->execute($data);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("DELETE FROM tours WHERE id=:id");
        $stmt->execute(['id' => $id]);
    }
}