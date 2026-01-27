<?php
require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

if (session_status() === PHP_SESSION_NONE) session_start();
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$csrf = $_GET['csrf'] ?? '';

if ($id <= 0) {
  header("Location: " . BASE_URL . "/dashboard.php?view=tours&error=" . urlencode("ID i pavlefshëm."));
  exit;
}

if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
  header("Location: " . BASE_URL . "/dashboard.php?view=tours&error=" . urlencode("CSRF i pavlefshëm."));
  exit;
}

$pdo = Database::connection();

$stmt = $pdo->prepare("SELECT image_path, pdf_path FROM tours WHERE id = ?");
$stmt->execute([$id]);
$tour = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("DELETE FROM tours WHERE id = ?");
$stmt->execute([$id]);

if ($stmt->rowCount() === 0) {
  header("Location: " . BASE_URL . "/dashboard.php?view=tours&error=" . urlencode("Turi nuk u gjet."));
  exit;
}
if ($tour) {
  foreach (['image_path', 'pdf_path'] as $k) {
    if (!empty($tour[$k])) {
      $path = $tour[$k];
      $path = ltrim($path, '/');
      $full = __DIR__ . "/" . $path;
      if (strpos($path, "uploads/") === 0 && file_exists($full)) {
        @unlink($full);
      }
    }
  }
}

header("Location: " . BASE_URL . "/dashboard.php?view=tours&success=" . urlencode("Turi u fshi me sukses."));
exit;