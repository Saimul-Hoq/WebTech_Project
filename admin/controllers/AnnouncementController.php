<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/AnnouncementModel.php';

$model  = new AnnouncementModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title']);
    $message = trim($_POST['message']);
    if ($title && $message) {
        $model->addAnnouncement($title, $message, $_SESSION['admin_id']);
    }
    header("Location: AnnouncementController.php");
    exit();
}

if ($action === 'delete' && isset($_GET['id'])) {
    $model->deleteAnnouncement($_GET['id']);
    header("Location: AnnouncementController.php");
    exit();
}

$announcements = $model->getAllAnnouncements();
require_once '../views/announcements.php';
?>