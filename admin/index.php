<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: controllers/AuthController.php?action=dashboard");
} else {
    header("Location: views/login.php");
}
exit();
?>