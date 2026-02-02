<?php
$pageTitle = "Menaxho Tur – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$pdo = Database::connection();
$tour = null;

if ($id > 0) {
  $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
  $stmt->execute([$id]);
  $tour = $stmt->fetch();
  if (!$tour) {
    header("Location: dashboard.php?view=tours");
    exit;
  }
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page form-page">
  <section class="page-header">
    <h1><?= $tour ? "Edito Tur" : "Shto Tur" ?></h1>

    <?php if (!empty($_GET['error'])): ?>
      <p class="error-msg" style="margin-top:10px; text-align:center;">
        <?= e($_GET['error']) ?>
      </p>
    <?php endif; ?>
  </section>

  <form class="form-card" method="POST" action="admin_tour_save.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
    <input type="hidden" name="id" value="<?= $tour ? (int)$tour['id'] : 0 ?>">

    <div>
      <label>Titulli</label>
      <input type="text" name="title" required value="<?= $tour ? e($tour['title']) : "" ?>">
    </div>

    <div>
      <label>Përshkrimi i shkurtër</label>
      <input type="text" name="short_description" required value="<?= $tour ? e($tour['short_description']) : "" ?>">
    </div>

    <div>
      <label>Përmbajtja</label>
      <textarea name="content" rows="6" required><?= $tour ? e($tour['content']) : "" ?></textarea>
    </div>

    <div>
      <label>Foto (opsionale)</label>
      <input type="file" name="image" accept="image/*">
    </div>

    <div>
      <label>PDF (opsionale)</label>
      <input type="file" name="pdf" accept="application/pdf">
    </div>

    <button class="btn-primary" type="submit"><?= $tour ? "Ruaj Ndryshimet" : "Shto Tur" ?></button>
  </form>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>