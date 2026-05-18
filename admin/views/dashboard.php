<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .container { padding: 30px; }
    h2 { margin-bottom: 20px; }
    .cards { display: flex; gap: 20px; flex-wrap: wrap; }
    .card { background: white; padding: 20px; border-radius: 8px; width: 200px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .card h3 { margin: 0 0 10px; font-size: 14px; color: #666; }
    .card p { margin: 0; font-size: 28px; font-weight: bold; color: #333; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .nav a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<?php require_once '../views/nav.php'; ?>

<div class="container">
  <h2>Welcome, <?= $_SESSION['admin_name'] ?></h2>
  <div class="cards">
    <div class="card">
      <h3>Total Customers</h3>
      <p><?= $users['customer'] ?? 0 ?></p>
    </div>
    <div class="card">
      <h3>Total Sellers</h3>
      <p><?= $users['seller'] ?? 0 ?></p>
    </div>
    <div class="card">
      <h3>Active Sellers</h3>
      <p><?= $sellers ?></p>
    </div>
    <div class="card">
      <h3>Orders Today</h3>
      <p><?= $orders ?></p>
    </div>
    <div class="card">
      <h3>Revenue This Month</h3>
      <p>$<?= number_format($revenue, 2) ?></p>
    </div>
    <div class="card">
    <h3>Total Orders</h3>
    <p><?= $total_orders ?></p>
    </div>
    <div class="card">
    <h3>Open Disputes</h3>
    <p><?= $pending_disputes ?></p>
    </div>
  </div>
</div>

</body>
</html>