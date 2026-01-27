<?php
declare(strict_types=1);

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . BASE_URL . "/contact.php");
  exit;
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

$errors = [];
if ($name === '' || mb_strlen($name) < 2) $errors[] = "Emri jo valid.";
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email jo valid.";
if ($message === '' || mb_strlen($message) < 10) $errors[] = "Mesazhi shumë i shkurtër.";

if ($errors) {
  header("Location: " . BASE_URL . "/contact.php?error=" . urlencode(implode(" | ", $errors)));
  exit;
}

$pdo = Database::connection();
$stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (:n,:e,:m)");
$stmt->execute(['n'=>$name,'e'=>$email,'m'=>$message]);

header("Location: " . BASE_URL . "/contact.php?success=1");
exit;