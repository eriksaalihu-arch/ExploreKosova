<?php
$pageTitle = "Detajet – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/models/Tour.php";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

$id = (int)($_GET['id'] ?? 0);
$tour = $id > 0 ? Tour::find($id) : null;
?>

<main class="page">
  <section class="page-header">
    <h1>Detajet e Shërbimit</h1>
    <p>Informata të detajuara për turin.</p>
  </section>

  <?php if (!$tour): ?>
    <p class="error-msg">Turi nuk u gjet.</p>
  <?php else: ?>
    <section class="two-cols">
      <article>
        <h2><?= e($tour['title']) ?></h2>
        <p><?= nl2br(e($tour['content'])) ?></p>

        <p style="margin-top:10px;">
          <strong>Krijuar nga:</strong> <?= e($tour['created_by_name']) ?>
          <?php if (!empty($tour['updated_by_name'])): ?>
            • <strong>Përditësuar nga:</strong> <?= e($tour['updated_by_name']) ?>
          <?php endif; ?>
        </p>

        <?php if (!empty($tour['pdf_path'])): ?>
          <p style="margin-top:10px;">
            <a class="btn-secondary" href="<?= e($tour['pdf_path']) ?>" target="_blank">Shkarko PDF</a>
          </p>
        <?php endif; ?>
      </article>

      <article>
        <?php if (!empty($tour['image_path'])): ?>
          <img style="width:100%; border-radius:12px;" src="<?= e($tour['image_path']) ?>" alt="<?= e($tour['title']) ?>">
        <?php else: ?>
          <p class="error-msg">Nuk ka foto të ngarkuar.</p>
        <?php endif; ?>
      </article>
    </section>
  <?php endif; ?>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>