<?php
require_once '../config/db.php';

class ReportModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getMonthlyReport($month, $year) {
        $data = [];

        // Total orders
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as total, SUM(total_amount) as revenue
             FROM orders
             WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?"
        );
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $data['orders'] = $stmt->get_result()->fetch_assoc();

        // Total new users
        $stmt = $this->conn->prepare(
            "SELECT role, COUNT(*) as total
             FROM users
             WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
             GROUP BY role"
        );
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $data['users'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Top sellers
        $stmt = $this->conn->prepare(
            "SELECT s.shop_name, SUM(oi.price * oi.quantity) as total_sales
             FROM order_items oi
             JOIN sellers s ON oi.seller_id = s.id
             JOIN orders o ON oi.order_id = o.id
             WHERE MONTH(o.created_at) = ? AND YEAR(o.created_at) = ?
             GROUP BY oi.seller_id
             ORDER BY total_sales DESC
             LIMIT 5"
        );
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $data['top_sellers'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Top categories
        $stmt = $this->conn->prepare(
            "SELECT c.name as category_name, SUM(oi.price * oi.quantity) as total_sales
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             JOIN categories c ON p.category_id = c.id
             JOIN orders o ON oi.order_id = o.id
             WHERE MONTH(o.created_at) = ? AND YEAR(o.created_at) = ?
             GROUP BY p.category_id
             ORDER BY total_sales DESC
             LIMIT 5"
        );
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $data['top_categories'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Disputes
        $stmt = $this->conn->prepare(
            "SELECT status, COUNT(*) as total
             FROM disputes
             WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
             GROUP BY status"
        );
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $data['disputes'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return $data;
    }
}
?>