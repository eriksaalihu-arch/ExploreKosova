<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = $pageTitle ?? "ExploreKosova";
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>