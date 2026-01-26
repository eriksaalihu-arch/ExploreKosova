<?php
require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/models/User.php";
require_once __DIR__ . "/app/controllers/AuthController.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/register.php");
    exit;
}

$result = AuthController::register($_POST);

if (!$result['ok']) {
    $msg = urlencode(implode(" | ", $result['errors']));
    header("Location: " . BASE_URL . "/register.php?error=" . $msg);
    exit;
}

header("Location: " . BASE_URL . "/login.php?success=1");
exit;