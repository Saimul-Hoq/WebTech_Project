<?php
require_once '../views/auth_check.php';
require_once '../config/db.php';
require_once '../models/ProductModel.php';

$model  = new ProductModel($conn);
$action = $_GET['action'] ?? '';

if ($action === 'remove' && isset($_GET['id'])) {
    $model->removeProduct($_GET['id']);
    header("Location: ProductController.php");
    exit();
}

$search      = $_GET['search']      ?? '';
$category_id = $_GET['category_id'] ?? '';
$seller_id   = $_GET['seller_id']   ?? '';

$products   = $model->getAllProducts($search, $category_id, $seller_id);
$categories = $model->getAllCategories();
$sellers    = $model->getAllSellers();

require_once '../views/products.php';
?>