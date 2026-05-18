<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Analytics</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; flex-wrap: wrap; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .cards { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px; }
    .card { background: white; padding: 20px; border-radius: 8px; min-width: 200px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .card h3 { margin: 0 0 10px; font-size: 14px; color: #666; }
    .card p { margin: 0; font-size: 28px; font-weight: bold; color: #333; }
    .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .section h3 { margin-top: 0; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #f8f8f8; }
    .print-btn { padding: 10px 20px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 20px; }
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
  <a href="../controllers/AnalyticsController.php">Analytics</a>
</div>

<div class="container">
  <h2>Platform Analytics</h2>

  <button class="print-btn" onclick="window.print()">Print / Export Report</button>

  <!-- Summary Cards -->
  <div class="cards">
    <div class="card">
      <h3>Gross Merchandise Value</h3>
      <p>$<?= number_format($gmv, 2) ?></p>
    </div>
    <div class="card">
      <h3>Total Commission Earned</h3>
      <p>$<?= number_format($commission, 2) ?></p>
    </div>
  </div>

  <!-- Monthly Revenue -->
  <div class="section">
    <h3>Monthly Revenue</h3>
    <table>
      <tr><th>Month</th><th>Revenue</th></tr>
      <?php foreach ($monthly as $m): ?>
      <tr>
        <td><?= $m['month'] ?></td>
        <td>$<?= number_format($m['revenue'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($monthly)): ?>
        <tr><td colspan="2" style="color:#999;">No data.</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <!-- Top Sellers -->
  <div class="section">
    <h3>Top Performing Sellers</h3>
    <table>
      <tr><th>Shop</th><th>Owner</th><th>Total Sales</th></tr>
      <?php foreach ($top_sellers as $s): ?>
      <tr>
        <td><?= htmlspecialchars($s['shop_name']) ?></td>
        <td><?= htmlspecialchars($s['name']) ?></td>
        <td>$<?= number_format($s['total_sales'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($top_sellers)): ?>
        <tr><td colspan="3" style="color:#999;">No data.</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <!-- Top Categories -->
  <div class="section">
    <h3>Top Selling Categories</h3>
    <table>
      <tr><th>Category</th><th>Total Sales</th></tr>
      <?php foreach ($top_categories as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['category_name']) ?></td>
        <td>$<?= number_format($c['total_sales'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($top_categories)): ?>
        <tr><td colspan="2" style="color:#999;">No data.</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <!-- Delivery Overview -->
  <div class="section">
    <h3>Delivery Performance Overview</h3>
    <table>
      <tr><th>Status</th><th>Total</th></tr>
      <?php foreach ($delivery as $d): ?>
      <tr>
        <td><?= ucfirst($d['status']) ?></td>
        <td><?= $d['total'] ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($delivery)): ?>
        <tr><td colspan="2" style="color:#999;">No data.</td></tr>
      <?php endif; ?>
    </table>
  </div>

</div>

</body>
</html>