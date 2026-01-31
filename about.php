<?php
$pageTitle = "Rreth Nesh – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/models/PageContent.php";

$about = PageContent::getBySlug('about') ?? [];

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page">
    <section class="page-header">
        <h1><?= e($about['page_title'] ?? 'Rreth nesh') ?></h1>
        <p><?= e($about['page_subtitle'] ?? 'Promovojmë Kosovën përmes turizmit të qëndrueshëm dhe përvojave autentike.') ?></p>
    </section>

    <?php
      $sections = $about['sections'] ?? [];
      $chunks = array_chunk($sections, 2);
    ?>

    <?php if (empty($sections)): ?>
        <p class="error-msg" style="text-align:center;">Nuk ka përmbajtje për About ende.</p>
    <?php else: ?>
        <?php foreach ($chunks as $pair): ?>
            <section class="two-cols">
                <?php foreach ($pair as $sec): ?>
                    <article>
                        <h2><?= e((string)($sec['title'] ?? '')) ?></h2>
                        <p><?= e((string)($sec['text'] ?? '')) ?></p>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>