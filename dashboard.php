<?php
$pageTitle = "Dashboard – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

require_once __DIR__ . "/app/models/User.php";
require_once __DIR__ . "/app/models/ContactMessage.php";
require_once __DIR__ . "/app/models/Tour.php";

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

$view = $_GET['view'] ?? 'overview';

$totalUsers    = User::count();
$totalMessages = ContactMessage::count();
$totalTours    = Tour::count();

$users    = User::latest(5);
$messages = ContactMessage::latest(5);
$tours    = Tour::latest(5);

function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page">

  <section class="page-header">
    <h1>Admin Dashboard</h1>
    <p>Mirësevini, <?= e($_SESSION['user']['name']) ?></p>
  </section>

  <!-- Tabs -->
  <div class="dashboard-tabs" style="justify-content:center;">
    <a href="dashboard.php?view=overview" class="<?= $view === 'overview' ? 'active' : '' ?>">Përmbledhje</a>
    <a href="dashboard.php?view=users" class="<?= $view === 'users' ? 'active' : '' ?>">Përdoruesit</a>
    <a href="dashboard.php?view=messages" class="<?= $view === 'messages' ? 'active' : '' ?>">Mesazhet</a>
    <a href="dashboard.php?view=tours" class="<?= $view === 'tours' ? 'active' : '' ?>">Turet</a>
  </div>

  <!-- ================= OVERVIEW ================= -->
  <?php if ($view === 'overview'): ?>

  <section class="cards" style="margin-bottom:30px;">
    <div class="dashboard-card">
      <h3>Totali i përdoruesve</h3>
      <h2><?= $totalUsers ?></h2>
      <a href="dashboard.php?view=users" class="btn-secondary">Shiko përdoruesit</a>
    </div>

    <div class="dashboard-card">
      <h3>Totali i mesazheve</h3>
      <h2><?= $totalMessages ?></h2>
      <a href="dashboard.php?view=messages" class="btn-secondary">Shiko mesazhet</a>
    </div>

    <div class="dashboard-card">
      <h3>Totali i tureve</h3>
      <h2><?= $totalTours ?></h2>
      <a href="dashboard.php?view=tours" class="btn-secondary">Shiko turet</a>
    </div>
  </section>

  <section class="two-cols">

    <article>
      <h3>Përdoruesit e fundit</h3>
      <div class="table-wrap">
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
            <?php foreach ($users as $u): ?>
              <tr>
                <td><?= $u['id'] ?></td>
                <td><?= e($u['name']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><span class="badge"><?= e($u['role']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </article>

    <article>
      <h3>Mesazhet e fundit</h3>
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>Emri</th>
              <th>Email</th>
              <th>Mesazhi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($messages as $m): ?>
              <tr>
                <td><?= e($m['name']) ?></td>
                <td><?= e($m['email']) ?></td>
                <td class="msg-cell"><?= e($m['message']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </article>

  </section>

  <?php endif; ?>

  <!-- ================= USERS ================= -->
  <?php if ($view === 'users'): ?>

  <section class="dashboard-card">
    <h3>Përdoruesit</h3>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Emri</th>
            <th>Email</th>
            <th>Roli</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (User::all() as $u): ?>
            <tr>
              <td><?= $u['id'] ?></td>
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

  <!-- ================= MESSAGES ================= -->
  <?php if ($view === 'messages'): ?>

  <section class="dashboard-card">
    <h3>Mesazhet (Contact Form)</h3>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Emri</th>
            <th>Email</th>
            <th>Mesazhi</th>
            <th>Data</th>
            <th>Veprim</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (ContactMessage::all() as $m): ?>
            <tr>
              <td><?= $m['id'] ?></td>
              <td><?= e($m['name']) ?></td>
              <td><?= e($m['email']) ?></td>
              <td class="msg-cell"><?= e($m['message']) ?></td>
              <td><?= e($m['created_at']) ?></td>
              <td>
                <form method="POST" action="admin_message_delete.php">
                  <input type="hidden" name="id" value="<?= $m['id'] ?>">
                  <button class="btn-danger">Fshij</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <?php endif; ?>

  <!-- ================= TOURS ================= -->
  <?php if ($view === 'tours'): ?>

  <section class="dashboard-card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
      <h3>Turet</h3>
      <a href="admin_tour_form.php" class="btn-primary">Shto tur</a>
    </div>

    <div class="table-wrap" style="margin-top:15px;">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Titulli</th>
            <th>Përshkrimi</th>
            <th>Data</th>
            <th>Veprim</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty(Tour::all())): ?>
            <tr>
              <td colspan="5">Nuk ka ende ture të regjistruara</td>
            </tr>
          <?php else: ?>
            <?php foreach (Tour::all() as $t): ?>
              <tr>
                <td><?= $t['id'] ?></td>
                <td><?= e($t['title']) ?></td>
                <td class="msg-cell"><?= e($t['short_description']) ?></td>
                <td><?= e($t['created_at']) ?></td>
                <td style="display:flex; gap:8px;">
                  <a href="admin_tour_form.php?id=<?= $t['id'] ?>" class="btn-secondary">Edit</a>
                  <form method="POST" action="admin_tour_delete.php">
                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                    <button class="btn-danger">Fshij</button>
                  </form>
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