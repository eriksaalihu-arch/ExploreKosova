<?php
$pageTitle = "Regjistrohu – ExploreKosova";
require_once "includes/header.php";
require_once "includes/navbar.php";
?>

<main class="page form-page">

    <section class="page-header">
        <h1>Regjistrohu</h1>
        <p>Krijo llogarinë tënde.</p>

        <?php if (!empty($_GET['error'])): ?>
            <p class="error-msg" style="margin-top:10px;">
                <?= htmlspecialchars($_GET['error']) ?>
            </p>
        <?php endif; ?>
    </section>

    <form id="registerForm" class="form-card" method="POST" action="auth_register.php">

        <div>
            <label for="regName">Emri</label>
            <input type="text" id="regName" name="name" placeholder="Shkruaj emrin tënd">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="regEmail">Email</label>
            <input type="email" id="regEmail" name="email" placeholder="Shkruaj emailin tënd">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="regPassword">Fjalëkalimi</label>
            <input type="password" id="regPassword" name="password" placeholder="Zgjidh një fjalëkalim">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="regConfirm">Konfirmo fjalëkalimin</label>
            <input type="password" id="regConfirm" name="confirm" placeholder="Shkruaj përsëri fjalëkalimin">
            <div class="error-msg"></div>
        </div>

        <button type="submit" class="btn-primary">Regjistrohu</button>

        <p>Ke tashmë llogari? <a href="login.php">Kyçu këtu</a></p>
    </form>

</main>

<?php require_once "includes/footer.php"; ?>