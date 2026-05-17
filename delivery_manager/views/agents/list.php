<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Delivery Agents</h2>
    <a href="/WebTech_Project/delivery_manager/controllers/AgentController.php?action=add" class="btn btn-primary">+ Add Agent</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Vehicle</th>
            <th>Active Deliveries</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agents as $agent): ?>
        <tr>
            <td><?= $agent['id'] ?></td>
            <td><?= htmlspecialchars($agent['name']) ?></td>
            <td><?= htmlspecialchars($agent['email']) ?></td>
            <td><?= htmlspecialchars($agent['phone']) ?></td>
            <td><?= htmlspecialchars($agent['vehicle_type']) ?></td>
            <td><?= $agent['active_deliveries'] ?></td>
            <td>
                <span class="badge <?= $agent['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                    <?= $agent['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </td>
            <td>
                <a href="/WebTech_Project/delivery_manager/controllers/AgentController.php?action=edit&id=<?= $agent['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <button
                    class="btn btn-sm <?= $agent['is_active'] ? 'btn-danger' : 'btn-success' ?> toggle-btn"
                    data-id="<?= $agent['id'] ?>"
                    data-status="<?= $agent['is_active'] ? 0 : 1 ?>">
                    <?= $agent['is_active'] ? 'Deactivate' : 'Activate' ?>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id     = this.dataset.id;
        const status = this.dataset.status;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/WebTech_Project/delivery_manager/controllers/AgentController.php?action=toggle');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload();
            }
        };
        xhr.send('id=' + id + '&status=' + status);
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>