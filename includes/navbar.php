<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$current = basename($_SERVER['PHP_SELF']);
function isActive(string $file, string $current): string {
    return $file === $current ? "active" : "";
}
?>
<header class="top-bar">
  <div class="logo">Explore<span>Kosova</span></div>
  <nav>
    <ul class="nav-links">
      <li><a class="<?= isActive('index.php',$current) ?>" href="index.php">Ballina</a></li>
      <li><a class="<?= isActive('about.php',$current) ?>" href="about.php">Rreth Nesh</a></li>
      <li><a class="<?= isActive('services.php',$current) ?>" href="services.php">Shërbimet</a></li>
      <li><a class="<?= isActive('contact.php',$current) ?>" href="contact.php">Kontakt</a></li>

      <?php if (!empty($_SESSION['user'])): ?>
        <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
          <li><a class="<?= isActive('dashboard.php',$current) ?>" href="dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li><a class="btn-nav" href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a class="btn-nav <?= isActive('login.php',$current) ?>" href="login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>