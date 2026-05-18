<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/SellerModel.php';

$model  = new SellerModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'approve' && isset($_GET['id'])) {
    $model->updateSellerStatus($_GET['id'], 'approved');
    header("Location: SellerController.php");
    exit();
}

if ($action === 'reject' && isset($_GET['id'])) {
    $model->updateSellerStatus($_GET['id'], 'rejected');
    header("Location: SellerController.php");
    exit();
}

if ($action === 'suspend' && isset($_GET['id'], $_GET['user_id'])) {
    $model->toggleUserActive($_GET['user_id'], 0);
    header("Location: SellerController.php");
    exit();
}

if ($action === 'reactivate' && isset($_GET['id'], $_GET['user_id'])) {
    $model->toggleUserActive($_GET['user_id'], 1);
    header("Location: SellerController.php");
    exit();
}

$sellers = $model->getAllSellers();
require_once '../views/sellers.php';
?>