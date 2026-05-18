<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Profile</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; flex-wrap: wrap; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; max-width: 500px; }
    .card { background: white; padding: 25px; border-radius: 8px; margin-bottom: 20px; }
    .card h3 { margin-top: 0; }
    .card p { font-size: 14px; margin: 8px 0; }
    label { font-weight: bold; font-size: 14px; }
    input { width: 100%; padding: 8px; margin: 6px 0 12px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
    button { padding: 10px 20px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .success { color: green; margin-bottom: 10px; font-size: 14px; }
    .error { color: red; margin-bottom: 10px; font-size: 14px; }
  </style>
</head>
<body>

<?php require_once '../views/nav.php'; ?>

<div class="container">
  <h2>Admin Profile</h2>

  <div class="card">
    <h3>Account Info</h3>
    <p><label>Name:</label> <?= htmlspecialchars($admin['name']) ?></p>
    <p><label>Email:</label> <?= htmlspecialchars($admin['email']) ?></p>
    <p><label>Role:</label> <?= ucfirst($admin['role']) ?></p>
    <p><label>Joined:</label> <?= date('d M Y', strtotime($admin['created_at'])) ?></p>
  </div>

  <div class="card">
    <h3>Change Password</h3>
    <?php if ($success): ?>
      <p class="success"><?= $success ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="../controllers/ProfileController.php">
      <label>Current Password</label>
      <input type="password" name="current_password" required>
      <label>New Password</label>
      <input type="password" name="new_password" required>
      <label>Confirm Password</label>
      <input type="password" name="confirm_password" required>
      <button type="submit">Update Password</button>
    </form>
  </div>
</div>

</body>
</html>