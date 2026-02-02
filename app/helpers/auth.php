<?php
declare(strict_types=1);

function preventAuthCache(): void
{
    if (!headers_sent()) {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}

function requireAuth(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    preventAuthCache();

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