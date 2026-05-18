<?php require "../app/views/partials/header.php"; ?>

<section class="auth-card">
    <div>
        <p class="eyebrow">Join <?= APP_NAME ?></p>
        <h1>Create customer account</h1>
        <p>Register as a customer to save wishlists, place orders, and manage your profile.</p>
    </div>
    <form method="post" class="form-card">
        <label>Name
            <input type="text" name="name" value="<?= old('name') ?>" required>
        </label>
        <label>Email
            <input type="email" name="email" value="<?= old('email') ?>" required>
        </label>
        <label>Phone
            <input type="text" name="phone" value="<?= old('phone') ?>">
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <label>Confirm Password
            <input type="password" name="confirm_password" required>
        </label>
        <button class="btn btn-primary" type="submit">Register</button>
        <p>Already registered? <a href="<?= url('auth/login') ?>">Login</a></p>
    </form>
</section>

<?php clear_old(); ?>
<?php require "../app/views/partials/footer.php"; ?>
