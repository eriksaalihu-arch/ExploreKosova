<?php
$pageTitle = "Detajet e turit – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/models/Tour.php";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";

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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$tour = $id > 0 ? Tour::find($id) : null;
?>

<main class="page">
  <section class="page-header">
    <h1>Detajet e turit</h1>
    <p>Informacione të detajuara për turin.</p>
  </section>

  <?php if (!$tour): ?>
    <p class="error-msg">Turi nuk u gjet.</p>
  <?php else: ?>

    <div class="dashboard-card" style="max-width: 920px; margin: 0 auto;">

      <?php if (!empty($tour['image_path'])): ?>
        <img
          src="<?= e(asset($tour['image_path'])) ?>"
          alt="<?= e($tour['title']) ?>"
          style="width:100%;max-height:380px;object-fit:cover;border-radius:12px;"
        >
      <?php endif; ?>

      <h2 style="margin-top:18px;"><?= e($tour['title']) ?></h2>
      <p style="color:#555;"><?= e($tour['short_description']) ?></p>

      <hr style="margin:18px 0;border:none;border-top:1px solid #eee;">

      <p><?= nl2br(e($tour['content'])) ?></p>

      <?php if (!empty($tour['pdf_path'])): ?>
        <div style="margin-top:18px;display:flex;gap:10px;flex-wrap:wrap;">
          <a class="btn-primary" href="<?= e(asset($tour['pdf_path'])) ?>" target="_blank">Hap PDF</a>
          <a class="btn-secondary" href="<?= e(asset($tour['pdf_path'])) ?>" download>Shkarko PDF</a>
        </div>
      <?php endif; ?>

    </div>

  <?php endif; ?>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>