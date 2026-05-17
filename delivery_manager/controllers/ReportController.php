<?php
require_once __DIR__ . '/../config/auth_guard.php';
requireDeliveryManager();
require_once __DIR__ . '/../models/ReportModel.php';

$model  = new ReportModel();
$action = $_GET['action'] ?? 'agents';

if ($action === 'agents') {
    $agents = $model->getAgentPerformance();
    require_once __DIR__ . '/../views/reports/agents.php';

} elseif ($action === 'zones') {
    $zones = $model->getZonePerformance();
    require_once __DIR__ . '/../views/reports/zones.php';

} elseif ($action === 'summary') {
    $summary = $model->getDailySummary();
    require_once __DIR__ . '/../views/reports/summary.php';
}