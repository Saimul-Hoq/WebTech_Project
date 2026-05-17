<?php
require_once __DIR__ . '/../config/auth_guard.php';
requireDeliveryManager();
require_once __DIR__ . '/../models/ReportModel.php';

$model = new ReportModel();
$stats = $model->getDashboardStats();

require_once __DIR__ . '/../views/dashboard.php';