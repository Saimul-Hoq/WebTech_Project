<?php require_once '../views/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Monthly Report</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; margin: 0; }
    .topbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
    .topbar a { color: white; text-decoration: none; }
    .nav { background: #444; padding: 10px 20px; display: flex; gap: 15px; flex-wrap: wrap; }
    .nav a { color: white; text-decoration: none; font-size: 14px; }
    .container { padding: 30px; }
    .filters { display: flex; gap: 10px; margin-bottom: 20px; align-items: center; }
    .filters select, .filters input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .filters button { padding: 8px 16px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .print-btn { padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .section h3 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .cards { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px; }
    .card { background: white; padding: 20px; border-radius: 8px; min-width: 180px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .card h4 { margin: 0 0 8px; font-size: 13px; color: #666; }
    .card p { margin: 0; font-size: 24px; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
    th { background: #f8f8f8; }
    @media print {
      .topbar, .nav, .filters, .print-btn { display: none; }
      body { background: white; }
    }
  </style>
</head>
<body>

<?php require_once '../views/nav.php'; ?>

<div class="container">
  <h2>Monthly Report</h2>

  <form method="GET" action="ReportController.php">
    <div class="filters">
      <select name="month">
        <?php for ($m = 1; $m <= 12; $m++): ?>
          <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>>
            <?= date('F', mktime(0,0,0,$m,1)) ?>
          </option>
        <?php endfor; ?>
      </select>
      <input type="number" name="year" value="<?= $year ?>" min="2000" max="2099" style="width:80px;">
      <button type="submit">Generate</button>
      <button type="button" class="print-btn" onclick="window.print()">Print / Export</button>
    </div>
  </form>

  <!-- Summary -->
  <div class="cards">
    <div class="card">
      <h4>Total Orders</h4>
      <p><?= $report['orders']['total'] ?? 0 ?></p>
    </div>
    <div class="card">
      <h4>Total Revenue</h4>
      <p>$<?= number_format($report['orders']['revenue'] ?? 0, 2) ?></p>
    </div>
  </div>

  <!-- New Users -->
  <div class="section">
    <h3>New Users This Month</h3>
    <table>
      <tr><th>Role</th><th>Count</th></tr>
      <?php foreach ($report['users'] as $u): ?>
      <tr>
        <td><?= ucfirst($u['role']) ?></td>
        <td><?= $u['total'] ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($report['users'])): ?>
        <tr><td colspan="2" style="color:#999;">No data.</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <!-- Top Sellers -->
  <div class="section">
    <h3>Top Sellers</h3>
    <table>
      <tr><th>Shop</th><th>Sales</th></tr>
      <?php foreach ($report['top_sellers'] as $s): ?>
      <tr>
        <td><?= htmlspecialchars($s['shop_name']) ?></td>
        <td>$<?= number_format($s['total_sales'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($report['top_sellers'])): ?>
        <tr><td colspan="2" style="color:#999;">No data.</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <!-- Top Categories -->
  <div class="section">
    <h3>Top Categories</h3>
    <table>
      <tr><th>Category</th><th>Sales</th></tr>
      <?php foreach ($report['top_categories'] as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['category_name']) ?></td>
        <td>$<?= number_format($c['total_sales'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($report['top_categories'])): ?>
        <tr><td colspan="2" style="color:#999;">No data.</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <!-- Disputes -->
  <div class="section">
    <h3>Disputes</h3>
    <table>
      <tr><th>Status</th><th>Total</th></tr>
      <?php foreach ($report['disputes'] as $d): ?>
      <tr>
        <td><?= ucfirst($d['status']) ?></td>
        <td><?= $d['total'] ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($report['disputes'])): ?>
        <tr><td colspan="2" style="color:#999;">No data.</td></tr>
      <?php endif; ?>
    </table>
  </div>

</div>
</body>
</html>