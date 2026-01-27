<?php
require_once __DIR__ . "/app/controllers/AuthController.php";
require_once __DIR__ . "/app/config/config.php";

AuthController::logout();
header("Location: " . BASE_URL . "/index.php");
exit;