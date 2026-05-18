<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/ProfileModel.php';

$model   = new ProfileModel($conn);
$admin   = $model->getAdminById($_SESSION['admin_id']);
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (!password_verify($current, $admin['password'])) {
        $error = "Current password is wrong.";
    } elseif ($new !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($new) < 6) {
        $error = "Min 6 characters.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $model->updatePassword($_SESSION['admin_id'], $hashed);
        $success = "Password changed.";
    }
}

require_once '../views/profile.php';
?>