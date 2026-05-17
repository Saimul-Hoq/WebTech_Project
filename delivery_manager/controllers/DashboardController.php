<?php
require_once __DIR__ . '/../config/auth_guard.php';
requireDeliveryManager();
require_once __DIR__ . '/../views/layouts/header.php';
?>

<h2>Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></p>

<?php require_once __DIR__ . '/../views/layouts/footer.php'; ?>