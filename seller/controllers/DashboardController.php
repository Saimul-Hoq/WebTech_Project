<?php

require_once __DIR__ . '/../models/Seller.php';

class DashboardController {

    private $db;
    private Seller $sellerModel;

    public function __construct() {
        $this->db          = getDB();
        $this->sellerModel = new Seller();
    }

    public function index(): void {
        // RBAC — sellers only
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            header('Location: index.php?page=login');
            exit;
        }

        $sellerId = $_SESSION['seller_id'];

        // --- Total Revenue (delivered orders only) ---
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(oi.price * oi.quantity), 0) AS total_revenue
             FROM order_items oi
             WHERE oi.seller_id = ? AND oi.status = 'delivered'"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $totalRevenue = $stmt->get_result()->fetch_assoc()['total_revenue'];
        $stmt->close();

        // --- Total Orders ---
        $stmt = $this->db->prepare(
            "SELECT COUNT(DISTINCT oi.order_id) AS total_orders
             FROM order_items oi
             WHERE oi.seller_id = ?"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $totalOrders = $stmt->get_result()->fetch_assoc()['total_orders'];
        $stmt->close();

        // --- Total Products ---
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total_products FROM products WHERE seller_id = ?"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $totalProducts = $stmt->get_result()->fetch_assoc()['total_products'];
        $stmt->close();

        // --- Pending Orders ---
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS pending_orders
             FROM order_items oi
             WHERE oi.seller_id = ? AND oi.status = 'pending'"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $pendingOrders = $stmt->get_result()->fetch_assoc()['pending_orders'];
        $stmt->close();

        // --- Low Stock Products (below 10) ---
        $stmt = $this->db->prepare(
            "SELECT id, name, stock_quantity
             FROM products
             WHERE seller_id = ? AND stock_quantity < 10 AND is_available = 1
             ORDER BY stock_quantity ASC
             LIMIT 5"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $lowStockProducts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // --- Recent Orders ---
        $stmt = $this->db->prepare(
            "SELECT o.id AS order_id, o.created_at, o.payment_method,
                    oi.status, oi.quantity, oi.price,
                    p.name AS product_name,
                    u.name AS customer_name
             FROM order_items oi
             JOIN orders o  ON o.id  = oi.order_id
             JOIN products p ON p.id = oi.product_id
             JOIN users u    ON u.id = o.user_id
             WHERE oi.seller_id = ?
             ORDER BY o.created_at DESC
             LIMIT 6"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $recentOrders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // --- Top Products by Revenue ---
        $stmt = $this->db->prepare(
            "SELECT p.name, SUM(oi.price * oi.quantity) AS revenue, SUM(oi.quantity) AS units_sold
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.seller_id = ? AND oi.status = 'delivered'
             GROUP BY oi.product_id
             ORDER BY revenue DESC
             LIMIT 5"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $topProducts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $pageTitle    = 'Dashboard';
        $pageSubtitle = 'Welcome back, ' . htmlspecialchars($_SESSION['name']);

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}