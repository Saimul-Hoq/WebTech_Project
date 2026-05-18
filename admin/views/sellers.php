<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Sellers</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; }
    .btn-green { background: #28a745; color: white; }
    .btn-red { background: #dc3545; color: white; }
    .btn-orange { background: #fd7e14; color: white; }
    .btn-blue { background: #007bff; color: white; }
    .badge { padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-approved { background: #d4edda; color: #155724; }
    .badge-rejected { background: #f8d7da; color: #721c24; }
  </style>
</head>
<body>

<?php require_once '../views/nav.php'; ?>

<div class="container">
  <h2>Manage Sellers</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Shop</th>
      <th>Status</th>
      <th>Account</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($sellers as $s): ?>
    <tr>
      <td><?= $s['id'] ?></td>
      <td><?= htmlspecialchars($s['name']) ?></td>
      <td><?= htmlspecialchars($s['email']) ?></td>
      <td><?= htmlspecialchars($s['shop_name']) ?></td>
      <td>
        <span class="badge badge-<?= $s['status'] ?>">
          <?= ucfirst($s['status']) ?>
        </span>
      </td>
      <td><?= $s['is_active'] ? 'Active' : 'Suspended' ?></td>
      <td>
        <?php if ($s['status'] === 'pending'): ?>
          <a class="btn btn-green" href="SellerController.php?action=approve&id=<?= $s['id'] ?>">Approve</a>
          <a class="btn btn-red" href="SellerController.php?action=reject&id=<?= $s['id'] ?>">Reject</a>
        <?php endif; ?>
        <?php if ($s['is_active']): ?>
          <a class="btn btn-orange" href="SellerController.php?action=suspend&id=<?= $s['id'] ?>&user_id=<?= $s['user_id'] ?>">Suspend</a>
        <?php else: ?>
          <a class="btn btn-blue" href="SellerController.php?action=reactivate&id=<?= $s['id'] ?>&user_id=<?= $s['user_id'] ?>">Reactivate</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

</body>
</html>