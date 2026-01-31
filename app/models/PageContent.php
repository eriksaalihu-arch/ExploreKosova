<?php
declare(strict_types=1);

require_once __DIR__ . "/../config/Database.php";

class PageContent
{
    public static function getBySlug(string $slug): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT content_json FROM page_contents WHERE page_slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $data = json_decode((string)$row['content_json'], true);
        return is_array($data) ? $data : null;
    }

    public static function saveBySlug(string $slug, array $data, string $updatedBy): bool
    {
        $pdo = Database::connection();
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $stmt = $pdo->prepare("
            INSERT INTO page_contents (page_slug, content_json, updated_by)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE
                content_json = VALUES(content_json),
                updated_by = VALUES(updated_by)
        ");

        return $stmt->execute([$slug, $json, $updatedBy]);
    }
}