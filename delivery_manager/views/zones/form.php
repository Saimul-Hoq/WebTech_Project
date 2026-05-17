<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php $editing = isset($zone); ?>

<h2><?= $editing ? 'Edit Zone' : 'Add New Zone' ?></h2>

<form action="/WebTech_Project/delivery_manager/controllers/ZoneController.php?action=<?= $editing ? 'update' : 'create' ?>" method="POST">
    <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= $zone['id'] ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label>Zone Name</label>
        <input type="text" name="zone_name" class="form-control"
            value="<?= $editing ? htmlspecialchars($zone['zone_name']) : '' ?>" required>
    </div>

    <div class="mb-3">
        <label>Delivery Fee (Tk)</label>
        <input type="number" step="0.01" name="delivery_fee" class="form-control"
            value="<?= $editing ? $zone['delivery_fee'] : '' ?>" required>
    </div>

    <div class="mb-3">
        <label>Estimated Delivery Days</label>
        <input type="number" name="estimated_days" class="form-control"
            value="<?= $editing ? $zone['estimated_days'] : '' ?>" required>
    </div>

    <button type="submit" class="btn btn-primary"><?= $editing ? 'Update Zone' : 'Add Zone' ?></button>
    <a href="/WebTech_Project/delivery_manager/controllers/ZoneController.php?action=list" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>