<?php
require_once __DIR__ . '/../config/auth_guard.php';
requireDeliveryManager();
require_once __DIR__ . '/../models/AgentModel.php';

$model  = new AgentModel();
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $agents = $model->getAllAgents();
    require_once __DIR__ . '/../views/agents/list.php';

} elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = trim($_POST['name']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $vehicle_type = trim($_POST['vehicle_type']);
    $model->createAgent($name, $email, $phone, $vehicle_type);
    header("Location: /WebTech_Project/delivery_manager/controllers/AgentController.php?action=list&success=Agent+added");
    exit;

} elseif ($action === 'add') {
    require_once __DIR__ . '/../views/agents/form.php';

} elseif ($action === 'edit') {
    $id    = (int)$_GET['id'];
    $agent = $model->getAgentById($id);
    require_once __DIR__ . '/../views/agents/form.php';

} elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = (int)$_POST['id'];
    $phone        = trim($_POST['phone']);
    $vehicle_type = trim($_POST['vehicle_type']);
    $model->updateAgent($id, $phone, $vehicle_type);
    header("Location: /WebTech_Project/delivery_manager/controllers/AgentController.php?action=list&success=Agent+updated");
    exit;

} elseif ($action === 'toggle') {
    // AJAX endpoint
    header('Content-Type: application/json');
    $id     = (int)$_POST['id'];
    $status = (int)$_POST['status'];
    $model->toggleStatus($id, $status);
    echo json_encode(['success' => true, 'new_status' => $status]);
    exit;
}