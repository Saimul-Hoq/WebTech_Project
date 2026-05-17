<?php
require_once __DIR__ . '/../config/auth_guard.php';
requireDeliveryManager();
require_once __DIR__ . '/../models/AssignmentModel.php';
require_once __DIR__ . '/../models/AgentModel.php';

$model      = new AssignmentModel();
$agentModel = new AgentModel();
$action     = $_GET['action'] ?? 'active';

if ($action === 'active') {
    $deliveries = $model->getActiveDeliveries();
    require_once __DIR__ . '/../views/deliveries/active.php';

} elseif ($action === 'history') {
    $history = $model->getDeliveryHistory();
    require_once __DIR__ . '/../views/deliveries/history.php';

} elseif ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // AJAX endpoint
    header('Content-Type: application/json');
    $id     = (int)$_POST['id'];
    $status = trim($_POST['status']);

    $allowed = ['assigned', 'picked_up', 'in_transit', 'delivered', 'failed'];
    if (!in_array($status, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }

    $model->updateStatus($id, $status);
    echo json_encode(['success' => true, 'message' => 'Status updated']);
    exit;

} elseif ($action === 'reassign' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // AJAX endpoint
    header('Content-Type: application/json');
    $id       = (int)$_POST['id'];
    $agent_id = (int)$_POST['agent_id'];
    $model->reassignAgent($id, $agent_id);
    echo json_encode(['success' => true, 'message' => 'Agent reassigned']);
    exit;
}