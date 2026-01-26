<?php
$pageTitle = "Kontakt – ExploreKosova";
require_once "includes/header.php";
require_once "includes/navbar.php";
?>

<main class="page form-page">

    <section class="page-header">
        <h1>Na kontakto</h1>
        <p>Na shkruaj për rezervime ose informata rreth shërbimeve tona.</p>
    </section>

    <form id="contactForm" class="form-card">
        <div>
            <label for="contactName">Emri</label>
            <input type="text" id="contactName" placeholder="Shkruaj emrin tënd">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="contactEmail">Email</label>
            <input type="email" id="contactEmail" placeholder="Shkruaj emailin tënd">
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="contactMessage">Mesazhi</label>
            <textarea id="contactMessage" placeholder="Shkruaj mesazhin..."></textarea>
            <div class="error-msg"></div>
        </div>

        <button class="btn-primary" type="submit">Dërgo</button>
        <div id="contactSuccess" class="success-msg"></div>
    </form>

</main>

<?php require_once "includes/footer.php"; ?>