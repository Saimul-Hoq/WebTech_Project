<?php require "../app/views/partials/header.php"; ?>

<section class="account-layout">
    <aside class="profile-card">
        <img src="<?= profile_image($user['profile_pic']) ?>" alt="<?= esc($user['name']) ?>">
        <h2><?= esc($user['name']) ?></h2>
        <p><?= esc($user['email']) ?></p>
    </aside>
    <form method="post" action="<?= url('account/update') ?>" class="form-card">
        <h1>Edit Profile</h1>
        <label>Name
            <input type="text" name="name" value="<?= esc($user['name']) ?>" required>
        </label>
        <label>Email
            <input type="email" value="<?= esc($user['email']) ?>" disabled>
        </label>
        <label>Phone
            <input type="text" name="phone" value="<?= esc($user['phone']) ?>">
        </label>
        <button class="btn btn-primary" type="submit">Save Changes</button>
    </form>
</section>

<?php require "../app/views/partials/footer.php"; ?>
