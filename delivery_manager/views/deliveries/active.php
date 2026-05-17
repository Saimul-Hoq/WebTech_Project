<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Active Deliveries</h2>
    <a href="/WebTech_Project/delivery_manager/controllers/DeliveryController.php?action=history" class="btn btn-secondary">View History</a>
</div>

<?php if (empty($deliveries)): ?>
    <div class="alert alert-info">No active deliveries.</div>
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
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($deliveries as $d): ?>
        <tr id="delivery-row-<?= $d['id'] ?>">
            <td><?= $d['id'] ?></td>
            <td><?= $d['order_id'] ?></td>
            <td><?= htmlspecialchars($d['customer_name']) ?></td>
            <td><?= htmlspecialchars($d['delivery_zone']) ?></td>
            <td><?= htmlspecialchars($d['agent_name']) ?></td>
            <td>
                <span class="badge bg-primary status-badge-<?= $d['id'] ?>">
                    <?= ucfirst(str_replace('_', ' ', $d['status'])) ?>
                </span>
            </td>
            <td><?= $d['assigned_at'] ?></td>
            <td>
                <select class="form-select form-select-sm mb-1 status-select" id="status-<?= $d['id'] ?>">
                    <option value="assigned"    <?= $d['status']==='assigned'    ? 'selected':'' ?>>Assigned</option>
                    <option value="picked_up"   <?= $d['status']==='picked_up'   ? 'selected':'' ?>>Picked Up</option>
                    <option value="in_transit"  <?= $d['status']==='in_transit'  ? 'selected':'' ?>>In Transit</option>
                    <option value="delivered"   <?= $d['status']==='delivered'   ? 'selected':'' ?>>Delivered</option>
                    <option value="failed"      <?= $d['status']==='failed'      ? 'selected':'' ?>>Failed</option>
                </select>
                <button class="btn btn-sm btn-primary update-btn" data-id="<?= $d['id'] ?>">Update</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<script>
document.querySelectorAll('.update-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id     = this.dataset.id;
        const status = document.getElementById('status-' + id).value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/WebTech_Project/delivery_manager/controllers/DeliveryController.php?action=update_status');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            const res = JSON.parse(xhr.responseText);
            if (res.success) {
                document.querySelector('.status-badge-' + id).textContent =
                    status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                if (status === 'delivered' || status === 'failed') {
                    document.getElementById('delivery-row-' + id).style.opacity = '0.4';
                }
            } else {
                alert(res.message);
            }
        };
        xhr.send('id=' + id + '&status=' + status);
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>