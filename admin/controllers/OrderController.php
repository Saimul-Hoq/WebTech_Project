<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/OrderModel.php';

$model  = new OrderModel($conn);
$action = $_GET['action'] ?? '';

$status    = $_GET['status']    ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to']   ?? '';
$seller_id = $_GET['seller_id'] ?? '';
$user_id   = $_GET['user_id']   ?? '';

if ($action === 'view' && isset($_GET['id'])) {
    $order_id   = $_GET['id'];
    $order_items = $model->getOrderItems($order_id);
    require_once '../views/order_detail.php';
    exit();
}

$orders  = $model->getAllOrders($status, $date_from, $date_to, $seller_id, $user_id);
$sellers = $model->getAllSellers();

require_once '../views/orders.php';
?>