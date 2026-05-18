<?php
require_once '../config/db.php';

class FeaturedModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllProducts() {
        $result = $this->conn->query(
            "SELECT p.*, s.shop_name, c.name as category_name
             FROM products p
             JOIN sellers s ON p.seller_id = s.id
             JOIN categories c ON p.category_id = c.id
             ORDER BY p.is_featured DESC, p.created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function toggleFeatured($id, $is_featured) {
        $stmt = $this->conn->prepare(
            "UPDATE products SET is_featured = ? WHERE id = ?"
        );
        $stmt->bind_param("ii", $is_featured, $id);
        return $stmt->execute();
    }
}
?>