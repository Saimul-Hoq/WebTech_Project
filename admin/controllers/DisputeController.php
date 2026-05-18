<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/DisputeModel.php';

$model  = new DisputeModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'resolve' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = $_POST['id'];
    $admin_note = trim($_POST['admin_note']);
    $model->resolveDispute($id, $admin_note);
    header("Location: DisputeController.php");
    exit();
}

if ($action === 'view' && isset($_GET['id'])) {
    $dispute = $model->getDisputeById((int)$_GET['id']);
    if (!$dispute) {
        die("Dispute not found. ID: " . $_GET['id']);
    }
    require_once '../views/dispute.php';
    exit();
}

$status   = $_GET['status'] ?? '';
$disputes = $model->getAllDisputes($status);

require_once '../views/dispute.php';
?>