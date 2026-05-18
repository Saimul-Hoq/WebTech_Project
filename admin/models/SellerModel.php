<?php
require_once '../config/db.php';

class SellerModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllSellers() {
        $result = $this->conn->query(
            "SELECT s.*, u.name, u.email, u.is_active 
             FROM sellers s 
             JOIN users u ON s.user_id = u.id 
             ORDER BY s.created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateSellerStatus($seller_id, $status) {
        $stmt = $this->conn->prepare(
            "UPDATE sellers SET status = ?, updated_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param("si", $status, $seller_id);
        return $stmt->execute();
    }

    public function toggleUserActive($user_id, $is_active) {
        $stmt = $this->conn->prepare(
            "UPDATE users SET is_active = ? WHERE id = ?"
        );
        $stmt->bind_param("ii", $is_active, $user_id);
        return $stmt->execute();
    }
}
?>