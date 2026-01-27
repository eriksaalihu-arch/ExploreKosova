<?php
$pageTitle = "Shto / Edito Tur – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

if (session_status() === PHP_SESSION_NONE) session_start();
requireAdmin();

if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

$pdo = Database::connection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$tour = [
  'id' => 0,
  'title' => '',
  'short_description' => '',
  'content' => '',
  'image_path' => '',
  'pdf_path' => ''
];

if ($id > 0) {
  $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
  $stmt->execute([$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) $tour = $row;
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page">

  <section class="page-header">
    <h1><?= $id > 0 ? "Edito Tur" : "Shto Tur" ?></h1>
    <p>Plotëso të dhënat e turit. Foto dhe PDF janë opsionale.</p>

    <?php if (!empty($_GET['error'])): ?>
      <div class="auth-alert error"><?= e($_GET['error']) ?></div>
    <?php endif; ?>

    <?php if (!empty($_GET['success'])): ?>
      <div class="auth-alert success"><?= e($_GET['success']) ?></div>
    <?php endif; ?>
  </section>

  <div class="dashboard-container">
    <div class="dashboard-card" style="max-width:820px;margin:0 auto;">

      <form class="form-card" style="max-width:100%;"
            method="POST"
            action="admin_tour_save.php"
            enctype="multipart/form-data">

        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="id" value="<?= (int)$tour['id'] ?>">

        <div>
          <label for="title">Titulli</label>
          <input id="title" type="text" name="title" value="<?= e($tour['title'] ?? '') ?>" required>
        </div>

        <div>
          <label for="short_description">Përshkrimi i shkurtër</label>
          <input id="short_description" type="text" name="short_description" value="<?= e($tour['short_description'] ?? '') ?>" required>
        </div>

        <div>
          <label for="content">Përmbajtja</label>
          <textarea id="content" name="content" rows="8" required><?= e($tour['content'] ?? '') ?></textarea>
        </div>

        <div>
          <label for="image">Foto</label>
          <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
          <?php if (!empty($tour['image_path'])): ?>
            <p style="margin-top:8px;">
              Foto aktuale:
              <a href="<?= e(ltrim($tour['image_path'], '/')) ?>" target="_blank">Shiko</a>
            </p>
          <?php endif; ?>
        </div>

        <div>
          <label for="pdf">PDF</label>
          <input id="pdf" type="file" name="pdf" accept=".pdf">
          <?php if (!empty($tour['pdf_path'])): ?>
            <p style="margin-top:8px;">
              PDF aktual:
              <a href="<?= e(ltrim($tour['pdf_path'], '/')) ?>" target="_blank">Shiko</a>
            </p>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn-primary">
          <?= $id > 0 ? "Ruaj ndryshimet" : "Ruaj turin" ?>
        </button>

        <div style="margin-top:12px;text-align:center;">
          <a class="btn-secondary" href="dashboard.php?view=tours">Kthehu te Turet</a>
        </div>
      </form>

    </div>
  </div>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>