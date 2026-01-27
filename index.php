<?php
$pageTitle = "Ballina – ExploreKosova";

<?php
$pageTitle = "Ballina – ExploreKosova";

/* LIDHJA ME DATABAZË – VETËM SELECT */
$conn = mysqli_connect("localhost", "root", "", "explore_kosova");

$query = "SELECT hero_title, hero_description FROM home_content LIMIT 1";
$result = mysqli_query($conn, $query);
$home = mysqli_fetch_assoc($result);

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page home-page">

    <section class="hero hero-img">
        <div class="hero-content">
            <h1>Zbulo Kosovën</h1>
            <p>Eksploro natyrën, qytetet dhe traditën e vendit me ture profesionale.</p>
            <a href="services.php" class="btn-primary">Shiko turet</a>
        </div>
    </section>

    <section class="section">
        <h2>Pse ExploreKosova?</h2>

        <div class="cards">

            <article class="card">
                <img src="https://images.unsplash.com/photo-1644175616886-a7644f85fe7c?w=900&auto=format&fit=crop&q=60"
                     alt="Guida lokale për aventurë në Kosovë">
                <h3>Guida lokale</h3>
                <p>Eksploro me ekspertë që njohin vendin.</p>
            </article>

            <article class="card">
                <img src="https://images.unsplash.com/photo-1622151680932-c855a0a0b011?w=900&auto=format&fit=crop&q=60"
                     alt="Kulturë dhe qytete autentike të Kosovës">
                <h3>Qytete &amp; kulturë</h3>
                <p>Përjeto energjinë urbane dhe traditat kulturore të Kosovës.</p>
            </article>

            <article class="card">
                <img src="https://images.unsplash.com/photo-1658413380634-e127bbaeeb7b?w=900&auto=format&fit=crop&q=60"
                     alt="Ushqim tradicional dhe shije lokale të Kosovës">
                <h3>Ushqim tradicional</h3>
                <p>Shije autentike dhe receta lokale.</p>
            </article>

        </div>
    </section>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
