<?php
$pageTitle = "Shërbimet – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

function asset_url(?string $path): string {
  if (!$path) return "";
  $path = trim($path);

  if (preg_match('#^https?://#i', $path)) return $path;

  if (preg_match('#(uploads/(images|pdfs)/[^"\']+)#i', $path, $m)) {
    $path = $m[1];
  }

  $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
  return $base . '/' . ltrim($path, '/');
}

$pdo = Database::connection();
$tours = $pdo->query("SELECT id, title, short_description, image_path FROM tours ORDER BY id DESC")->fetchAll();
?>

<main class="page">
  <section class="page-header">
    <h1>Shërbimet tona</h1>
    <p>Zgjedhja perfekte për një aventurë të paharrueshme në Kosovë.</p>
  </section>

  <section class="cards">
    <?php if (empty($tours)): ?>
      <p class="error-msg" style="text-align:center;width:100%;">Nuk ka ende ture të regjistruara.</p>
    <?php else: ?>
      <?php foreach ($tours as $t): ?>
        <article class="card">
          <?php $img = asset_url($t['image_path'] ?? ''); ?>
          <?php if (!empty($img)): ?>
            <img src="<?= e($img) ?>" alt="<?= e($t['title']) ?>">
          <?php endif; ?>

          <h3><?= e($t['title']) ?></h3>
          <p><?= e($t['short_description']) ?></p>

          <a href="service-details.php?id=<?= (int)$t['id'] ?>" class="btn-secondary">Shiko detajet</a>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>