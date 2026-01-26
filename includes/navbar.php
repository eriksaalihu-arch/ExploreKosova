<?php
$current = basename($_SERVER['PHP_SELF']);

function isActive(string $file, string $current): string {
    return $file === $current ? "active" : "";
}
?>
<header class="top-bar">
    <div class="logo">Explore<span>Kosova</span></div>
    <nav>
        <ul class="nav-links">
            <li>
                <a class="<?= isActive('index.php', $current) ?>" href="index.php">
                    Ballina
                </a>
            </li>
            <li>
                <a class="<?= isActive('about.php', $current) ?>" href="about.php">
                    Rreth Nesh
                </a>
            </li>
            <li>
                <a class="<?= isActive('products.php', $current) ?>" href="products.php">
                    Shërbimet
                </a>
            </li>
            <li>
                <a class="<?= isActive('contact.php', $current) ?>" href="contact.php">
                    Kontakt
                </a>
            </li>
            <li>
                <a class="btn-nav <?= isActive('login.php', $current) ?>" href="login.php">
                    Login
                </a>
            </li>
        </ul>
    </nav>
</header>