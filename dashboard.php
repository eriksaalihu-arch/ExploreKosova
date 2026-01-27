<?php
declare(strict_types=1);

$pageTitle = "Dashboard – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/config/Database.php";
require_once __DIR__ . "/app/helpers/auth.php";

requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

$pdo = Database::connection();

$view = $_GET['view'] ?? 'overview';
$allowed = ['overview', 'users', 'messages'];
if (!in_array($view, $allowed, true)) $view = 'overview';

function e(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }

$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalMessages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

$users = [];
if ($view === 'users' || $view === 'overview') {
    $users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC LIMIT 50")->fetchAll();
}

$messages = [];
if ($view === 'messages' || $view === 'overview') {
    $messages = $pdo->query("SELECT id, name, email, message, created_at FROM contact_messages ORDER BY id DESC LIMIT 50")->fetchAll();
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page dashboard-page">

    <section class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Mirësevini, <?= e($_SESSION['user']['name'] ?? 'Admin') ?> 👋</p>
    </section>

    <section style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:18px;">
        <a class="btn-secondary" href="dashboard.php?view=overview">Përmbledhje</a>
        <a class="btn-secondary" href="dashboard.php?view=users">Përdoruesit</a>
        <a class="btn-secondary" href="dashboard.php?view=messages">Mesazhet</a>
    </section>

    <?php if (!empty($_GET['ok'])): ?>
        <p class="success-msg" style="margin-bottom:12px;"><?= e((string)$_GET['ok']) ?></p>
    <?php endif; ?>
    <?php if (!empty($_GET['err'])): ?>
        <p class="error-msg" style="margin-bottom:12px;"><?= e((string)$_GET['err']) ?></p>
    <?php endif; ?>

    <?php if ($view === 'overview'): ?>

        <section class="cards" style="margin-bottom:18px;">
            <article class="card">
                <h3>Totali i përdoruesve</h3>
                <p style="font-size:32px; margin:10px 0;"><?= $totalUsers ?></p>
                <a class="btn-secondary" href="dashboard.php?view=users">Shiko përdoruesit</a>
            </article>

            <article class="card">
                <h3>Totali i mesazheve</h3>
                <p style="font-size:32px; margin:10px 0;"><?= $totalMessages ?></p>
                <a class="btn-secondary" href="dashboard.php?view=messages">Shiko mesazhet</a>
            </article>
        </section>

        <section class="two-cols" style="gap:18px;">
            <article>
                <h2>Përdoruesit e fundit</h2>
                <div class="form-card" style="overflow:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th style="text-align:left; padding:8px;">ID</th>
                            <th style="text-align:left; padding:8px;">Emri</th>
                            <th style="text-align:left; padding:8px;">Email</th>
                            <th style="text-align:left; padding:8px;">Role</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_slice($users, 0, 10) as $u): ?>
                            <tr>
                                <td style="padding:8px;"><?= (int)$u['id'] ?></td>
                                <td style="padding:8px;"><?= e($u['name']) ?></td>
                                <td style="padding:8px;"><?= e($u['email']) ?></td>
                                <td style="padding:8px;"><?= e($u['role']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </article>

            <article>
                <h2>Mesazhet e fundit</h2>
                <div class="form-card" style="overflow:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th style="text-align:left; padding:8px;">Emri</th>
                            <th style="text-align:left; padding:8px;">Email</th>
                            <th style="text-align:left; padding:8px;">Mesazhi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_slice($messages, 0, 8) as $m): ?>
                            <tr>
                                <td style="padding:8px;"><?= e($m['name']) ?></td>
                                <td style="padding:8px;"><?= e($m['email']) ?></td>
                                <td style="padding:8px;"><?= e(mb_strimwidth($m['message'], 0, 60, '...')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

    <?php elseif ($view === 'users'): ?>

        <h2>Përdoruesit</h2>
        <div class="form-card" style="overflow:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                <tr>
                    <th style="text-align:left; padding:8px;">ID</th>
                    <th style="text-align:left; padding:8px;">Emri</th>
                    <th style="text-align:left; padding:8px;">Email</th>
                    <th style="text-align:left; padding:8px;">Role</th>
                    <th style="text-align:left; padding:8px;">Krijuar</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td style="padding:8px;"><?= (int)$u['id'] ?></td>
                        <td style="padding:8px;"><?= e($u['name']) ?></td>
                        <td style="padding:8px;"><?= e($u['email']) ?></td>
                        <td style="padding:8px;"><?= e($u['role']) ?></td>
                        <td style="padding:8px;"><?= e((string)$u['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php elseif ($view === 'messages'): ?>

        <h2>Mesazhet (Contact Form)</h2>

        <?php if (empty($messages)): ?>
            <p class="error-msg">Nuk ka ende mesazhe.</p>
        <?php else: ?>
            <div class="form-card" style="overflow:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                    <tr>
                        <th style="text-align:left; padding:8px;">ID</th>
                        <th style="text-align:left; padding:8px;">Emri</th>
                        <th style="text-align:left; padding:8px;">Email</th>
                        <th style="text-align:left; padding:8px;">Mesazhi</th>
                        <th style="text-align:left; padding:8px;">Data</th>
                        <th style="text-align:left; padding:8px;">Veprime</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($messages as $m): ?>
                        <tr>
                            <td style="padding:8px;"><?= (int)$m['id'] ?></td>
                            <td style="padding:8px;"><?= e($m['name']) ?></td>
                            <td style="padding:8px;"><?= e($m['email']) ?></td>
                            <td style="padding:8px;"><?= e($m['message']) ?></td>
                            <td style="padding:8px;"><?= e((string)$m['created_at']) ?></td>
                            <td style="padding:8px;">
                                <form method="POST" action="admin_message_delete.php" onsubmit="return confirm('A je i sigurt që do ta fshish këtë mesazh?');">
                                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                    <input type="hidden" name="message_id" value="<?= (int)$m['id'] ?>">
                                    <button type="submit" class="btn-secondary">Fshij</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>