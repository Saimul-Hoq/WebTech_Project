<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .filters { display: flex; gap: 10px; margin-bottom: 20px; }
    .filters input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .filters select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .filters button { padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; }
    .btn-red { background: #dc3545; color: white; }
    .btn-blue { background: #007bff; color: white; }
    .badge-active { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    .badge-inactive { background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
  </style>
</head>
<body>

<?php require_once '../views/nav.php'; ?>

<div class="container">
  <h2>Manage Users</h2>

  <form method="GET" action="UserController.php">
    <div class="filters">
      <input type="text" name="search" placeholder="Search name or email" value="<?= htmlspecialchars($search) ?>">
      <select name="role">
        <option value="customer" <?= $role === 'customer' ? 'selected' : '' ?>>Customers</option>
        <option value="delivery_manager" <?= $role === 'delivery_manager' ? 'selected' : '' ?>>Delivery Managers</option>
      </select>
      <button type="submit">Search</button>
    </div>
  </form>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Role</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($users as $u): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= htmlspecialchars($u['name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
      <td><?= ucfirst($u['role']) ?></td>
      <td>
        <?php if ($u['is_active']): ?>
          <span class="badge-active">Active</span>
        <?php else: ?>
          <span class="badge-inactive">Inactive</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($u['is_active']): ?>
          <a class="btn btn-red" href="UserController.php?action=deactivate&id=<?= $u['id'] ?>"
             onclick="return confirm('Deactivate this user?')">Deactivate</a>
        <?php else: ?>
          <a class="btn btn-blue" href="UserController.php?action=reactivate&id=<?= $u['id'] ?>">Reactivate</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($users)): ?>
      <tr><td colspan="7" style="text-align:center; color:#999;">No users found.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>