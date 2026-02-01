<?php
declare(strict_types=1);

require_once __DIR__ . "/../config/Database.php";

final class PageContent
{

    public static function getBySlug(string $slug): ?array
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            "SELECT content FROM page_contents WHERE slug = :slug LIMIT 1"
        );
        $stmt->execute(['slug' => $slug]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $data = json_decode((string)$row['content'], true);
        return is_array($data) ? $data : null;
    }

    public static function saveBySlug(string $slug, array $data, string $updatedBy = 'admin'): void
    {
        $pdo = Database::connection();

        $data['_meta'] = [
            'updated_by' => $updatedBy,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $json = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            throw new RuntimeException('JSON encode failed');
        }

        $stmt = $pdo->prepare("
            INSERT INTO page_contents (slug, content)
            VALUES (:slug, :content)
            ON DUPLICATE KEY UPDATE
                content = VALUES(content),
                updated_at = CURRENT_TIMESTAMP
        ");

        $stmt->execute([
            'slug'    => $slug,
            'content' => $json
        ]);
    }
}