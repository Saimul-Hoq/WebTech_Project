<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';

$id   = $_GET['id'] ?? 0;
$stmt = $conn->prepare(
    "SELECT d.*, 
     u.name as customer_name, u.email as customer_email,
     s.shop_name,
     o.total_amount, o.created_at as order_date
     FROM disputes d
     JOIN users u ON d.user_id = u.id
     JOIN sellers s ON d.seller_id = s.id
     JOIN orders o ON d.order_id = o.id
     WHERE d.id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$dispute = $stmt->get_result()->fetch_assoc();

if (!$dispute) die("Dispute not found.");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dispute Detail</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .container { padding: 30px; max-width: 700px; }
    .card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .card p { margin: 8px 0; font-size: 14px; }
    label { font-weight: bold; }
    textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .btn-green { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    .back { display: inline-block; margin-bottom: 15px; padding: 8px 16px; background: #333; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; }
    .resolved-note { background: #d4edda; padding: 10px; border-radius: 4px; color: #155724; font-size: 14px; }
  </style>
</head>
<body>

<div class="topbar">
  <span>Admin Panel</span>
  <a href="../controllers/AuthController.php?action=logout">Logout</a>
</div>

<div class="container">
  <a class="back" href="../controllers/DisputeController.php">← Back to Disputes</a>
  <h2>Dispute #<?= $dispute['id'] ?></h2>

  <div class="card">
    <p><label>Customer:</label> <?= htmlspecialchars($dispute['customer_name']) ?> (<?= htmlspecialchars($dispute['customer_email']) ?>)</p>
    <p><label>Shop:</label> <?= htmlspecialchars($dispute['shop_name']) ?></p>
    <p><label>Order ID:</label> #<?= $dispute['order_id'] ?></p>
    <p><label>Order Total:</label> $<?= number_format($dispute['total_amount'], 2) ?></p>
    <p><label>Status:</label> <?= ucfirst($dispute['status']) ?></p>
    <p><label>Submitted:</label> <?= date('d M Y', strtotime($dispute['created_at'])) ?></p>
    <p><label>Description:</label><br><?= nl2br(htmlspecialchars($dispute['description'])) ?></p>
  </div>

  <?php if ($dispute['status'] === 'resolved'): ?>
    <div class="resolved-note">
      <strong>Resolution Note:</strong><br>
      <?= nl2br(htmlspecialchars($dispute['admin_note'])) ?>
    </div>
  <?php else: ?>
    <div class="card">
      <h3>Resolve Dispute</h3>
      <form method="POST" action="../controllers/DisputeController.php?action=resolve">
        <input type="hidden" name="id" value="<?= $dispute['id'] ?>">
        <textarea name="admin_note" rows="4" placeholder="Write resolution note..." required></textarea>
        <br><br>
        <button type="submit" class="btn-green">Mark as Resolved</button>
      </form>
    </div>
  <?php endif; ?>
</div>

</body>
</html>