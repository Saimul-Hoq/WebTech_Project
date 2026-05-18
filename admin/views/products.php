<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Products</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .filters { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .filters input, .filters select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .filters button { padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; }
    .btn-red { background: #dc3545; color: white; }
    .badge-yes { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    .badge-no  { background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
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
  <h2>All Products</h2>

  <form method="GET" action="ProductController.php">
    <div class="filters">
      <input type="text" name="search" placeholder="Search product name" value="<?= htmlspecialchars($search) ?>">
      <select name="category_id">
        <option value="">All Categories</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $category_id == $c['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
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
      <th>ID</th>
      <th>Name</th>
      <th>Category</th>
      <th>Seller</th>
      <th>Price</th>
      <th>Stock</th>
      <th>Available</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($products as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['category_name']) ?></td>
      <td><?= htmlspecialchars($p['shop_name']) ?></td>
      <td>$<?= number_format($p['price'], 2) ?></td>
      <td><?= $p['stock_quantity'] ?></td>
      <td>
        <?php if ($p['is_available']): ?>
          <span class="badge-yes">Yes</span>
        <?php else: ?>
          <span class="badge-no">Removed</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($p['is_available']): ?>
          <a class="btn btn-red"
             href="ProductController.php?action=remove&id=<?= $p['id'] ?>"
             onclick="return confirm('Remove this product?')">Remove</a>
        <?php else: ?>
          —
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($products)): ?>
      <tr><td colspan="8" style="text-align:center; color:#999;">No products found.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>