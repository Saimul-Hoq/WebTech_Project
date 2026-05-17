<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Zone Performance Report</h2>
    <div>
        <a href="/WebTech_Project/delivery_manager/controllers/ReportController.php?action=agents" class="btn btn-outline-secondary btn-sm">Agent Report</a>
        <a href="/WebTech_Project/delivery_manager/controllers/ReportController.php?action=summary" class="btn btn-outline-secondary btn-sm">Summary</a>
        <a href="/WebTech_Project/delivery_manager/controllers/DashboardController.php" class="btn btn-outline-primary btn-sm">Dashboard</a>
    </div>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Zone</th>
            <th>Total Deliveries</th>
            <th>Delivered</th>
            <th>Failed</th>
            <th>Success Rate</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($zones as $z): ?>
        <?php $rate = $z['total_deliveries'] > 0 ? round(($z['delivered'] / $z['total_deliveries']) * 100) : 0; ?>
        <tr>
            <td><?= htmlspecialchars($z['delivery_zone']) ?></td>
            <td><?= $z['total_deliveries'] ?></td>
            <td><span class="badge bg-success"><?= $z['delivered'] ?></span></td>
            <td><span class="badge bg-danger"><?= $z['failed'] ?></span></td>
            <td>
                <div class="progress" style="height:20px">
                    <div class="progress-bar bg-info" style="width:<?= $rate ?>%"><?= $rate ?>%</div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>