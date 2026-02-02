<?php
declare(strict_types=1);

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";
require_once __DIR__ . "/app/models/PageContent.php";

if (session_status() === PHP_SESSION_NONE) session_start();

preventAuthCache();

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_pages.php?page=home&error=" . urlencode("Invalid request"));
    exit;
}

if (
    empty($_POST['csrf']) || empty($_SESSION['csrf']) ||
    !hash_equals((string)$_SESSION['csrf'], (string)$_POST['csrf'])
) {
    header("Location: admin_pages.php?page=home&error=" . urlencode("CSRF token gabim"));
    exit;
}

$page = (string)($_POST['page'] ?? 'home');
if (!in_array($page, ['home','about'], true)) $page = 'home';

$updatedBy = (string)($_SESSION['user']['name'] ?? 'admin');

function clean(string $v): string { return trim($v); }

function isLikelyUrl(string $v): bool {
    if ($v === '') return false;
    return (bool)filter_var($v, FILTER_VALIDATE_URL);
}

if ($page === 'home') {
    $heroTitle = clean((string)($_POST['hero_title'] ?? ''));
    $heroSub   = clean((string)($_POST['hero_subtitle'] ?? ''));
    $btnText   = clean((string)($_POST['hero_button_text'] ?? ''));
    $btnLink   = clean((string)($_POST['hero_button_link'] ?? 'services.php'));
    $whyTitle  = clean((string)($_POST['why_title'] ?? ''));

    $sliderImages = $_POST['slider_image'] ?? [];
    $sliderTitles = $_POST['slider_title'] ?? [];
    $sliderTexts  = $_POST['slider_text'] ?? [];

    $slider = [];
    for ($i=0; $i<3; $i++) {
        $img = clean((string)($sliderImages[$i] ?? ''));
        $ttl = clean((string)($sliderTitles[$i] ?? ''));
        $txt = clean((string)($sliderTexts[$i] ?? ''));

        $slider[] = [
            'image' => $img,
            'title' => $ttl,
            'text'  => $txt,
        ];
    }

    $cardTitles = $_POST['card_title'] ?? [];
    $cardTexts  = $_POST['card_text'] ?? [];
    $cardImages = $_POST['card_image'] ?? [];

    $cards = [];
    for ($i=0; $i<3; $i++) {
        $cards[] = [
            'title' => clean((string)($cardTitles[$i] ?? '')),
            'text'  => clean((string)($cardTexts[$i] ?? '')),
            'image' => clean((string)($cardImages[$i] ?? '')),
        ];
    }

    if (
        mb_strlen($heroTitle) < 3 ||
        mb_strlen($heroSub) < 5 ||
        mb_strlen($btnText) < 2 ||
        mb_strlen($whyTitle) < 3
    ) {
        header("Location: admin_pages.php?page=home&error=" . urlencode("Plotëso të gjitha fushat kryesore të Home."));
        exit;
    }

    for ($i=0; $i<3; $i++) {
        if (!isLikelyUrl((string)$slider[$i]['image'])) {
            header("Location: admin_pages.php?page=home&error=" . urlencode("Vendos URL të vlefshme për foton e Slide " . ($i+1) . "."));
            exit;
        }
    }

    for ($i=0; $i<3; $i++) {
        if (mb_strlen((string)$cards[$i]['title']) < 2 || mb_strlen((string)$cards[$i]['text']) < 2) {
            header("Location: admin_pages.php?page=home&error=" . urlencode("Plotëso titullin dhe tekstin për Card " . ($i+1) . "."));
            exit;
        }
        if (!isLikelyUrl((string)$cards[$i]['image'])) {
            header("Location: admin_pages.php?page=home&error=" . urlencode("Vendos URL të vlefshme për foton e Card " . ($i+1) . "."));
            exit;
        }
    }

    $data = [
        'hero_title' => $heroTitle,
        'hero_subtitle' => $heroSub,
        'hero_button_text' => $btnText,
        'hero_button_link' => $btnLink,
        'why_title' => $whyTitle,
        'slider' => $slider, 
        'cards' => $cards,
    ];

    PageContent::saveBySlug('home', $data, $updatedBy);
    header("Location: admin_pages.php?page=home&success=1");
    exit;
}

/* ========================= ABOUT ========================= */
$pageTitle = clean((string)($_POST['page_title'] ?? ''));
$pageSub   = clean((string)($_POST['page_subtitle'] ?? ''));

$secTitles = $_POST['sec_title'] ?? [];
$secTexts  = $_POST['sec_text'] ?? [];

$sections = [];
for ($i=0; $i<4; $i++) {
    $sections[] = [
        'title' => clean((string)($secTitles[$i] ?? '')),
        'text'  => clean((string)($secTexts[$i] ?? '')),
    ];
}

if (mb_strlen($pageTitle) < 3 || mb_strlen($pageSub) < 5) {
    header("Location: admin_pages.php?page=about&error=" . urlencode("Plotëso titullin dhe përshkrimin e About."));
    exit;
}

for ($i=0; $i<4; $i++) {
    if (mb_strlen((string)$sections[$i]['title']) < 2 || mb_strlen((string)$sections[$i]['text']) < 3) {
        header("Location: admin_pages.php?page=about&error=" . urlencode("Plotëso të gjitha fushat për Seksionin " . ($i+1) . "."));
        exit;
    }
}

PageContent::saveBySlug('about', [
    'page_title' => $pageTitle,
    'page_subtitle' => $pageSub,
    'sections' => $sections,
], $updatedBy);

header("Location: admin_pages.php?page=about&success=1");
exit;