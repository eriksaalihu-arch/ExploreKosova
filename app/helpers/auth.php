<?php
declare(strict_types=1);

function requireAuth(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['user'])) {
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }
}

function requireAdmin(): void
{
    requireAuth();
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        header("Location: " . BASE_URL . "/index.php");
        exit;
    }
}