<?php
require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: dashboard.php?view=tours");
  exit;
}

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  header("Location: admin_tour_form.php?error=" . urlencode("CSRF token gabim."));
  exit;
}

$pdo = Database::connection();

function clean(string $v): string { return trim($v); }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$title = clean($_POST['title'] ?? '');
$short = clean($_POST['short_description'] ?? '');
$content = clean($_POST['content'] ?? '');

if (mb_strlen($title) < 3) {
  header("Location: admin_tour_form.php" . ($id ? "?id=$id&" : "?") . "error=" . urlencode("Titulli duhet të ketë të paktën 3 karaktere."));
  exit;
}
if (mb_strlen($short) < 10) {
  header("Location: admin_tour_form.php" . ($id ? "?id=$id&" : "?") . "error=" . urlencode("Përshkrimi i shkurtër duhet të ketë të paktën 10 karaktere."));
  exit;
}
if (mb_strlen($content) < 20) {
  header("Location: admin_tour_form.php" . ($id ? "?id=$id&" : "?") . "error=" . urlencode("Përmbajtja duhet të ketë të paktën 20 karaktere."));
  exit;
}

$existing = null;
if ($id > 0) {
  $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
  $stmt->execute([$id]);
  $existing = $stmt->fetch();
  if (!$existing) {
    header("Location: dashboard.php?view=tours");
    exit;
  }
}

$imagePath = $existing['image_path'] ?? null;

if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
  $uploadDir = __DIR__ . "/uploads/images/";
  if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

  $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
  $allowed = ['jpg', 'jpeg', 'png', 'webp'];

  if (!in_array($ext, $allowed, true)) {
    header("Location: admin_tour_form.php" . ($id ? "?id=$id&" : "?") . "error=" . urlencode("Foto lejohet vetëm: jpg, jpeg, png, webp."));
    exit;
  }

  $fileName = "tour_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
  $target = $uploadDir . $fileName;

  if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    header("Location: admin_tour_form.php" . ($id ? "?id=$id&" : "?") . "error=" . urlencode("Nuk u arrit të ngarkohet fotoja."));
    exit;
  }

  if (!empty($existing['image_path'])) {
    $old = __DIR__ . "/" . ltrim($existing['image_path'], '/');
    if (is_file($old)) @unlink($old);
  }

  $imagePath = "uploads/images/" . $fileName;
}

$pdfPath = $existing['pdf_path'] ?? null;

if (!empty($_FILES['pdf']['name']) && is_uploaded_file($_FILES['pdf']['tmp_name'])) {
  $uploadDir = __DIR__ . "/uploads/pdfs/";
  if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

  $ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));
  if ($ext !== 'pdf') {
    header("Location: admin_tour_form.php" . ($id ? "?id=$id&" : "?") . "error=" . urlencode("Dokumenti lejohet vetëm PDF."));
    exit;
  }

  $fileName = "tour_" . time() . "_" . bin2hex(random_bytes(4)) . ".pdf";
  $target = $uploadDir . $fileName;

  if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $target)) {
    header("Location: admin_tour_form.php" . ($id ? "?id=$id&" : "?") . "error=" . urlencode("Nuk u arrit të ngarkohet PDF."));
    exit;
  }

  if (!empty($existing['pdf_path'])) {
    $old = __DIR__ . "/" . ltrim($existing['pdf_path'], '/');
    if (is_file($old)) @unlink($old);
  }

  $pdfPath = "uploads/pdfs/" . $fileName;
}

$userName = $_SESSION['user']['name'] ?? 'admin';

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

header("Location: dashboard.php?view=tours");
exit;