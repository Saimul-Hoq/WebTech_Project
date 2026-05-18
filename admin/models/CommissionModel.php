<?php
require_once '../config/db.php';

class CommissionModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllSellersWithCommission() {
        $result = $this->conn->query(
            "SELECT s.id, s.shop_name, s.commission_rate, u.name, u.email
             FROM sellers s
             JOIN users u ON s.user_id = u.id
             WHERE s.status = 'approved'
             ORDER BY s.shop_name ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateCommission($seller_id, $rate) {
        $stmt = $this->conn->prepare(
            "UPDATE sellers SET commission_rate = ?, updated_at = NOW() WHERE id = ?"
        );
        $stmt->bind_param("di", $rate, $seller_id);
        return $stmt->execute();
    }
}
?>