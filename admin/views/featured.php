<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Featured Products</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; flex-wrap: wrap; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; }
    .btn-yellow { background: #ffc107; color: #333; }
    .btn-gray   { background: #6c757d; color: white; }
    .badge-yes { background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    .badge-no  { background: #f0f0f0; color: #999; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
  </style>
</head>
<body>

<?php require_once '../views/nav.php'; ?>

<div class="container">
  <h2>Featured Products</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Product</th>
      <th>Category</th>
      <th>Shop</th>
      <th>Price</th>
      <th>Featured</th>
      <th>Action</th>
    </tr>
    <?php foreach ($products as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['category_name']) ?></td>
      <td><?= htmlspecialchars($p['shop_name']) ?></td>
      <td>$<?= number_format($p['price'], 2) ?></td>
      <td>
        <?php if ($p['is_featured']): ?>
          <span class="badge-yes">⭐ Featured</span>
        <?php else: ?>
          <span class="badge-no">No</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($p['is_featured']): ?>
          <a class="btn btn-gray" href="FeaturedController.php?action=unfeature&id=<?= $p['id'] ?>">Remove</a>
        <?php else: ?>
          <a class="btn btn-yellow" href="FeaturedController.php?action=feature&id=<?= $p['id'] ?>">Feature</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($products)): ?>
      <tr><td colspan="7" style="text-align:center; color:#999;">No products found.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>