<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Delivery History</h2>
    <a href="/WebTech_Project/delivery_manager/controllers/DeliveryController.php?action=active" class="btn btn-secondary">Active Deliveries</a>
</div>

<?php if (empty($history)): ?>
    <div class="alert alert-info">No delivery history yet.</div>
<?php else: ?>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Assignment #</th>
            <th>Order #</th>
            <th>Customer</th>
            <th>Zone</th>
            <th>Agent</th>
            <th>Status</th>
            <th>Assigned At</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($history as $h): ?>
        <tr>
            <td><?= $h['id'] ?></td>
            <td><?= $h['order_id'] ?></td>
            <td><?= htmlspecialchars($h['customer_name']) ?></td>
            <td><?= htmlspecialchars($h['delivery_zone']) ?></td>
            <td><?= htmlspecialchars($h['agent_name']) ?></td>
            <td>
                <span class="badge <?= $h['status'] === 'delivered' ? 'bg-success' : 'bg-danger' ?>">
                    <?= ucfirst($h['status']) ?>
                </span>
            </td>
            <td><?= $h['assigned_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>