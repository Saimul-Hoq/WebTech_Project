<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Delivery Zones</h2>
    <a href="/WebTech_Project/delivery_manager/controllers/ZoneController.php?action=add" class="btn btn-primary">+ Add Zone</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Zone Name</th>
            <th>Delivery Fee (Tk)</th>
            <th>Estimated Days</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($zones as $zone): ?>
        <tr id="row-<?= $zone['id'] ?>">
            <td><?= $zone['id'] ?></td>
            <td><?= htmlspecialchars($zone['zone_name']) ?></td>
            <td><?= number_format($zone['delivery_fee'], 2) ?></td>
            <td><?= $zone['estimated_days'] ?> days</td>
            <td>
                <a href="/WebTech_Project/delivery_manager/controllers/ZoneController.php?action=edit&id=<?= $zone['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $zone['id'] ?>">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        if (!confirm('Delete this zone?')) return;
        const id = this.dataset.id;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/WebTech_Project/delivery_manager/controllers/ZoneController.php?action=delete');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            const res = JSON.parse(xhr.responseText);
            if (res.success) {
                document.getElementById('row-' + id).remove();
            }
        };
        xhr.send('id=' + id);
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>