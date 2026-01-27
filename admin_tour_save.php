<?php
require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

if (session_status() === PHP_SESSION_NONE) session_start();
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: dashboard.php?view=tours");
  exit;
}

if (
  empty($_POST['csrf']) ||
  empty($_SESSION['csrf']) ||
  !hash_equals($_SESSION['csrf'], $_POST['csrf'])
) {
  header("Location: admin_tour_form.php?error=" . urlencode("CSRF token gabim. Rifresko faqen dhe provo prapë."));
  exit;
}

$pdo = Database::connection();

function backToForm(int $id, string $msg): void {
  $base = "admin_tour_form.php";
  $q = $id > 0 ? "?id={$id}&" : "?";
  header("Location: {$base}{$q}error=" . urlencode($msg));
  exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$title = trim($_POST['title'] ?? '');
$short = trim($_POST['short_description'] ?? '');
$content = trim($_POST['content'] ?? '');

if (mb_strlen($title) < 3) backToForm($id, "Titulli duhet të ketë të paktën 3 karaktere.");
if (mb_strlen($short) < 10) backToForm($id, "Përshkrimi i shkurtër duhet të ketë të paktën 10 karaktere.");
if (mb_strlen($content) < 20) backToForm($id, "Përmbajtja duhet të ketë të paktën 20 karaktere.");

$existing = null;
if ($id > 0) {
  $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
  $stmt->execute([$id]);
  $existing = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$existing) {
    header("Location: dashboard.php?view=tours");
    exit;
  }
}

$imagePath = $existing['image_path'] ?? null;
$pdfPath   = $existing['pdf_path'] ?? null;

$imgDir = __DIR__ . "/uploads/images/";
$pdfDir = __DIR__ . "/uploads/pdfs/";

if (!is_dir($imgDir)) mkdir($imgDir, 0755, true);
if (!is_dir($pdfDir)) mkdir($pdfDir, 0755, true);

/* ===== IMAGE UPLOAD ===== */
if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
  $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
  $allowed = ['jpg', 'jpeg', 'png', 'webp'];

  if (!in_array($ext, $allowed, true)) {
    backToForm($id, "Foto lejohet vetëm: jpg, jpeg, png, webp.");
  }

  $name = "tour_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
  $target = $imgDir . $name;

  if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    backToForm($id, "Nuk u arrit të ngarkohet fotoja.");
  }

  if (!empty($existing['image_path'])) {
    $old = __DIR__ . "/" . ltrim($existing['image_path'], "/");
    if (is_file($old)) @unlink($old);
  }

  $imagePath = "uploads/images/" . $name;
}

/* ===== PDF UPLOAD ===== */
if (!empty($_FILES['pdf']['name']) && is_uploaded_file($_FILES['pdf']['tmp_name'])) {
  $ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));
  if ($ext !== 'pdf') {
    backToForm($id, "Dokumenti lejohet vetëm PDF.");
  }

  $name = "tour_" . time() . "_" . bin2hex(random_bytes(4)) . ".pdf";
  $target = $pdfDir . $name;

  if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $target)) {
    backToForm($id, "Nuk u arrit të ngarkohet PDF.");
  }

  if (!empty($existing['pdf_path'])) {
    $old = __DIR__ . "/" . ltrim($existing['pdf_path'], "/");
    if (is_file($old)) @unlink($old);
  }

  $pdfPath = "uploads/pdfs/" . $name;
}

$userName = $_SESSION['user']['name'] ?? 'admin';

try {
  if ($id > 0) {
    $stmt = $pdo->prepare("
      UPDATE tours
      SET title = ?, short_description = ?, content = ?, image_path = ?, pdf_path = ?, updated_by = ?
      WHERE id = ?
    ");
    $stmt->execute([$title, $short, $content, $imagePath, $pdfPath, $userName, $id]);
  } else {
    $stmt = $pdo->prepare("
      INSERT INTO tours (title, short_description, content, image_path, pdf_path, created_by, updated_by)
      VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$title, $short, $content, $imagePath, $pdfPath, $userName, $userName]);
  }
} catch (Throwable $e) {
  backToForm($id, "DB gabim: " . $e->getMessage());
}

header("Location: dashboard.php?view=tours");
exit;