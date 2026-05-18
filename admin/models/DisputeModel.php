<?php
require_once '../config/db.php';

class DisputeModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllDisputes($status = '') {
        $sql = "SELECT d.*, 
                u.name as customer_name, 
                s.shop_name,
                o.total_amount
                FROM disputes d
                JOIN users u ON d.user_id = u.id
                JOIN sellers s ON d.seller_id = s.id
                JOIN orders o ON d.order_id = o.id
                WHERE 1=1";

        $params = [];
        $types  = '';

        if ($status) {
            $sql .= " AND d.status = ?";
            $params[] = $status;
            $types   .= 's';
        }

        $sql .= " ORDER BY d.created_at DESC";
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getDisputeById($id) {
        $stmt = $this->conn->prepare(
            "SELECT d.*, 
             u.name as customer_name, u.email as customer_email,
             s.shop_name,
             o.total_amount, o.created_at as order_date
             FROM disputes d
             JOIN users u ON d.user_id = u.id
             JOIN sellers s ON d.seller_id = s.id
             JOIN orders o ON d.order_id = o.id
             WHERE d.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function resolveDispute($id, $admin_note) {
        $stmt = $this->conn->prepare(
            "UPDATE disputes SET status = 'resolved', admin_note = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $admin_note, $id);
        return $stmt->execute();
    }
}
?>