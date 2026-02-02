<?php
declare(strict_types=1);

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: dashboard.php?view=users");
  exit;
}

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
  header("Location: dashboard.php?view=users&err=CSRF");
  exit;
}

$userId = (int)($_POST['user_id'] ?? 0);
if ($userId <= 0) {
  header("Location: dashboard.php?view=users&err=ID");
  exit;
}

// Mos lejo me fshi vetveten
$selfId = (int)($_SESSION['user']['id'] ?? 0);
if ($selfId === $userId) {
  header("Location: dashboard.php?view=users&err=self_delete");
  exit;
}

$pdo = Database::connection();

// Mos lejo me fshi admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$role = (string)($stmt->fetchColumn() ?: '');

if ($role === 'admin') {
  header("Location: dashboard.php?view=users&err=admin_delete");
  exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$userId]);

header("Location: dashboard.php?view=users&ok=user_deleted");
exit;