<?php
$pageTitle = "Ballina – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/models/PageContent.php";

$home = PageContent::getBySlug('home') ?? [];

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page home-page">

    <section class="hero hero-img">
        <div class="hero-content">
            <h1><?= e($home['hero_title'] ?? 'Zbulo Kosovën') ?></h1>
            <p><?= e($home['hero_subtitle'] ?? 'Eksploro natyrën, qytetet dhe traditën e vendit me ture profesionale.') ?></p>

            <?php
              $btnText = $home['hero_button_text'] ?? 'Shiko turet';
              $btnLink = $home['hero_button_link'] ?? 'services.php';
            ?>
            <a href="<?= e($btnLink) ?>" class="btn-primary"><?= e($btnText) ?></a>
        </div>
    </section>

    <section class="section">
        <h2><?= e($home['why_title'] ?? 'Pse ExploreKosova?') ?></h2>

        <div class="cards">
            <?php $cards = $home['cards'] ?? []; ?>
            <?php if (empty($cards)): ?>
                <p class="error-msg">Nuk ka përmbajtje për kartat (cards) ende.</p>
            <?php else: ?>
                <?php foreach ($cards as $c): ?>
                    <article class="card">
                        <?php if (!empty($c['image'])): ?>
                            <img src="<?= e((string)$c['image']) ?>" alt="<?= e((string)($c['title'] ?? '')) ?>">
                        <?php endif; ?>
                        <h3><?= e((string)($c['title'] ?? '')) ?></h3>
                        <p><?= e((string)($c['text'] ?? '')) ?></p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>