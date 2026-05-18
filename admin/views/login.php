<?php session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: ../controllers/AuthController.php?action=dashboard");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <style>
    body { font-family: Arial; background: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
    .box { background: white; padding: 30px; border-radius: 8px; width: 320px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h2 { text-align: center; margin-bottom: 20px; }
    input { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
    button { width: 100%; padding: 10px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .error { color: red; text-align: center; margin-top: 10px; }
  </style>
</head>
<body>
<div class="box">
  <h2>Admin Login</h2>
  <form method="POST" action="../controllers/AuthController.php?action=login">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
  <?php if (isset($_SESSION['login_error'])): ?>
    <p class="error"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
  <?php endif; ?>
</div>
</body>
</html>