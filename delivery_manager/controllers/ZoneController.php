<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/auth_guard.php';
requireDeliveryManager();
if (!class_exists('ZoneModel')) require_once __DIR__ . '/../models/ZoneModel.php';

$model  = new ZoneModel();
$action = $_GET['action'] ?? 'list';


if ($action === 'list') {
    $zones = $model->getAllZones();
    require_once __DIR__ . '/../views/zones/list.php';

/*if ($action === 'list') {
    $zones = $model->getAllZones();
    echo "Before header";
    require_once __DIR__ . '/../views/layouts/header.php';
    echo "After header";
    exit;*/

} elseif ($action === 'add') {
    require_once __DIR__ . '/../views/zones/form.php';

} elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $zone_name     = trim($_POST['zone_name']);
    $delivery_fee  = (float)$_POST['delivery_fee'];
    $estimated_days = (int)$_POST['estimated_days'];
    $model->createZone($zone_name, $delivery_fee, $estimated_days);
    header("Location: /WebTech_Project/delivery_manager/controllers/ZoneController.php?action=list&success=Zone+added");
    exit;

} elseif ($action === 'edit') {
    $id   = (int)$_GET['id'];
    $zone = $model->getZoneById($id);
    require_once __DIR__ . '/../views/zones/form.php';

} elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id             = (int)$_POST['id'];
    $zone_name      = trim($_POST['zone_name']);
    $delivery_fee   = (float)$_POST['delivery_fee'];
    $estimated_days = (int)$_POST['estimated_days'];
    $model->updateZone($id, $zone_name, $delivery_fee, $estimated_days);
    header("Location: /WebTech_Project/delivery_manager/controllers/ZoneController.php?action=list&success=Zone+updated");
    exit;

} elseif ($action === 'delete') {
    // AJAX endpoint
    header('Content-Type: application/json');
    $id = (int)$_POST['id'];
    $model->deleteZone($id);
    echo json_encode(['success' => true]);
    exit;
}