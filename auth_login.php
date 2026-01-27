<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/models/User.php";
require_once __DIR__ . "/app/controllers/AuthController.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

$result = AuthController::login($_POST);

if (!$result['ok']) {
    $msg = urlencode(implode(" | ", $result['errors']));
    header("Location: " . BASE_URL . "/login.php?error=" . $msg);
    exit;
}

if (($result['role'] ?? '') === 'admin') {
    header("Location: " . BASE_URL . "/dashboard.php");
    exit;
}

header("Location: " . BASE_URL . "/index.php");
exit;