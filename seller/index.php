<?php
// Start session for all pages
session_start();

// Load database config
require_once __DIR__ . '/config/database.php';

// Security: strip tags from GET/POST to prevent XSS
$page = isset($_GET['page']) ? strip_tags(trim($_GET['page'])) : 'login';

// Public pages — no login required
$publicPages = ['login', 'register'];

// If not a public page and not logged in — redirect to login
if (!in_array($page, $publicPages) && $page !== 'logout') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
        header('Location: index.php?page=login');
        exit;
    }
}

// Route map — page => [controller file, controller class, method]
$routes = [
    'login'           => ['controllers/AuthController.php',     'AuthController',     'login'],
    'register'        => ['controllers/AuthController.php',     'AuthController',     'register'],
    'logout'          => ['controllers/AuthController.php',     'AuthController',     'logout'],
    'dashboard'       => ['controllers/DashboardController.php','DashboardController','index'],
    'shop-profile'    => ['controllers/ShopController.php',     'ShopController',     'index'],
    'products'        => ['controllers/ProductController.php',  'ProductController',  'index'],
    'products-create' => ['controllers/ProductController.php',  'ProductController',  'create'],
    'products-edit'   => ['controllers/ProductController.php',  'ProductController',  'edit'],
    'products-delete' => ['controllers/ProductController.php',  'ProductController',  'delete'],
    'products-toggle' => ['controllers/ProductController.php',  'ProductController',  'toggle'],
    'orders'          => ['controllers/OrderController.php',    'OrderController',    'index'],
    'orders-detail'   => ['controllers/OrderController.php',    'OrderController',    'detail'],
    'orders-update'   => ['controllers/OrderController.php',    'OrderController',    'updateStatus'],
    'coupons'         => ['controllers/CouponController.php',   'CouponController',   'index'],
    'coupons-create'  => ['controllers/CouponController.php',   'CouponController',   'create'],
    'coupons-toggle'  => ['controllers/CouponController.php',   'CouponController',   'toggle'],
    'coupons-delete'  => ['controllers/CouponController.php',   'CouponController',   'delete'],
    'reviews'         => ['controllers/ReviewController.php',   'ReviewController',   'index'],
    'reviews-reply'   => ['controllers/ReviewController.php',   'ReviewController',   'reply'],
    'returns'         => ['controllers/ReturnController.php',   'ReturnController',   'index'],
    'returns-action'  => ['controllers/ReturnController.php',   'ReturnController',   'action'],
    'analytics'       => ['controllers/AnalyticsController.php','AnalyticsController','index'],
];

// Check if route exists
if (array_key_exists($page, $routes)) {
    [$file, $class, $method] = $routes[$page];

    require_once __DIR__ . '/' . $file;

    $controller = new $class();
    $controller->$method();
} else {
    // Page not found — redirect to login
    header('Location: index.php?page=login');
    exit;
}