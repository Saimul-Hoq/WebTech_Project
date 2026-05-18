<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/DashboardModel.php';

$model   = new DashboardModel($conn);
$users   = $model->getTotalUsers();
$sellers = $model->getTotalActiveSellers();
$orders  = $model->getTodayOrders();
$revenue = $model->getMonthlyRevenue();

require_once '../views/dashboard.php';
?>