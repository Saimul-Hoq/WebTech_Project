<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/UserModel.php';

$model  = new UserModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'deactivate' && isset($_GET['id'])) {
    $model->toggleActive($_GET['id'], 0);
    header("Location: UserController.php");
    exit();
}

if ($action === 'reactivate' && isset($_GET['id'])) {
    $model->toggleActive($_GET['id'], 1);
    header("Location: UserController.php");
    exit();
}

$search    = $_GET['search'] ?? '';
$role      = $_GET['role'] ?? 'customer';
$users     = $model->getUsers($role, $search);

require_once '../views/users.php';
?>