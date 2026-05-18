<?php
require_once '../config/db.php';

class CouponModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCoupons() {
        $result = $this->conn->query(
            "SELECT c.*, s.shop_name 
             FROM coupons c
             JOIN sellers s ON c.seller_id = s.id
             ORDER BY c.created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addCoupon($seller_id, $code, $discount, $max_uses, $expires_at) {
        $stmt = $this->conn->prepare(
            "INSERT INTO coupons (seller_id, code, discount_percentage, max_uses, expires_at)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isdis", $seller_id, $code, $discount, $max_uses, $expires_at);
        return $stmt->execute();
    }

    public function toggleCoupon($id, $is_active) {
        $stmt = $this->conn->prepare(
            "UPDATE coupons SET is_active = ? WHERE id = ?"
        );
        $stmt->bind_param("ii", $is_active, $id);
        return $stmt->execute();
    }

    public function getAllSellers() {
        $result = $this->conn->query(
            "SELECT id, shop_name FROM sellers WHERE status = 'approved' ORDER BY shop_name ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>