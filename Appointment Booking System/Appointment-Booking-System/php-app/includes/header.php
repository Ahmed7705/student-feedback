<?php
$pageTitle = $pageTitle ?? SITE_TITLE;
$currentPage = basename($_SERVER['PHP_SELF']);
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | <?= e(SITE_TITLE) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="index.php" class="brand"><?= e(SITE_TITLE) ?></a>
            <nav class="main-nav" aria-label="Primary">
                <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
                <a href="book.php" class="<?= $currentPage === 'book.php' ? 'active' : '' ?>">Book</a>
                <a href="manage.php" class="<?= in_array($currentPage, ['manage.php', 'edit.php'], true) ? 'active' : '' ?>">Manage</a>
                <?php if (is_logged_in()): ?>
                    <span class="nav-user"><?= e(current_user_name()) ?></span>
                    <a href="logout.php" class="nav-muted">Sign Out</a>
                <?php else: ?>
                    <a href="login.php" class="<?= in_array($currentPage, ['login.php', 'register.php'], true) ? 'active' : '' ?>">Sign In</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container main-content">
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>" role="alert">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
