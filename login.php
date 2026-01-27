<?php
$pageTitle = "Login – ExploreKosova";
require_once "includes/header.php";
require_once "includes/navbar.php";
?>

<main class="page form-page">

    <section class="page-header">
        <h1>Kyçu</h1>
        <p>Shkruaj të dhënat për t'u qasur në llogari.</p>

        <?php if (!empty($_GET['error'])): ?>
            <p class="error-msg" style="margin-top:10px;">
                <?= htmlspecialchars($_GET['error']) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($_GET['success'])): ?>
            <p class="success-msg" style="margin-top:10px;">
                Regjistrimi u krye me sukses. Tani mund të kyçesh.
            </p>
        <?php endif; ?>
    </section>

    <form id="loginForm" class="form-card" method="POST" action="auth_login.php">
        <div>
            <label for="loginEmail">Email</label>
            <input type="email" id="loginEmail" name="email" placeholder="Shkruaj emailin">
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

<?php require_once "includes/footer.php"; ?>