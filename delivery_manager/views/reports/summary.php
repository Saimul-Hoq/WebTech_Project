<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daily & Weekly Summary</h2>
    <div>
        <a href="/WebTech_Project/delivery_manager/controllers/ReportController.php?action=agents" class="btn btn-outline-secondary btn-sm">Agent Report</a>
        <a href="/WebTech_Project/delivery_manager/controllers/ReportController.php?action=zones" class="btn btn-outline-secondary btn-sm">Zone Report</a>
        <a href="/WebTech_Project/delivery_manager/controllers/DashboardController.php" class="btn btn-outline-primary btn-sm">Dashboard</a>
    </div>
</div>

<p class="text-muted">Showing last 7 days.</p>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Date</th>
            <th>Total</th>
            <th>Delivered</th>
            <th>Failed</th>
            <th>In Transit</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($summary as $s): ?>
        <tr>
            <td><?= $s['date'] ?></td>
            <td><?= $s['total'] ?></td>
            <td><span class="badge bg-success"><?= $s['delivered'] ?></span></td>
            <td><span class="badge bg-danger"><?= $s['failed'] ?></span></td>
            <td><span class="badge bg-primary"><?= $s['in_transit'] ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($summary)): ?>
        <tr><td colspan="5" class="text-center">No data yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>