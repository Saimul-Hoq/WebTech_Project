<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Agent Performance Report</h2>
    <div>
        <a href="/WebTech_Project/delivery_manager/controllers/ReportController.php?action=zones" class="btn btn-outline-secondary btn-sm">Zone Report</a>
        <a href="/WebTech_Project/delivery_manager/controllers/ReportController.php?action=summary" class="btn btn-outline-secondary btn-sm">Summary</a>
        <a href="/WebTech_Project/delivery_manager/controllers/DashboardController.php" class="btn btn-outline-primary btn-sm">Dashboard</a>
    </div>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Agent Name</th>
            <th>Vehicle</th>
            <th>Total Assigned</th>
            <th>Completed</th>
            <th>Failed</th>
            <th>Success Rate</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agents as $a): ?>
        <?php $rate = $a['total'] > 0 ? round(($a['completed'] / $a['total']) * 100) : 0; ?>
        <tr>
            <td><?= htmlspecialchars($a['agent_name']) ?></td>
            <td><?= htmlspecialchars($a['vehicle_type']) ?></td>
            <td><?= $a['total'] ?></td>
            <td><span class="badge bg-success"><?= $a['completed'] ?></span></td>
            <td><span class="badge bg-danger"><?= $a['failed'] ?></span></td>
            <td>
                <div class="progress" style="height:20px">
                    <div class="progress-bar bg-success" style="width:<?= $rate ?>%"><?= $rate ?>%</div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>