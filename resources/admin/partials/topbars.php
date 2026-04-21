<?php
$pageTitle = $pageTitle ?? 'Admin Panel';
$adminName = $_SESSION['users']['name'] ?? 'Admin';
$base = '/GROUP/public/index.php';
?>

<header class="admin-topbar">
    <div class="admin-topbar__left">
        <h1 class="admin-topbar__title">
            <?= htmlspecialchars($pageTitle) ?>
        </h1>
    </div>

    <div class="admin-topbar__right">
        <span class="admin-topbar__welcome">
            Welcome, <?= htmlspecialchars($adminName) ?>
        </span>

        <a href="<?= $base ?>?route=logout" class="admin-topbar__logout">
            Logout
        </a>
    </div>
</header>