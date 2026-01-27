<?php
$pageTitle = "Detajet e turit – ExploreKosova";

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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header("Location: services.php");
  exit;
}

$pdo = Database::connection();
$stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
$stmt->execute([$id]);
$tour = $stmt->fetch();

if (!$tour) {
  header("Location: services.php");
  exit;
}

$imgUrl = asset_url($tour['image_path'] ?? '');
$pdfUrl = asset_url($tour['pdf_path'] ?? '');
?>

<main class="page">
  <section class="page-header">
    <h1><?= e($tour['title']) ?></h1>
    <p><?= e($tour['short_description']) ?></p>
  </section>

  <section class="two-cols" style="justify-content:center;">
    <article style="max-width:900px;">
      <?php if (!empty($imgUrl)): ?>
        <img src="<?= e($imgUrl) ?>" alt="<?= e($tour['title']) ?>" style="width:100%;max-height:380px;object-fit:cover;border-radius:12px;margin-bottom:18px;">
      <?php endif; ?>

      <div style="text-align:left; line-height:1.7; color:#333;">
        <?= nl2br(e($tour['content'] ?? '')) ?>
      </div>

      <?php if (!empty($pdfUrl)): ?>
        <div style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap;">
          <a class="btn-primary" href="<?= e($pdfUrl) ?>" target="_blank" rel="noopener">Hap PDF</a>
          <a class="btn-secondary" href="<?= e($pdfUrl) ?>" download>Shkarko PDF</a>
        </div>
      <?php endif; ?>
    </article>
  </section>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>