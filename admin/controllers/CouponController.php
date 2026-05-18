<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/CouponModel.php';

$model  = new CouponModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id  = $_POST['seller_id'];
    $code       = trim($_POST['code']);
    $discount   = $_POST['discount'];
    $max_uses   = $_POST['max_uses'];
    $expires_at = $_POST['expires_at'];
    $model->addCoupon($seller_id, $code, $discount, $max_uses, $expires_at);
    header("Location: CouponController.php");
    exit();
}

if ($action === 'enable' && isset($_GET['id'])) {
    $model->toggleCoupon($_GET['id'], 1);
    header("Location: CouponController.php");
    exit();
}

if ($action === 'disable' && isset($_GET['id'])) {
    $model->toggleCoupon($_GET['id'], 0);
    header("Location: CouponController.php");
    exit();
}

$coupons = $model->getAllCoupons();
$sellers = $model->getAllSellers();
require_once '../views/coupons.php';
?>