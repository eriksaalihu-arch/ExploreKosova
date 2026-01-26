<?php
$pageTitle = "Regjistrohu – ExploreKosova";
require_once "includes/header.php";
require_once "includes/navbar.php";
?>

<main class="page form-page">

    <section class="page-header">
        <h1>Regjistrohu</h1>
        <p>Krijo llogarinë tënde.</p>
    </section>

    <form id="registerForm" class="form-card">

        <div>
            <label for="regName">Emri</label>
            <input type="text" id="regName" placeholder="Shkruaj emrin tënd">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="regEmail">Email</label>
            <input type="email" id="regEmail" placeholder="Shkruaj emailin tënd">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="regPassword">Fjalëkalimi</label>
            <input type="password" id="regPassword" placeholder="Zgjidh një fjalëkalim">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="regConfirm">Konfirmo fjalëkalimin</label>
            <input type="password" id="regConfirm" placeholder="Shkruaj përsëri fjalëkalimin">
            <div class="error-msg"></div>
        </div>

        <button type="submit" class="btn-primary">Regjistrohu</button>

        <p>Ke tashmë llogari? <a href="login.php">Kyçu këtu</a></p>
    </form>

</main>

<?php require_once "includes/footer.php"; ?>