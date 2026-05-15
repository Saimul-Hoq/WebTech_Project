<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';

header('Content-Type: application/json');

// RBAC check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);
$quantity  = (int)($_POST['quantity']   ?? -1);
$sellerId  = $_SESSION['seller_id'];

// Validate
if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

if ($quantity < 0) {
    echo json_encode(['success' => false, 'message' => 'Quantity cannot be negative']);
    exit;
}

$productModel = new Product();

// Verify product belongs to this seller
$product = $productModel->findByIdAndSeller($productId, $sellerId);
if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Update stock
$ok = $productModel->updateStock($productId, $sellerId, $quantity);

if ($ok) {
    echo json_encode([
        'success'       => true,
        'message'       => 'Stock updated successfully',
        'new_quantity'  => $quantity,
        'is_low_stock'  => $quantity < 10,
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update stock']);
}