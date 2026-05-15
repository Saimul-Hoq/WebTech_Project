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

// Only accept GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$sellerId  = $_SESSION['seller_id'];

// Optional custom threshold from query string
$threshold = isset($_GET['threshold']) ? (int)$_GET['threshold'] : 10;

// Clamp threshold between 1 and 100
$threshold = max(1, min(100, $threshold));

$productModel    = new Product();
$lowStockProducts = $productModel->getLowStock($sellerId, $threshold);

echo json_encode([
    'success'   => true,
    'threshold' => $threshold,
    'count'     => count($lowStockProducts),
    'products'  => $lowStockProducts,
]);