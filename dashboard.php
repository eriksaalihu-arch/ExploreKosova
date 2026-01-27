<?php
$pageTitle = "Dashboard – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";
require_once __DIR__ . "/app/models/User.php";
require_once __DIR__ . "/app/models/Tour.php";

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

$view = $_GET['view'] ?? 'overview';

$pdo = Database::connection();

function e(string $v): string {
  return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalMessages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

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

$tours = $pdo->query("
  SELECT id, title, short_description, created_at
  FROM tours
  ORDER BY id DESC
")->fetchAll();

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page dashboard-page">

  <section class="page-header">
    <h1>Admin Dashboard</h1>
    <p>Mirësevini, <?= e($_SESSION['user']['name']) ?></p>
  </section>

  <nav class="dashboard-nav">
    <a href="dashboard.php?view=overview" class="<?= $view === 'overview' ? 'active' : '' ?>">Përmbledhje</a>
    <a href="dashboard.php?view=users" class="<?= $view === 'users' ? 'active' : '' ?>">Përdoruesit</a>
    <a href="dashboard.php?view=messages" class="<?= $view === 'messages' ? 'active' : '' ?>">Mesazhet</a>
    <a href="dashboard.php?view=tours" class="<?= $view === 'tours' ? 'active' : '' ?>">Turet</a>
  </nav>

  <?php if ($view === 'overview'): ?>
    <section class="dashboard-grid">

      <div class="dash-card">
        <h3>Totali i përdoruesve</h3>
        <div class="dash-number"><?= $totalUsers ?></div>
        <a class="btn-secondary" href="dashboard.php?view=users">Shiko përdoruesit</a>
      </div>

      <div class="dash-card">
        <h3>Totali i mesazheve</h3>
        <div class="dash-number"><?= $totalMessages ?></div>
        <a class="btn-secondary" href="dashboard.php?view=messages">Shiko mesazhet</a>
      </div>

    </section>

    <section class="dashboard-grid two-cols">

      <div class="dash-card">
        <h3>Përdoruesit e fundit</h3>
        <table class="table">
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
                <td><?= e($u['role']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="dash-card">
        <h3>Mesazhet e fundit</h3>
        <table class="table">
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
                <td><?= e(mb_strimwidth($m['message'], 0, 60, '...')) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </section>
  <?php endif; ?>

  <?php if ($view === 'users'): ?>
    <section class="dash-card">
      <h2>Përdoruesit</h2>

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
              <td><?= e($u['role']) ?></td>
              <td><?= e($u['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  <?php endif; ?>

  <?php if ($view === 'messages'): ?>
    <section class="dash-card">
      <h2>Mesazhet (Contact Form)</h2>

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
              <td><?= e($m['message']) ?></td>
              <td><?= e($m['created_at']) ?></td>
              <td>
                <a class="btn-secondary"
                   href="admin_message_delete.php?id=<?= (int)$m['id'] ?>&csrf=<?= e($csrf) ?>"
                   onclick="return confirm('A je i sigurt?')">
                   Fshij
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  <?php endif; ?>

  <?php if ($view === 'tours'): ?>
    <section class="dash-card">
      <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
        <h2>Turet</h2>
        <a class="btn-primary" href="admin_tour_form.php">Shto tur</a>
      </div>

      <?php if (empty($tours)): ?>
        <p class="error-msg">Nuk ka ende ture të regjistruara.</p>
      <?php else: ?>
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
            <?php foreach ($tours as $t): ?>
              <tr>
                <td><?= (int)$t['id'] ?></td>
                <td><?= e($t['title']) ?></td>
                <td><?= e($t['short_description']) ?></td>
                <td><?= e($t['created_at']) ?></td>
                <td>
                  <a class="btn-secondary" href="admin_tour_form.php?id=<?= (int)$t['id'] ?>">Edito</a>
                  <a class="btn-secondary"
                     href="admin_tour_delete.php?id=<?= (int)$t['id'] ?>&csrf=<?= e($csrf) ?>"
                     onclick="return confirm('A je i sigurt që dëshiron ta fshish këtë tur?')">
                     Fshij
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  <?php endif; ?>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>