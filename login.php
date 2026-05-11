<?php
// views/auth/login.php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once BASE_PATH . '/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    AuthController::login();
}
if (!empty($_SESSION['seller_id'])) redirect('/views/dashboard/index.php');
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Seller Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">
<div class="card shadow-sm" style="width:400px">
  <div class="card-body p-4">
    <h4 class="mb-1 fw-bold"><i class="bi bi-shop"></i> Seller Login</h4>
    <p class="text-muted small mb-3">Sign in to your seller dashboard</p>

    <?php if ($flash): ?>
      <div class="alert alert-<?= e($flash['type']) ?>"><?= $flash['msg'] ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
    <p class="text-center mt-3 small">
      No account? <a href="<?= BASE_URL ?>/views/auth/register.php">Register as Seller</a>
    </p>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>
