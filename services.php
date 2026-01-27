<?php
$pageTitle = "Shërbimet – ExploreKosova";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="page">

    <section class="page-header">
        <h1>Shërbimet tona</h1>
        <p>Zgjedhja perfekte për një aventurë të paharrueshme në Kosovë.</p>
    </section>

    <section class="cards">

        <article class="card">
            <img
                src="https://images.unsplash.com/photo-1664980395016-0bd4b0456ca5?w=900&auto=format&fit=crop&q=60"
                alt="Rugova Adventure"
            >
            <h3>Rugova Adventure</h3>
            <p>Aventura malore, hiking, ujëvara dhe natyrë e paprekur.</p>
            <a href="service-details.php" class="btn-secondary">Shiko detajet</a>
        </article>

        <article class="card">
            <img
                src="https://images.unsplash.com/photo-1653684617625-c6d017d05b80?w=900&auto=format&fit=crop&q=60"
                alt="Prishtina City Tour"
            >
            <h3>Prishtina City Tour</h3>
            <p>Eksplorim historik dhe kulturor i kryeqytetit modern të Kosovës.</p>
            <a href="service-details.php" class="btn-secondary">Shiko detajet</a>
        </article>

        <article class="card">
            <img
                src="https://images.unsplash.com/photo-1622151680932-c855a0a0b011?w=900&auto=format&fit=crop&q=60"
                alt="Prizren Heritage Tour"
            >
            <h3>Prizren Heritage</h3>
            <p>Një shëtitje autentike në qytetin më historik dhe kulturor të Kosovës.</p>
            <a href="service-details.php" class="btn-secondary">Shiko detajet</a>
        </article>

    </section>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>