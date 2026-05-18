<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Categories</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .form-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; max-width: 500px; }
    .form-box input, .form-box select, .form-box textarea { width: 100%; padding: 8px; margin: 6px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
    .form-box button { padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; margin-right: 4px; }
    .btn-red { background: #dc3545; color: white; }
    .error { color: red; margin-bottom: 10px; }
    input[type="text"], textarea { font-size: 13px; }
  </style>
</head>
<body>

<div class="topbar">
  <span>Admin Panel</span>
  <a href="../controllers/AuthController.php?action=logout">Logout</a>
</div>

<div class="nav">
  <a href="../controllers/DashboardController.php">Dashboard</a>
  <a href="../controllers/SellerController.php">Sellers</a>
  <a href="../controllers/CategoryController.php">Categories</a>
  <a href="../controllers/UserController.php">Users</a>
  <a href="../controllers/ProductController.php">Products</a>
  <a href="../controllers/OrderController.php">Orders</a>
  <a href="../controllers/DisputeController.php">Disputes</a>
</div>

<div class="container">
  <h2>Manage Categories</h2>

  <?php if ($error): ?>
    <p class="error"><?= $error ?></p>
  <?php endif; ?>

  <!-- Add Category -->
  <div class="form-box">
    <h3>Add Category</h3>
    <form method="POST" action="CategoryController.php?action=add">
      <input type="text" name="name" placeholder="Category name" required>
      <textarea name="description" placeholder="Description (optional)" rows="2"></textarea>
      <select name="parent_id">
        <option value="">-- No Parent (Top Level) --</option>
        <?php foreach ($categories as $c): ?>
          <?php if (!$c['parent_id']): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endif; ?>
        <?php endforeach; ?>
      </select>
      <button type="submit">Add</button>
    </form>
  </div>

  <!-- Category Table -->
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Parent</th>
      <th>Description</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($categories as $c): ?>
    <tr>
      <td><?= $c['id'] ?></td>
      <td>
        <!-- Inline rename form -->
        <form method="POST" action="CategoryController.php?action=rename" style="display:flex; gap:5px;">
          <input type="hidden" name="id" value="<?= $c['id'] ?>">
          <input type="text" name="name" value="<?= htmlspecialchars($c['name']) ?>" style="width:120px; padding:4px;">
          <button type="submit" class="btn" style="background:#28a745; color:white;">Save</button>
        </form>
      </td>
      <td><?= $c['parent_name'] ? htmlspecialchars($c['parent_name']) : '—' ?></td>
      <td><?= htmlspecialchars($c['description'] ?? '') ?></td>
      <td>
        <a class="btn btn-red" href="CategoryController.php?action=delete&id=<?= $c['id'] ?>"
           onclick="return confirm('Delete this category?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

</body>
</html>