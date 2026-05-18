<?php
require_once '../config/db.php';

class AnalyticsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getGMV() {
        $result = $this->conn->query(
            "SELECT SUM(total_amount) as gmv FROM orders"
        );
        return $result->fetch_assoc()['gmv'] ?? 0;
    }

    public function getTotalCommission() {
        $result = $this->conn->query(
            "SELECT SUM(o.total_amount * s.commission_rate / 100) as commission
             FROM order_items oi
             JOIN orders o ON oi.order_id = o.id
             JOIN sellers s ON oi.seller_id = s.id"
        );
        return $result->fetch_assoc()['commission'] ?? 0;
    }

    public function getTopSellers($limit = 5) {
        $result = $this->conn->query(
            "SELECT s.shop_name, u.name,
             SUM(oi.price * oi.quantity) as total_sales
             FROM order_items oi
             JOIN sellers s ON oi.seller_id = s.id
             JOIN users u ON s.user_id = u.id
             GROUP BY oi.seller_id
             ORDER BY total_sales DESC
             LIMIT $limit"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTopCategories($limit = 5) {
        $result = $this->conn->query(
            "SELECT c.name as category_name,
             SUM(oi.price * oi.quantity) as total_sales
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             JOIN categories c ON p.category_id = c.id
             GROUP BY p.category_id
             ORDER BY total_sales DESC
             LIMIT $limit"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMonthlyRevenue() {
        $result = $this->conn->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
             SUM(total_amount) as revenue
             FROM orders
             GROUP BY month
             ORDER BY month DESC
             LIMIT 12"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDeliveryOverview() {
        $result = $this->conn->query(
            "SELECT da.status, COUNT(*) as total
             FROM delivery_assignments da
             GROUP BY da.status"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>