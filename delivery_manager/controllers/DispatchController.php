<?php
require_once __DIR__ . '/../config/auth_guard.php';
requireDeliveryManager();
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/AgentModel.php';
require_once __DIR__ . '/../models/AssignmentModel.php';

$orderModel      = new OrderModel();
$agentModel      = new AgentModel();
$assignmentModel = new AssignmentModel();
$action          = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $orders = $orderModel->getReadyToDispatchOrders();
    $agents = $agentModel->getAvailableAgents();
    require_once __DIR__ . '/../views/dispatch/list.php';

} elseif ($action === 'assign') {
    // AJAX endpoint
    header('Content-Type: application/json');
    $order_id      = (int)$_POST['order_id'];
    $agent_id      = (int)$_POST['agent_id'];
    $delivery_zone = trim($_POST['delivery_zone']);

    if (!$order_id || !$agent_id || !$delivery_zone) {
        echo json_encode(['success' => false, 'message' => 'Missing fields']);
        exit;
    }

    $assignmentModel->assignAgent($order_id, $agent_id, $delivery_zone);
    echo json_encode(['success' => true, 'message' => 'Agent assigned successfully']);
    exit;
}