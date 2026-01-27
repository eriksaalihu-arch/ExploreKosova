<?php
$pageTitle = "Dashboard – Admin";
require_once __DIR__ . "/app/config/config.php";
require_once __DIR__ . "/app/helpers/auth.php";
requireAdmin();

require_once "includes/header.php";
require_once "includes/navbar.php";
?>

<main class="page">
  <section class="page-header">
    <h1>Admin Dashboard</h1>
    <p>Mirë se erdhe, <?= htmlspecialchars($_SESSION['user']['name']) ?> (Admin)</p>
  </section>

  <section class="two-cols">
    <article>
      <h2>Menaxho përmbajtjen</h2>
      <p>Këtu do të vijnë CRUD për Products/News/Contact.</p>
    </article>
    <article>
      <h2>Mesazhet</h2>
      <p>Këtu do të shfaqen kontakt-mesazhet nga databaza.</p>
    </article>
  </section>
</main>

<?php require_once "includes/footer.php"; ?>