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

if (
  empty($_POST['csrf']) ||
  empty($_SESSION['csrf']) ||
  !hash_equals($_SESSION['csrf'], $_POST['csrf'])
) {
  header("Location: admin_tour_form.php?error=" . urlencode("CSRF token gabim."));
  exit;
}

$pdo = Database::connection();

function clean(string $v): string {
  return trim($v);
}

$id      = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title   = clean($_POST['title'] ?? '');
$short   = clean($_POST['short_description'] ?? '');
$content = clean($_POST['content'] ?? '');

if (mb_strlen($title) < 3 || mb_strlen($short) < 10 || mb_strlen($content) < 20) {
  header("Location: admin_tour_form.php?error=" . urlencode("Të dhëna të pavlefshme."));
  exit;
}

$userName = $_SESSION['user']['name'] ?? 'admin';

/* ================== MERR TURIN NËSE ËSHTË EDIT ================== */
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
$pdfPath   = $existing['pdf_path'] ?? null;

/* ================== SIGURO FOLDERAT ================== */
function ensure_dir(string $dir) {
  if (!is_dir($dir)) mkdir($dir, 0755, true);
  if (!is_writable($dir)) {
    header("Location: admin_tour_form.php?error=" . urlencode("Folderi $dir nuk ka permission për shkrim."));
    exit;
  }
}

/* ================== UPLOAD FOTO ================== */
if (!empty($_FILES['image']['name'])) {
  $dir = __DIR__ . "/uploads/images/";
  ensure_dir($dir);

  $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
  if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
    header("Location: admin_tour_form.php?error=" . urlencode("Format i pavlefshëm i fotos."));
    exit;
  }

  $name = "tour_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
  if (!move_uploaded_file($_FILES['image']['tmp_name'], $dir . $name)) {
    header("Location: admin_tour_form.php?error=" . urlencode("Fotoja nuk u ngarkua."));
    exit;
  }

  if ($imagePath && is_file(__DIR__ . "/" . $imagePath)) {
    @unlink(__DIR__ . "/" . $imagePath);
  }

  $imagePath = "uploads/images/" . $name;
}

/* ================== UPLOAD PDF ================== */
if (!empty($_FILES['pdf']['name'])) {
  $dir = __DIR__ . "/uploads/pdfs/";
  ensure_dir($dir);

  $ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));
  if ($ext !== 'pdf') {
    header("Location: admin_tour_form.php?error=" . urlencode("Lejohet vetëm PDF."));
    exit;
  }

  $name = "tour_" . time() . "_" . bin2hex(random_bytes(4)) . ".pdf";
  if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $dir . $name)) {
    header("Location: admin_tour_form.php?error=" . urlencode("PDF nuk u ngarkua."));
    exit;
  }

  if ($pdfPath && is_file(__DIR__ . "/" . $pdfPath)) {
    @unlink(__DIR__ . "/" . $pdfPath);
  }

  $pdfPath = "uploads/pdfs/" . $name;
}

/* ================== INSERT / UPDATE ================== */
if ($id > 0) {
  $stmt = $pdo->prepare("
    UPDATE tours
    SET title=?, short_description=?, content=?, image_path=?, pdf_path=?, updated_by_name=?
    WHERE id=?
  ");
  $stmt->execute([$title, $short, $content, $imagePath, $pdfPath, $userName, $id]);
} else {
  $stmt = $pdo->prepare("
    INSERT INTO tours
    (title, short_description, content, image_path, pdf_path, created_by_name, updated_by_name)
    VALUES (?, ?, ?, ?, ?, ?, ?)
  ");
  $stmt->execute([$title, $short, $content, $imagePath, $pdfPath, $userName, $userName]);
}

header("Location: dashboard.php?view=tours");
exit;