<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>All Orders</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .filters { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
    .filters input, .filters select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .filters button { padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; }
    .btn-blue { background: #007bff; color: white; }
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
  <h2>All Orders</h2>

  <form method="GET" action="OrderController.php">
    <div class="filters">
      <select name="status">
        <option value="">All Statuses</option>
        <?php foreach (['pending','confirmed','processing','shipped','delivered','cancelled'] as $s): ?>
          <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="date" name="date_from" value="<?= $date_from ?>">
      <input type="date" name="date_to"   value="<?= $date_to ?>">
      <select name="seller_id">
        <option value="">All Sellers</option>
        <?php foreach ($sellers as $s): ?>
          <option value="<?= $s['id'] ?>" <?= $seller_id == $s['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['shop_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Filter</button>
    </div>
  </form>

  <table>
    <tr>
      <th>Order ID</th>
      <th>Customer</th>
      <th>Email</th>
      <th>Total</th>
      <th>Payment</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($orders as $o): ?>
    <tr>
      <td>#<?= $o['id'] ?></td>
      <td><?= htmlspecialchars($o['customer_name']) ?></td>
      <td><?= htmlspecialchars($o['customer_email']) ?></td>
      <td>$<?= number_format($o['total_amount'], 2) ?></td>
      <td><?= htmlspecialchars($o['payment_method'] ?? '—') ?></td>
      <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
      <td>
        <a class="btn btn-blue" href="OrderController.php?action=view&id=<?= $o['id'] ?>">View</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($orders)): ?>
      <tr><td colspan="7" style="text-align:center; color:#999;">No orders found.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>