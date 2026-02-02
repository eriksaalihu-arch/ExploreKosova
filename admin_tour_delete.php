<?php
require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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

function uploads_relative(?string $path): ?string {
  if (!$path) return null;
  $path = trim($path);

  if (preg_match('#^https?://#i', $path)) {
    $u = parse_url($path);
    $path = $u['path'] ?? $path;
  }

  if (preg_match('#(uploads/(images|pdfs)/[^"\']+)#i', $path, $m)) {
    return $m[1];
  }

  return null;
}

$stmt = $pdo->prepare("SELECT image_path, pdf_path FROM tours WHERE id = ?");
$stmt->execute([$id]);
$tour = $stmt->fetch();

if ($tour) {
  $imgRel = uploads_relative($tour['image_path'] ?? null);
  $pdfRel = uploads_relative($tour['pdf_path'] ?? null);

  if ($imgRel) {
    $imgFile = __DIR__ . "/" . $imgRel;
    if (is_file($imgFile)) @unlink($imgFile);
  }

  if ($pdfRel) {
    $pdfFile = __DIR__ . "/" . $pdfRel;
    if (is_file($pdfFile)) @unlink($pdfFile);
  }

  $del = $pdo->prepare("DELETE FROM tours WHERE id = ?");
  $del->execute([$id]);
}

header("Location: dashboard.php?view=tours");
exit;