<?php
declare(strict_types=1);

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/helpers/auth.php";
require_once __DIR__ . "/app/models/Tour.php";
require_once __DIR__ . "/app/config/Database.php";

requireAdmin();
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: " . BASE_URL . "/dashboard.php"); exit; }

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
  header("Location: " . BASE_URL . "/dashboard.php?err=CSRF"); exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) Tour::delete($id);

header("Location: " . BASE_URL . "/dashboard.php?ok=Tur+u+fshi");
exit;