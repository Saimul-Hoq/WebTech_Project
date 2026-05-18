<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Commission Rates</title>
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
    input[type="number"] { width: 80px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; }
    .btn-save { background: #28a745; color: white; padding: 5px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; }
  </style>
</head>
<body>

<?php require_once '../views/nav.php'; ?>

<div class="container">
  <h2>Seller Commission Rates</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Shop</th>
      <th>Owner</th>
      <th>Email</th>
      <th>Commission %</th>
      <th>Action</th>
    </tr>
    <?php foreach ($sellers as $s): ?>
    <tr>
      <td><?= $s['id'] ?></td>
      <td><?= htmlspecialchars($s['shop_name']) ?></td>
      <td><?= htmlspecialchars($s['name']) ?></td>
      <td><?= htmlspecialchars($s['email']) ?></td>
      <td>
        <form method="POST" action="../controllers/CommissionController.php?action=update" style="display:flex; gap:5px;">
          <input type="hidden" name="seller_id" value="<?= $s['id'] ?>">
          <input type="number" name="rate" value="<?= $s['commission_rate'] ?>" min="0" max="100" step="0.01">
          <button type="submit" class="btn-save">Save</button>
        </form>
      </td>
      <td></td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($sellers)): ?>
      <tr><td colspan="6" style="text-align:center; color:#999;">No approved sellers.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>