<?php require "../app/views/partials/header.php"; ?>

<section class="auth-card">
    <div>
        <p class="eyebrow">Customer access</p>
        <h1>Login</h1>
        <p>Only customer accounts can login here. Seller, admin, and delivery manager accounts will be blocked.</p>
    </div>
    <form method="post" class="form-card">
        <label>Email
            <input type="email" name="email" value="<?= old('email') ?>" required>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <button class="btn btn-primary" type="submit">Login</button>
        <p>New customer? <a href="<?= url('auth/register') ?>">Create an account</a></p>
    </form>
</section>

<?php clear_old(); ?>
<?php require "../app/views/partials/footer.php"; ?>
