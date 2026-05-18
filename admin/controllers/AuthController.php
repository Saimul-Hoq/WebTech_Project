<?php
session_start();
require_once '../config/db.php';
require_once '../models/AdminModel.php';
// GJJGJGJJGJH
$model  = new AdminModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $admin = $model->findAdminByEmail($email);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: DashboardController.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: ../views/login.php");
        exit();
    }
}

if ($action === 'logout') {
    session_destroy();
    header("Location: ../views/login.php");
    exit();
}

header("Location: ../views/login.php");
exit();
?>