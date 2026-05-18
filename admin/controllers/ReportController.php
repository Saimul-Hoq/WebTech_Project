<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/ReportModel.php';

$model = new ReportModel($conn);
$month = $_GET['month'] ?? date('m');
$year  = $_GET['year']  ?? date('Y');

$report = $model->getMonthlyReport($month, $year);
require_once '../views/report.php';
?>