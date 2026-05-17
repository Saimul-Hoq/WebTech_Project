<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php if (isset($_SESSION['user_id'])): ?>
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">Delivery Manager</span>
    <div>
        <span class="text-white me-3"><?= htmlspecialchars($_SESSION['name']) ?></span>
        <a href="/WebTech_Project/delivery_manager/controllers/AuthController.php?action=logout" class="btn btn-sm btn-danger">Logout</a>
    </div>
</nav>
<?php endif; ?>
<div class="container mt-4">