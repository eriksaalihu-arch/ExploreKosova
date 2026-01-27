<?php
$pageTitle = "Login – ExploreKosova";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";

function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>

<main class="page form-page">

    <section class="page-header">
        <h1>Kyçu</h1>
        <p>Shkruaj të dhënat për t'u qasur në llogari.</p>

        <?php if (!empty($_GET['error'])): ?>
            <div class="auth-alert error">
                Email ose fjalëkalimi nuk janë të saktë.
            </div>
        <?php endif; ?>

        <?php if (!empty($_GET['success'])): ?>
            <div class="auth-alert success">
                Regjistrimi u krye me sukses. Tani mund të kyçesh.
            </div>
        <?php endif; ?>

    </section>

    <form id="loginForm" class="form-card" method="POST" action="auth_login.php">

        <div>
            <label for="loginEmail">Email</label>
            <input
                type="email"
                id="loginEmail"
                name="email"
                placeholder="Shkruaj emailin"
                value="<?= !empty($_GET['email']) ? e((string)$_GET['email']) : '' ?>"
            >
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="loginPassword">Fjalëkalimi</label>
            <input type="password" id="loginPassword" name="password" placeholder="Shkruaj fjalëkalimin">
            <div class="error-msg"></div>
        </div>

        <button type="submit" class="btn-primary">Kyçu</button>

        <p>Nuk ke llogari? <a href="register.php">Regjistrohu këtu</a></p>
    </form>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>