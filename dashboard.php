<?php
$pageTitle = "Dashboard – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

$view = $_GET['view'] ?? 'overview';
$pdo  = Database::connection();

/* ===================== COUNTS ===================== */
$totalUsers    = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalMessages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$totalTours    = (int)$pdo->query("SELECT COUNT(*) FROM tours")->fetchColumn();

/* ===================== LATEST DATA ===================== */
$latestUsers = $pdo->query("
  SELECT id, name, email, role, created_at
  FROM users
  ORDER BY id DESC
  LIMIT 5
")->fetchAll();

$latestMessages = $pdo->query("
  SELECT id, name, email, message, created_at
  FROM contact_messages
  ORDER BY id DESC
  LIMIT 5
")->fetchAll();

$latestTours = $pdo->query("
  SELECT id, title, short_description, created_at
  FROM tours
  ORDER BY id DESC
  LIMIT 5
")->fetchAll();

$tours = $pdo->query("
  SELECT id, title, short_description, created_at
  FROM tours
  ORDER BY id DESC
")->fetchAll();

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page">

  <section class="page-header">
    <h1>Admin Dashboard</h1>
    <p>Mirësevini, <?= e($_SESSION['user']['name']) ?>👋🏻</p>
  </section>

  <div class="dashboard-tabs">
    <a href="dashboard.php?view=overview" class="<?= $view === 'overview' ? 'active' : '' ?>">Përmbledhje</a>
    <a href="dashboard.php?view=users" class="<?= $view === 'users' ? 'active' : '' ?>">Përdoruesit</a>
    <a href="dashboard.php?view=messages" class="<?= $view === 'messages' ? 'active' : '' ?>">Mesazhet</a>
    <a href="dashboard.php?view=tours" class="<?= $view === 'tours' ? 'active' : '' ?>">Turet</a>
  </div>

  <?php if ($view === 'overview'): ?>
    <!-- ===== 3 CARDS (Users, Messages, Tours) ===== -->
    <section class="two-cols" style="padding:0; justify-content:center;">

      <div class="stat-card" style="max-width:500px; width:100%;">
        <h3>Totali i përdoruesve</h3>
        <div style="font-size:38px; font-weight:800; margin:6px 0 12px;"><?= $totalUsers ?></div>
        <a class="btn-secondary" href="dashboard.php?view=users">Shiko përdoruesit</a>
      </div>

      <div class="stat-card" style="max-width:500px; width:100%;">
        <h3>Totali i mesazheve</h3>
        <div style="font-size:38px; font-weight:800; margin:6px 0 12px;"><?= $totalMessages ?></div>
        <a class="btn-secondary" href="dashboard.php?view=messages">Shiko mesazhet</a>
      </div>

      <div class="stat-card" style="max-width:500px; width:100%;">
        <h3>Totali i tureve</h3>
        <div style="font-size:38px; font-weight:800; margin:6px 0 12px;"><?= $totalTours ?></div>
        <a class="btn-secondary" href="dashboard.php?view=tours">Shiko turet</a>
      </div>

    </section>

    <!-- ===== LATEST TABLES ===== -->
    <section class="two-cols" style="padding:0; justify-content:center; margin-top:18px;">

      <div class="dashboard-card" style="width:100%; max-width:820px;">
        <h3>Përdoruesit e fundit</h3>
        <div class="table-wrap" style="margin-top:12px;">
          <table class="table" style="min-width:720px;">
            <thead>
              <tr>
                <th>ID</th>
                <th>Emri</th>
                <th>Email</th>
                <th>Roli</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($latestUsers as $u): ?>
                <tr>
                  <td><?= (int)$u['id'] ?></td>
                  <td><?= e($u['name']) ?></td>
                  <td><?= e($u['email']) ?></td>
                  <td><span class="badge"><?= e($u['role']) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="dashboard-card" style="width:100%; max-width:820px;">
        <h3>Mesazhet e fundit</h3>
        <div class="table-wrap" style="margin-top:12px;">
          <table class="table" style="min-width:720px;">
            <thead>
              <tr>
                <th>Emri</th>
                <th>Email</th>
                <th>Mesazhi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($latestMessages as $m): ?>
                <tr>
                  <td><?= e($m['name']) ?></td>
                  <td><?= e($m['email']) ?></td>
                  <td class="msg-cell"><?= e($m['message']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </section>

    <!-- ===== LATEST TOURS TABLE ===== -->
    <section class="two-cols" style="padding:0; justify-content:center; margin-top:18px;">

      <div class="dashboard-card" style="width:100%; max-width:820px;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
          <h3 style="margin:0;">Turet e fundit</h3>
          <a class="btn-primary" href="admin_tour_form.php">Shto tur</a>
        </div>

        <div class="table-wrap" style="margin-top:12px;">
          <table class="table" style="min-width:720px;">
            <thead>
              <tr>
                <th>ID</th>
                <th>Titulli</th>
                <th>Përshkrimi</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($latestTours)): ?>
                <tr>
                  <td colspan="4" style="padding:18px;">
                    <span class="badge">Nuk ka ende ture të regjistruara</span>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($latestTours as $t): ?>
                  <tr>
                    <td><?= (int)$t['id'] ?></td>
                    <td><?= e($t['title']) ?></td>
                    <td class="msg-cell"><?= e($t['short_description']) ?></td>
                    <td><?= e($t['created_at']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </section>

  <?php endif; ?>

  <?php if ($view === 'users'): ?>
    <section class="dashboard-card">
      <h2 style="margin-top:0;">Përdoruesit</h2>

      <div class="table-wrap" style="margin-top:12px;">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Emri</th>
              <th>Email</th>
              <th>Roli</th>
              <th>Krijuar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($latestUsers as $u): ?>
              <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= e($u['name']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><span class="badge"><?= e($u['role']) ?></span></td>
                <td><?= e($u['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </section>
  <?php endif; ?>

  <?php if ($view === 'messages'): ?>
    <section class="dashboard-card">
      <h2 style="margin-top:0;">Mesazhet (Contact Form)</h2>

      <div class="table-wrap" style="margin-top:12px;">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Emri</th>
              <th>Email</th>
              <th>Mesazhi</th>
              <th>Data</th>
              <th>Veprime</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($latestMessages as $m): ?>
              <tr>
                <td><?= (int)$m['id'] ?></td>
                <td><?= e($m['name']) ?></td>
                <td><?= e($m['email']) ?></td>
                <td class="msg-cell"><?= e($m['message']) ?></td>
                <td><?= e($m['created_at']) ?></td>
                <td>
                  <a class="btn-danger"
                     href="admin_message_delete.php?id=<?= (int)$m['id'] ?>&csrf=<?= e($csrf) ?>"
                     onclick="return confirm('A je i sigurt?')">
                     Fshij
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </section>
  <?php endif; ?>

  <?php if ($view === 'tours'): ?>
    <section class="dashboard-card">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
        <h2 style="margin:0;">Turet</h2>
        <a class="btn-primary" href="admin_tour_form.php">Shto tur</a>
      </div>

      <div class="table-wrap" style="margin-top:12px;">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Titulli</th>
              <th>Përshkrimi</th>
              <th>Data</th>
              <th>Veprime</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($tours)): ?>
              <tr>
                <td colspan="5" style="padding:18px;">
                  <span class="badge">Nuk ka ende ture të regjistruara</span>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($tours as $t): ?>
                <tr>
                  <td><?= (int)$t['id'] ?></td>
                  <td><?= e($t['title']) ?></td>
                  <td class="msg-cell"><?= e($t['short_description']) ?></td>
                  <td><?= e($t['created_at']) ?></td>
                  <td style="display:flex; gap:10px; align-items:center;">
                    <a class="btn-secondary" href="admin_tour_form.php?id=<?= (int)$t['id'] ?>">Edito</a>
                    <a class="btn-danger"
                       href="admin_tour_delete.php?id=<?= (int)$t['id'] ?>&csrf=<?= e($csrf) ?>"
                       onclick="return confirm('A je i sigurt që dëshiron ta fshish këtë tur?')">
                       Fshij
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </section>
  <?php endif; ?>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>