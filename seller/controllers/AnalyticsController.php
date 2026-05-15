<?php

class AnalyticsController {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    private function requireSeller(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function index(): void {
        $this->requireSeller();

        $sellerId = $_SESSION['seller_id'];
        $period   = $_GET['period'] ?? '30';
        if (!in_array($period, ['7', '30', '90'])) $period = '30';

        // --- Total Revenue (period) ---
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(oi.price * oi.quantity), 0) AS revenue
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id
             WHERE oi.seller_id = ? AND oi.status = 'delivered'
             AND o.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)"
        );
        $stmt->bind_param("ii", $sellerId, $period);
        $stmt->execute();
        $totalRevenue = $stmt->get_result()->fetch_assoc()['revenue'];
        $stmt->close();

        // --- Total Orders (period) ---
        $stmt = $this->db->prepare(
            "SELECT COUNT(DISTINCT oi.order_id) AS total
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id
             WHERE oi.seller_id = ?
             AND o.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)"
        );
        $stmt->bind_param("ii", $sellerId, $period);
        $stmt->execute();
        $totalOrders = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        // --- Average Order Value ---
        $avgOrderValue = $totalOrders > 0
            ? round($totalRevenue / $totalOrders, 2)
            : 0;

        // --- Platform commission (10%) and net payout ---
        $commission = round($totalRevenue * 0.10, 2);
        $netPayout  = round($totalRevenue - $commission, 2);

        // --- Top Selling Products ---
        $stmt = $this->db->prepare(
            "SELECT p.name, SUM(oi.quantity) AS units_sold,
                    SUM(oi.price * oi.quantity) AS revenue
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             JOIN orders o   ON o.id = oi.order_id
             WHERE oi.seller_id = ? AND oi.status = 'delivered'
             AND o.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
             GROUP BY oi.product_id
             ORDER BY revenue DESC
             LIMIT 5"
        );
        $stmt->bind_param("ii", $sellerId, $period);
        $stmt->execute();
        $topProducts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // --- Daily revenue for chart (last 7 days always) ---
        $stmt = $this->db->prepare(
            "SELECT DATE(o.created_at) AS day,
                    COALESCE(SUM(oi.price * oi.quantity), 0) AS revenue
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id
             WHERE oi.seller_id = ? AND oi.status = 'delivered'
             AND o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY DATE(o.created_at)
             ORDER BY day ASC"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $dailyRevenue = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $pageTitle    = 'Analytics';
        $pageSubtitle = 'Sales performance and earnings summary';

        require_once __DIR__ . '/../views/analytics/index.php';
    }
}