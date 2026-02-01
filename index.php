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

<?php
  $slides = [];

  $dbSlides = $home['slider_images'] ?? [];
  if (is_array($dbSlides)) {
    foreach ($dbSlides as $img) {
      $img = trim((string)$img);
      if ($img !== '') $slides[] = $img;
    }
  }

  if (count($slides) < 2) {
    $cardsFallback = $home['cards'] ?? [];
    if (is_array($cardsFallback)) {
      foreach ($cardsFallback as $c) {
        $img = trim((string)($c['image'] ?? ''));
        if ($img !== '') $slides[] = $img;
      }
    }
  }

  $slides = array_values(array_unique(array_filter($slides)));
  $slides = array_slice($slides, 0, 5);

  if (count($slides) < 2) {
    $slides = [
      'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=2000&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=2000&auto=format&fit=crop&q=80',
      'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=2000&auto=format&fit=crop&q=80',
    ];
  }

  $btnText = (string)($home['hero_button_text'] ?? 'Shiko turet');
  $btnLink = (string)($home['hero_button_link'] ?? 'services.php');
?>

  <section class="hero-slider" id="ek-hero-slider" data-autoplay="1" data-interval="5000">
    <div class="hero-slides">
      <?php foreach ($slides as $i => $img): ?>
        <?php
          $bg = "background-image: url('" . e((string)$img) . "');";
        ?>
        <div
          class="hero-slide <?= $i === 0 ? 'is-active' : '' ?>"
          style="<?= $bg ?>"
          role="img"
          aria-label="Slide <?= (int)($i + 1) ?>"
        ></div>
      <?php endforeach; ?>
    </div>

    <div class="hero-overlay"></div>

    <div class="hero-content">
      <h1><?= e((string)($home['hero_title'] ?? 'Zbulo Kosovën')) ?></h1>
      <p><?= e((string)($home['hero_subtitle'] ?? 'Eksploro natyrën, qytetet dhe traditën e vendit me ture profesionale.')) ?></p>
      <a href="<?= e($btnLink) ?>" class="btn-primary"><?= e($btnText) ?></a>
    </div>

    <button class="hero-btn prev" type="button" aria-label="Mbrapa">‹</button>
    <button class="hero-btn next" type="button" aria-label="Para">›</button>
    <div class="hero-dots" aria-label="Hero slider dots"></div>
  </section>

  <section class="section">
    <h2><?= e((string)($home['why_title'] ?? 'Pse ExploreKosova?')) ?></h2>

    <div class="cards">
      <?php $cards = $home['cards'] ?? []; ?>
      <?php if (empty($cards) || !is_array($cards)): ?>
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