<?php
$pageTitle = "Shërbimet – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/models/Tour.php";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";

$tours = Tour::all();

function asset(string $path): string {
  $path = trim($path);
  if ($path === '') return '';

  $path = str_replace('\\', '/', $path);

  $path = preg_replace('#^https?://[^/]+#', '', $path);

  $path = ltrim($path, '/');

  $base = trim(BASE_URL, '/'); 
  if ($base !== '' && strpos($path, $base . '/') === 0) {
    $path = substr($path, strlen($base) + 1);
  }

  return rtrim(BASE_URL, '/') . '/' . $path;
}
?>

<main class="page">
  <section class="page-header">
    <h1>Shërbimet tona</h1>
    <p>Zgjedhja perfekte për një aventurë të paharrueshme në Kosovë.</p>
  </section>

  <section class="cards">
    <?php if (empty($tours)): ?>
      <p class="error-msg">Nuk ka ende ture të regjistruara.</p>
    <?php endif; ?>

    <?php foreach ($tours as $t): ?>
      <article class="card">
        <?php if (!empty($t['image_path'])): ?>
          <img src="<?= e(asset($t['image_path'])) ?>" alt="<?= e($t['title']) ?>">
        <?php endif; ?>

        <h3><?= e($t['title']) ?></h3>
        <p><?= e($t['short_description']) ?></p>

        <a href="service-details.php?id=<?= (int)$t['id'] ?>" class="btn-secondary">Shiko detajet</a>
      </article>
    <?php endforeach; ?>
  </section>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>