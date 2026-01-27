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

    <section class="dashboard-tabs">
        <a class="<?= $view==='overview' ? 'active' : '' ?>" href="dashboard.php?view=overview">Përmbledhje</a>
        <a class="<?= $view==='users' ? 'active' : '' ?>" href="dashboard.php?view=users">Përdoruesit</a>
        <a class="<?= $view==='messages' ? 'active' : '' ?>" href="dashboard.php?view=messages">Mesazhet</a>
    </section>

    <?php if (!empty($_GET['ok'])): ?>
        <p class="success-msg" style="margin-bottom:12px;"><?= e((string)$_GET['ok']) ?></p>
    <?php endif; ?>
    <?php if (!empty($_GET['err'])): ?>
        <p class="error-msg" style="margin-bottom:12px;"><?= e((string)$_GET['err']) ?></p>
    <?php endif; ?>

    <?php if ($view === 'overview'): ?>

        <section class="two-cols" style="gap:18px; margin-bottom:18px;">
            <article class="dashboard-card">
                <h3>Totali i përdoruesve</h3>
                <p style="font-size:34px; margin:10px 0; font-weight:800;"><?= $totalUsers ?></p>
                <a class="btn-secondary" href="dashboard.php?view=users">Shiko përdoruesit</a>
            </article>

            <article class="dashboard-card">
                <h3>Totali i mesazheve</h3>
                <p style="font-size:34px; margin:10px 0; font-weight:800;"><?= $totalMessages ?></p>
                <a class="btn-secondary" href="dashboard.php?view=messages">Shiko mesazhet</a>
            </article>
        </section>

        <section class="two-cols" style="gap:18px;">
            <article>
                <h2>Përdoruesit e fundit</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Emri</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_slice($users, 0, 10) as $u): ?>
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
            </article>

            <article>
                <h2>Mesazhet e fundit</h2>
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
                        <?php foreach (array_slice($messages, 0, 8) as $m): ?>
                            <tr>
                                <td><?= e($m['name']) ?></td>
                                <td><?= e($m['email']) ?></td>
                                <td class="msg-cell" title="<?= e($m['message']) ?>"><?= e($m['message']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

    <?php elseif ($view === 'users'): ?>

        <h2>Përdoruesit</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Emri</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Krijuar</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= (int)$u['id'] ?></td>
                        <td><?= e($u['name']) ?></td>
                        <td><?= e($u['email']) ?></td>
                        <td><span class="badge"><?= e($u['role']) ?></span></td>
                        <td><?= e((string)$u['created_at']) ?></td>
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
            <div class="table-wrap">
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
                    <?php foreach ($messages as $m): ?>
                        <tr>
                            <td><?= (int)$m['id'] ?></td>
                            <td><?= e($m['name']) ?></td>
                            <td><?= e($m['email']) ?></td>
                            <td class="msg-cell" title="<?= e($m['message']) ?>"><?= e($m['message']) ?></td>
                            <td><?= e((string)$m['created_at']) ?></td>
                            <td>
                                <form method="POST" action="admin_message_delete.php"
                                      onsubmit="return confirm('A je i sigurt që do ta fshish këtë mesazh?');">
                                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                    <input type="hidden" name="message_id" value="<?= (int)$m['id'] ?>">
                                    <button type="submit" class="btn-danger">Fshij</button>
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