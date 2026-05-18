<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/AnalyticsModel.php';

$model          = new AnalyticsModel($conn);
$gmv            = $model->getGMV();
$commission     = $model->getTotalCommission();
$top_sellers    = $model->getTopSellers();
$top_categories = $model->getTopCategories();
$monthly        = $model->getMonthlyRevenue();
$delivery       = $model->getDeliveryOverview();

require_once '../views/analytics.php';
?>