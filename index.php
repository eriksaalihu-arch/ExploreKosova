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
      <div class="slider" id="ek-slider">
        <div class="slider-track">

          <div class="slide is-active">
            <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1600&auto=format&fit=crop&q=80" alt="Kosova nature">
            <div class="slide-caption">
              <h3>Zbulo natyrën</h3>
              <p>Eksploro destinacione unike me guida profesionale.</p>
            </div>
          </div>

          <div class="slide">
            <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1600&auto=format&fit=crop&q=80" alt="Mountains">
            <div class="slide-caption">
              <h3>Destinacione historike</h3>
              <p>Prishtinë, Prizren dhe më shumë.</p>
            </div>
          </div>

          <div class="slide">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1600&auto=format&fit=crop&q=80" alt="Travel team">
            <div class="slide-caption">
              <h3>Rezervo lehtë</h3>
              <p>Kontakt i shpejtë dhe menaxhim profesional.</p>
            </div>
          </div>

        </div>

        <button class="slider-btn prev" type="button" aria-label="Mbrapa">‹</button>
        <button class="slider-btn next" type="button" aria-label="Para">›</button>
        <div class="slider-dots" aria-label="Slider dots"></div>
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