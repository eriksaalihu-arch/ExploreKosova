<?php
declare(strict_types=1);

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

if (session_status() === PHP_SESSION_NONE) session_start();

preventAuthCache();

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/dashboard.php?view=messages&err=Invalid+request");
    exit;
}

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
    header("Location: " . BASE_URL . "/dashboard.php?view=messages&err=CSRF+error");
    exit;
}

$messageId = (int)($_POST['message_id'] ?? 0);
if ($messageId <= 0) {
    header("Location: " . BASE_URL . "/dashboard.php?view=messages&err=ID+invalid");
    exit;
}

$pdo = Database::connection();
$stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
$stmt->execute(['id' => $messageId]);

header("Location: " . BASE_URL . "/dashboard.php?view=messages&ok=Mesazhi+u+fshi");
exit;