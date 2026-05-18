<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/CategoryModel.php';

$model  = new CategoryModel($conn);
$action = $_GET['action'] ?? '';
$error  = '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $parent_id   = $_POST['parent_id'] ?? null;
    if ($name) {
        $model->addCategory($name, $description, $parent_id);
    }
    header("Location: CategoryController.php");
    exit();
}

if ($action === 'rename' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = $_POST['id'];
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $model->renameCategory($id, $name, $description);
    header("Location: CategoryController.php");
    exit();
}

if ($action === 'delete' && isset($_GET['id'])) {
    if ($model->hasProducts($_GET['id'])) {
        $error = "Cannot delete. Products exist in this category.";
    } else {
        $model->deleteCategory($_GET['id']);
        header("Location: CategoryController.php");
        exit();
    }
}

$categories = $model->getAllCategories();
require_once '../views/categories.php';
?>