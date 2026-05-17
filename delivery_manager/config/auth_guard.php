<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function requireDeliveryManager() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'delivery_manager') {
        header("Location: /WebTech_Project/delivery_manager/views/login.php?error=Unauthorized+access");
        exit;
    }
}