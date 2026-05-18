<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? APP_NAME) ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/styles.css') ?>">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <a class="brand" href="<?= url('') ?>">
                <span class="brand-mark">S</span>
                <span><?= APP_NAME ?></span>
            </a>

            <form class="nav-search" method="get" action="<?= url('') ?>">
                <input type="hidden" name="url" value="home">
                <input type="search" name="q" placeholder="Search products" value="<?= esc($_GET['q'] ?? '') ?>">
                <button type="submit">Search</button>
            </form>

            <div class="nav-links">
                <a href="<?= url('cart') ?>">Cart <span class="pill"><?= cart_count() ?></span></a>
                <?php if ($user): ?>
                    <a href="<?= url('wishlist') ?>">Wishlist</a>
                    <a href="<?= url('orders') ?>">Orders</a>
                    <a href="<?= url('account/profile') ?>">Profile</a>
                    <a class="btn btn-ghost" href="<?= url('auth/logout') ?>">Logout</a>
                <?php else: ?>
                    <a href="<?= url('auth/login') ?>">Login</a>
                    <a class="btn btn-primary" href="<?= url('auth/register') ?>">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="page-shell">
        <?php if ($message = flash('success')): ?>
            <div class="flash success"><?= esc($message) ?></div>
        <?php endif; ?>
        <?php if ($message = flash('error')): ?>
            <div class="flash error"><?= esc($message) ?></div>
        <?php endif; ?>
