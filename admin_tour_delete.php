<?php
require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$csrf = $_GET['csrf'] ?? '';

if ($id <= 0) {
  header("Location: dashboard.php?view=tours");
  exit;
}

if (empty($csrf) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
  header("Location: dashboard.php?view=tours&error=" . urlencode("CSRF token gabim."));
  exit;
}

$pdo = Database::connection();

$stmt = $pdo->prepare("SELECT image_path, pdf_path FROM tours WHERE id = ?");
$stmt->execute([$id]);
$tour = $stmt->fetch();

if ($tour) {
  if (!empty($tour['image_path'])) {
    $img = __DIR__ . "/" . ltrim($tour['image_path'], '/');
    if (is_file($img)) @unlink($img);
  }
  if (!empty($tour['pdf_path'])) {
    $pdf = __DIR__ . "/" . ltrim($tour['pdf_path'], '/');
    if (is_file($pdf)) @unlink($pdf);
  }

  $del = $pdo->prepare("DELETE FROM tours WHERE id = ?");
  $del->execute([$id]);
}

header("Location: dashboard.php?view=tours");
exit;