<?php
$pageTitle = "Kontakt – ExploreKosova";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page form-page">

    <section class="page-header">
        <h1>Na kontakto</h1>
        <p>Na shkruaj për rezervime ose informata rreth shërbimeve tona.</p>

        <?php if (!empty($_GET['error'])): ?>
            <p class="error-msg" style="margin-top:10px;">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success">
        <span class="alert-icon">✅</span>
        <div class="alert-content">
            <strong>Mesazhi u dërgua!</strong>
            <p>Faleminderit që na kontaktuat. Do t’ju përgjigjemi sa më shpejt.</p>
        </div>
    </div>
    <?php endif; ?>
    </section>

    <form
        id="contactForm"
        class="form-card"
        method="POST"
        action="contact_submit.php"
    >

        <div>
            <label for="contactName">Emri</label>
            <input
                type="text"
                id="contactName"
                name="name"
                placeholder="Shkruaj emrin tënd"
                required
            >
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="contactEmail">Email</label>
            <input
                type="email"
                id="contactEmail"
                name="email"
                placeholder="Shkruaj emailin tënd"
                required
            >
            <div class="error-msg"></div>
        </div>

        <div>
            <label for="contactMessage">Mesazhi</label>
            <textarea
                id="contactMessage"
                name="message"
                placeholder="Shkruaj mesazhin..."
                rows="5"
                required
            ></textarea>
            <div class="error-msg"></div>
        </div>

        <button class="btn-primary" type="submit">
            Dërgo
        </button>
    </form>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>