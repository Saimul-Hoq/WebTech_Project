<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php $editing = isset($agent); ?>

<h2><?= $editing ? 'Edit Agent' : 'Add New Agent' ?></h2>

<form action="/WebTech_Project/delivery_manager/controllers/AgentController.php?action=<?= $editing ? 'update' : 'create' ?>" method="POST">
    <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= $agent['id'] ?>">
    <?php endif; ?>

    <?php if (!$editing): ?>
    <div class="mb-3">
        <label>Full Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <?php endif; ?>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control"
            value="<?= $editing ? htmlspecialchars($agent['phone']) : '' ?>" required>
    </div>

    <div class="mb-3">
        <label>Vehicle Type</label>
        <select name="vehicle_type" class="form-select" required>
            <option value="">-- Select --</option>
            <?php foreach (['Bike', 'Bicycle', 'Car', 'Van', 'Truck'] as $v): ?>
                <option value="<?= $v ?>" <?= ($editing && $agent['vehicle_type'] === $v) ? 'selected' : '' ?>>
                    <?= $v ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary"><?= $editing ? 'Update Agent' : 'Add Agent' ?></button>
    <a href="/WebTech_Project/delivery_manager/controllers/AgentController.php?action=list" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>