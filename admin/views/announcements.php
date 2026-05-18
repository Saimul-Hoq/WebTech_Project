<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Announcements</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; flex-wrap: wrap; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .form-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; max-width: 550px; }
    .form-box input, .form-box textarea { width: 100%; padding: 8px; margin: 6px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
    .form-box button { padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn-red { background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; }
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
  <a href="../controllers/AnnouncementController.php">Announcements</a>
</div>

<div class="container">
  <h2>Platform Announcements</h2>

  <div class="form-box">
    <h3>Post Announcement</h3>
    <form method="POST" action="AnnouncementController.php?action=add">
      <input type="text" name="title" placeholder="Title" required>
      <textarea name="message" rows="4" placeholder="Message..." required></textarea>
      <button type="submit">Post</button>
    </form>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Message</th>
      <th>Posted By</th>
      <th>Date</th>
      <th>Action</th>
    </tr>
    <?php foreach ($announcements as $a): ?>
    <tr>
      <td>#<?= $a['id'] ?></td>
      <td><?= htmlspecialchars($a['title']) ?></td>
      <td><?= htmlspecialchars(substr($a['message'], 0, 60)) ?>...</td>
      <td><?= htmlspecialchars($a['admin_name']) ?></td>
      <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
      <td>
        <a class="btn-red"
           href="AnnouncementController.php?action=delete&id=<?= $a['id'] ?>"
           onclick="return confirm('Delete this announcement?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($announcements)): ?>
      <tr><td colspan="6" style="text-align:center; color:#999;">No announcements.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>