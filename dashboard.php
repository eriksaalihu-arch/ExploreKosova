<?php
$pageTitle = "Dashboard – ExploreKosova";

require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/helpers/auth.php";

requireAdmin();

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page dashboard-page">

    <section class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Mirësevini, <?= htmlspecialchars($_SESSION['user']['name']) ?> 👋</p>
    </section>

    <section class="dashboard-cards">

        <article class="card">
            <h3>Përdoruesit</h3>
            <p>Menaxho përdoruesit e regjistruar.</p>
            <a href="#" class="btn-secondary">Shiko përdoruesit</a>
        </article>

        <article class="card">
            <h3>Mesazhet</h3>
            <p>Lexo mesazhet nga Contact Form.</p>
            <a href="#" class="btn-secondary">Shiko mesazhet</a>
        </article>

        <article class="card">
            <h3>Përmbajtja</h3>
            <p>Menaxho lajme, produkte ose shërbime.</p>
            <a href="#" class="btn-secondary">Menaxho përmbajtjen</a>
        </article>

    </section>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>