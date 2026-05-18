<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/FeaturedModel.php';

$model  = new FeaturedModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'feature' && isset($_GET['id'])) {
    $model->toggleFeatured($_GET['id'], 1);
    header("Location: FeaturedController.php");
    exit();
}

if ($action === 'unfeature' && isset($_GET['id'])) {
    $model->toggleFeatured($_GET['id'], 0);
    header("Location: FeaturedController.php");
    exit();
}

$products = $model->getAllProducts();
require_once '../views/featured.php';
?>