<?php
require_once '../config/db.php';

class OrderModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllOrders($status = '', $date_from = '', $date_to = '', $seller_id = '', $user_id = '') {
        $sql = "SELECT o.*, u.name as customer_name, u.email as customer_email
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE 1=1";

        $params = [];
        $types  = '';

        if ($status) {
            $sql .= " AND EXISTS (
                SELECT 1 FROM order_items oi 
                WHERE oi.order_id = o.id AND oi.status = ?
            )";
            $params[] = $status;
            $types   .= 's';
        }

        if ($date_from) {
            $sql .= " AND DATE(o.created_at) >= ?";
            $params[] = $date_from;
            $types   .= 's';
        }

        if ($date_to) {
            $sql .= " AND DATE(o.created_at) <= ?";
            $params[] = $date_to;
            $types   .= 's';
        }

        if ($seller_id) {
            $sql .= " AND EXISTS (
                SELECT 1 FROM order_items oi 
                WHERE oi.order_id = o.id AND oi.seller_id = ?
            )";
            $params[] = $seller_id;
            $types   .= 'i';
        }

        if ($user_id) {
            $sql .= " AND o.user_id = ?";
            $params[] = $user_id;
            $types   .= 'i';
        }

        $sql .= " ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderItems($order_id) {
        $stmt = $this->conn->prepare(
            "SELECT oi.*, p.name as product_name, s.shop_name
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             JOIN sellers s ON oi.seller_id = s.id
             WHERE oi.order_id = ?"
        );
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllSellers() {
        $result = $this->conn->query(
            "SELECT id, shop_name FROM sellers WHERE status = 'approved' ORDER BY shop_name ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>