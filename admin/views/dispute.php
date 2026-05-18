<?php require_once '../views/auth_check.php'; 
if (!isset($disputes)) $disputes = [];
if (!isset($status)) $status = '';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Disputes</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; flex-wrap: wrap; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .filters { display: flex; gap: 10px; margin-bottom: 20px; }
    .filters select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .filters button { padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #333; color: white; }
    tr:hover { background: #f9f9f9; }
    .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none; }
    .btn-blue { background: #007bff; color: white; }
    .btn-green { background: #28a745; color: white; }
    .badge-open { background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    .badge-resolved { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-size: 12px; }

    /* Modal */
    .modal-bg { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; }
    .modal { background:white; width:450px; margin:100px auto; padding:25px; border-radius:8px; }
    .modal h3 { margin-top:0; }
    .modal textarea { width:100%; padding:10px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box; }
    .modal-btns { margin-top:15px; display:flex; gap:10px; }
    .modal-btns button { padding:8px 16px; border:none; border-radius:4px; cursor:pointer; }
    .btn-cancel { background:#ccc; }
    #ajax-msg { margin-top:10px; font-size:14px; }
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
  <h2>Customer Disputes</h2>

  <form method="GET" action="DisputeController.php">
    <div class="filters">
      <select name="status">
        <option value="">All</option>
        <option value="open"     <?= $status === 'open'     ? 'selected' : '' ?>>Open</option>
        <option value="resolved" <?= $status === 'resolved' ? 'selected' : '' ?>>Resolved</option>
      </select>
      <button type="submit">Filter</button>
    </div>
  </form>

  <table>
    <tr>
      <th>ID</th>
      <th>Customer</th>
      <th>Shop</th>
      <th>Order ID</th>
      <th>Status</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($disputes as $d): ?>
    <tr id="row-<?= $d['id'] ?>">
      <td>#<?= $d['id'] ?></td>
      <td><?= htmlspecialchars($d['customer_name']) ?></td>
      <td><?= htmlspecialchars($d['shop_name']) ?></td>
      <td>#<?= $d['order_id'] ?></td>
      <td>
        <span class="badge-<?= $d['status'] ?>" id="badge-<?= $d['id'] ?>">
          <?= ucfirst($d['status']) ?>
        </span>
      </td>
      <td><?= date('d M Y', strtotime($d['created_at'])) ?></td>
      <td>
        <a class="btn btn-blue" href="../views/dispute.php?id=<?= $d['id'] ?>">View</a>
        <?php if ($d['status'] === 'open'): ?>
          <button class="btn btn-green" onclick="openModal(<?= $d['id'] ?>)">Resolve</button>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($disputes)): ?>
      <tr><td colspan="7" style="text-align:center; color:#999;">No disputes found.</td></tr>
    <?php endif; ?>
  </table>
</div>

<!-- AJAX Modal -->
<div class="modal-bg" id="modal-bg">
  <div class="modal">
    <h3>Resolve Dispute</h3>
    <input type="hidden" id="modal-dispute-id">
    <textarea id="modal-note" rows="4" placeholder="Write resolution note..."></textarea>
    <div id="ajax-msg"></div>
    <div class="modal-btns">
      <button class="btn-green" onclick="submitResolve()">Submit</button>
      <button class="btn-cancel" onclick="closeModal()">Cancel</button>
    </div>
  </div>
</div>

<script>
function openModal(id) {
    document.getElementById('modal-dispute-id').value = id;
    document.getElementById('modal-note').value = '';
    document.getElementById('ajax-msg').innerText = '';
    document.getElementById('modal-bg').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal-bg').style.display = 'none';
}

function submitResolve() {
    var id   = document.getElementById('modal-dispute-id').value;
    var note = document.getElementById('modal-note').value.trim();

    if (!note) {
        document.getElementById('ajax-msg').innerText = 'Note is required.';
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../api/dispute_ajax.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                // Update badge without page reload
                document.getElementById('badge-' + id).className   = 'badge-resolved';
                document.getElementById('badge-' + id).innerText   = 'Resolved';
                document.getElementById('ajax-msg').style.color    = 'green';
                document.getElementById('ajax-msg').innerText      = 'Resolved successfully!';
                setTimeout(closeModal, 1000);
            } else {
                document.getElementById('ajax-msg').style.color  = 'red';
                document.getElementById('ajax-msg').innerText    = res.message;
            }
        }
    };

    xhr.send('action=resolve&id=' + id + '&admin_note=' + encodeURIComponent(note));
}
</script>

</body>
</html>