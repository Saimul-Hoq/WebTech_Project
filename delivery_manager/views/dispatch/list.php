<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h2 class="mb-3">Orders Ready for Dispatch</h2>

<?php if (empty($orders)): ?>
    <div class="alert alert-info">No orders pending dispatch.</div>
<?php else: ?>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Shipping Address</th>
            <th>Total (Tk)</th>
            <th>Order Date</th>
            <th>Assign Agent</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
        <tr id="order-row-<?= $order['id'] ?>">
            <td><?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td><?= htmlspecialchars($order['shipping_address']) ?></td>
            <td><?= number_format($order['total_amount'], 2) ?></td>
            <td><?= $order['created_at'] ?></td>
            <td>
                <select class="form-select form-select-sm agent-select mb-1" id="agent-<?= $order['id'] ?>">
                    <option value="">-- Select Agent --</option>
                    <?php foreach ($agents as $agent): ?>
                        <option value="<?= $agent['id'] ?>"><?= htmlspecialchars($agent['name']) ?> (<?= $agent['vehicle_type'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <input type="text" class="form-control form-control-sm zone-input mb-1"
                    id="zone-<?= $order['id'] ?>" placeholder="Delivery zone">
                <button class="btn btn-sm btn-success assign-btn" data-order="<?= $order['id'] ?>">Assign</button>
                <span class="text-success ms-2 assign-msg" id="msg-<?= $order['id'] ?>"></span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<script>
document.querySelectorAll('.assign-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const orderId = this.dataset.order;
        const agentId = document.getElementById('agent-' + orderId).value;
        const zone    = document.getElementById('zone-' + orderId).value.trim();

        if (!agentId || !zone) {
            alert('Select agent and enter zone.');
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/WebTech_Project/delivery_manager/controllers/DispatchController.php?action=assign');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            const res = JSON.parse(xhr.responseText);
            if (res.success) {
                document.getElementById('msg-' + orderId).textContent = '✓ Assigned';
                document.getElementById('order-row-' + orderId).style.opacity = '0.4';
            } else {
                alert(res.message);
            }
        };
        xhr.send('order_id=' + orderId + '&agent_id=' + agentId + '&delivery_zone=' + encodeURIComponent(zone));
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>