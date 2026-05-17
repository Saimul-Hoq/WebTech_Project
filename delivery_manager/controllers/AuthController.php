<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND is_active = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] !== 'delivery_manager') {
            header("Location: /WebTech_Project/delivery_manager/views/login.php?error=Access+denied");
            exit;
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
        header("Location: /WebTech_Project/delivery_manager/controllers/DashboardController.php");
        exit;
    } else {
        header("Location: /WebTech_Project/delivery_manager/views/login.php?error=Invalid+credentials");
        exit;
    }
}

if ($action === 'logout') {
    session_destroy();
    header("Location: /WebTech_Project/delivery_manager/views/login.php");
    exit;
}

// Default — show login
header("Location: /WebTech_Project/delivery_manager/views/login.php");
exit;