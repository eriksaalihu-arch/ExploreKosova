<?php
declare(strict_types=1);

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";
require_once __DIR__ . "/app/models/Tour.php";

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/dashboard.php");
    exit;
}

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
    header("Location: " . BASE_URL . "/dashboard.php?err=CSRF");
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$title = trim((string)($_POST['title'] ?? ''));
$short = trim((string)($_POST['short_description'] ?? ''));
$content = trim((string)($_POST['content'] ?? ''));

if ($title === '' || $short === '' || $content === '') {
    header("Location: " . BASE_URL . "/admin_tour_form.php?id={$id}&error=Ploteso+te+gjitha+fushat");
    exit;
}

$uploadImgDir = __DIR__ . "/uploads/images/";
$uploadPdfDir = __DIR__ . "/uploads/pdfs/";

if (!is_dir($uploadImgDir)) mkdir($uploadImgDir, 0777, true);
if (!is_dir($uploadPdfDir)) mkdir($uploadPdfDir, 0777, true);

$imagePath = null;
$pdfPath = null;

// nëse është edit, ruaj path-at ekzistues nëse s’ngarkohet file i ri
$existing = $id > 0 ? Tour::find($id) : null;
if ($existing) {
    $imagePath = $existing['image_path'] ?: null;
    $pdfPath = $existing['pdf_path'] ?: null;
}

// upload image
if (!empty($_FILES['image']['name'])) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed, true)) {
        header("Location: " . BASE_URL . "/admin_tour_form.php?id={$id}&error=Foto+jo+valide");
        exit;
    }
    $filename = "tour_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadImgDir . $filename);
    $imagePath = BASE_URL . "/uploads/images/" . $filename;
}

// upload pdf
if (!empty($_FILES['pdf']['name'])) {
    $ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        header("Location: " . BASE_URL . "/admin_tour_form.php?id={$id}&error=PDF+jo+valid");
        exit;
    }
    $filename = "tour_" . time() . "_" . bin2hex(random_bytes(4)) . ".pdf";
    move_uploaded_file($_FILES['pdf']['tmp_name'], $uploadPdfDir . $filename);
    $pdfPath = BASE_URL . "/uploads/pdfs/" . $filename;
}

$adminName = (string)($_SESSION['user']['name'] ?? 'admin');

if ($id > 0) {
    Tour::update($id, [
        'title' => $title,
        'short_description' => $short,
        'content' => $content,
        'image_path' => $imagePath,
        'pdf_path' => $pdfPath,
        'updated_by_name' => $adminName
    ]);
} else {
    Tour::create([
        'title' => $title,
        'short_description' => $short,
        'content' => $content,
        'image_path' => $imagePath,
        'pdf_path' => $pdfPath,
        'created_by_name' => $adminName
    ]);
}

header("Location: " . BASE_URL . "/dashboard.php?ok=Tur+u+ruajt");
exit;