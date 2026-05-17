<?php require_once __DIR__ . '/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <h3 class="mb-3">Delivery Manager Login</h3>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <form action="/WebTech_Project/delivery_manager/controllers/AuthController.php?action=login" method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>