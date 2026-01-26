<?php
$pageTitle = "Login – ExploreKosova";
require_once "includes/header.php";
require_once "includes/navbar.php";
?>

<main class="page form-page">

    <section class="page-header">
        <h1>Kyçu</h1>
        <p>Shkruaj të dhënat për t'u qasur në llogari.</p>
    </section>

    <form id="loginForm" class="form-card">
        <div>
            <label for="loginEmail">Email</label>
            <input type="email" id="loginEmail" placeholder="Shkruaj emailin">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="loginPassword">Fjalëkalimi</label>
            <input type="password" id="loginPassword" placeholder="Shkruaj fjalëkalimin">
            <div class="error-msg"></div>
        </div>

        <button type="submit" class="btn-primary">Kyçu</button>

        <p>Nuk ke llogari? <a href="register.php">Regjistrohu këtu</a></p>
    </form>

</main>

<?php require_once "includes/footer.php"; ?>