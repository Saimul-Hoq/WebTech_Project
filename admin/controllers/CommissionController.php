<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/CommissionModel.php';

$model  = new CommissionModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_POST['seller_id'];
    $rate      = $_POST['rate'];
    $model->updateCommission($seller_id, $rate);
    header("Location: CommissionController.php");
    exit();
}

$sellers = $model->getAllSellersWithCommission();
require_once '../views/commission.php';
?>