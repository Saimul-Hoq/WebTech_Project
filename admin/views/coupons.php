<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Coupons</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .form-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; max-width: 550px; }
    .form-box input, .form-box select { width: 100%; padding: 8px; margin: 6px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
    .form-box button { padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; }
    .btn-red { background: #dc3545; color: white; }
    .btn-green { background: #28a745; color: white; }
    .badge-active { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    .badge-inactive { background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
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
  <a href="../controllers/CommissionController.php">Commission</a>
  <a href="../controllers/CouponController.php">Coupons</a>
</div>

<div class="container">
  <h2>Platform Coupons</h2>

  <div class="form-box">
    <h3>Add Coupon</h3>
    <form method="POST" action="../controllers/CouponController.php?action=add">
      <select name="seller_id" required>
        <option value="">-- Select Seller --</option>
        <?php foreach ($sellers as $s): ?>
          <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['shop_name']) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="text"   name="code"       placeholder="Coupon code (e.g. SAVE10)" required>
      <input type="number" name="discount"   placeholder="Discount %" min="1" max="100" step="0.01" required>
      <input type="number" name="max_uses"   placeholder="Max uses" min="1" required>
      <input type="date"   name="expires_at" required>
      <button type="submit">Add Coupon</button>
    </form>
  </div>

  <table>
    <tr>
      <th>Code</th>
      <th>Shop</th>
      <th>Discount</th>
      <th>Uses</th>
      <th>Expires</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($coupons as $c): ?>
    <tr>
      <td><?= htmlspecialchars($c['code']) ?></td>
      <td><?= htmlspecialchars($c['shop_name']) ?></td>
      <td><?= $c['discount_percentage'] ?>%</td>
      <td><?= $c['uses_count'] ?> / <?= $c['max_uses'] ?></td>
      <td><?= $c['expires_at'] ?></td>
      <td>
        <?php if ($c['is_active']): ?>
          <span class="badge-active">Active</span>
        <?php else: ?>
          <span class="badge-inactive">Inactive</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($c['is_active']): ?>
          <a class="btn btn-red" href="../controllers/CouponController.php?action=disable&id=<?= $c['id'] ?>">Disable</a>
        <?php else: ?>
          <a class="btn btn-green" href="../controllers/CouponController.php?action=enable&id=<?= $c['id'] ?>">Enable</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($coupons)): ?>
      <tr><td colspan="7" style="text-align:center; color:#999;">No coupons found.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>
