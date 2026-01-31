<?php
$pageTitle = "Menaxho Faqet – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";
require_once __DIR__ . "/app/models/PageContent.php";

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

$page = $_GET['page'] ?? 'home';
if (!in_array($page, ['home', 'about'], true)) $page = 'home';

$data = PageContent::getBySlug($page) ?? [];

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page form-page">

  <section class="page-header">
    <h1>Menaxho Përmbajtjen</h1>
    <p>Vetëm administratori mund ta përditësojë Ballinën dhe faqen “Rreth Nesh”.</p>

    <?php if (!empty($_GET['success'])): ?>
      <div class="success-wrapper">
        <div class="alert alert-success success-message">
          <div class="alert-content">
            <strong>U ruajt me sukses</strong>
            <p>Përmbajtja u përditësua.</p>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
      <div class="success-wrapper">
        <div class="alert error-message" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;">
          <div class="alert-content">
            <strong>Gabim</strong>
            <p><?= e((string)$_GET['error']) ?></p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </section>

  <div class="dashboard-tabs" style="justify-content:center;">
    <a href="admin_pages.php?page=home" class="<?= $page === 'home' ? 'active' : '' ?>">Ballina</a>
    <a href="admin_pages.php?page=about" class="<?= $page === 'about' ? 'active' : '' ?>">Rreth Nesh</a>
    <a href="dashboard.php?view=overview">Kthehu në Dashboard</a>
  </div>

  <form class="form-card" method="POST" action="admin_pages_save.php">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
    <input type="hidden" name="page" value="<?= e($page) ?>">

    <?php if ($page === 'home'): ?>
      <h2 style="margin-top:0;">Ballina (Home)</h2>

      <div>
        <label>Hero Title</label>
        <input type="text" name="hero_title" value="<?= e((string)($data['hero_title'] ?? '')) ?>" required>
      </div>

      <div>
        <label>Hero Subtitle</label>
        <textarea name="hero_subtitle" rows="3" required><?= e((string)($data['hero_subtitle'] ?? '')) ?></textarea>
      </div>

      <div>
        <label>Hero Button Text</label>
        <input type="text" name="hero_button_text" value="<?= e((string)($data['hero_button_text'] ?? '')) ?>" required>
      </div>

      <div>
        <label>Hero Button Link (p.sh. services.php)</label>
        <input type="text" name="hero_button_link" value="<?= e((string)($data['hero_button_link'] ?? 'services.php')) ?>" required>
      </div>

      <div>
        <label>Why Section Title</label>
        <input type="text" name="why_title" value="<?= e((string)($data['why_title'] ?? '')) ?>" required>
      </div>

      <hr style="border:none;border-top:1px solid #eee;margin:16px 0;">

      <?php
        $cards = $data['cards'] ?? [];
        for ($i=0; $i<3; $i++) {
          $cards[$i] = $cards[$i] ?? ['title'=>'','text'=>'','image'=>''];
        }
      ?>

      <?php for ($i=0; $i<3; $i++): ?>
        <h3 style="margin:12px 0 6px;">Card <?= $i+1 ?></h3>

        <div>
          <label>Titulli</label>
          <input type="text" name="card_title[]" value="<?= e((string)($cards[$i]['title'] ?? '')) ?>" required>
        </div>

        <div>
          <label>Teksti</label>
          <input type="text" name="card_text[]" value="<?= e((string)($cards[$i]['text'] ?? '')) ?>" required>
        </div>

        <div>
          <label>URL e Fotos (link)</label>
          <input type="text" name="card_image[]" value="<?= e((string)($cards[$i]['image'] ?? '')) ?>" required>
        </div>

        <?php if ($i < 2): ?><hr style="border:none;border-top:1px solid #eee;margin:12px 0;"><?php endif; ?>
      <?php endfor; ?>

    <?php else: ?>
      <h2 style="margin-top:0;">Rreth Nesh (About)</h2>

      <div>
        <label>Page Title</label>
        <input type="text" name="page_title" value="<?= e((string)($data['page_title'] ?? '')) ?>" required>
      </div>

      <div>
        <label>Page Subtitle</label>
        <textarea name="page_subtitle" rows="3" required><?= e((string)($data['page_subtitle'] ?? '')) ?></textarea>
      </div>

      <hr style="border:none;border-top:1px solid #eee;margin:16px 0;">

      <?php
        $sections = $data['sections'] ?? [];
        for ($i=0; $i<4; $i++) {
          $sections[$i] = $sections[$i] ?? ['title'=>'','text'=>''];
        }
      ?>

      <?php for ($i=0; $i<4; $i++): ?>
        <h3 style="margin:12px 0 6px;">Seksioni <?= $i+1 ?></h3>

        <div>
          <label>Titulli</label>
          <input type="text" name="sec_title[]" value="<?= e((string)($sections[$i]['title'] ?? '')) ?>" required>
        </div>

        <div>
          <label>Teksti</label>
          <textarea name="sec_text[]" rows="3" required><?= e((string)($sections[$i]['text'] ?? '')) ?></textarea>
        </div>

        <?php if ($i < 3): ?><hr style="border:none;border-top:1px solid #eee;margin:12px 0;"><?php endif; ?>
      <?php endfor; ?>

    <?php endif; ?>

    <button class="btn-primary" type="submit" style="margin-top:12px;">Ruaj ndryshimet</button>
  </form>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>