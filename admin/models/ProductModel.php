<?php
require_once '../config/db.php';

class ProductModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllProducts($search = '', $category_id = '', $seller_id = '') {
        $sql = "SELECT p.*, c.name as category_name, s.shop_name, u.name as seller_name
                FROM products p
                JOIN categories c ON p.category_id = c.id
                JOIN sellers s ON p.seller_id = s.id
                JOIN users u ON s.user_id = u.id
                WHERE 1=1";

        $params = [];
        $types  = '';

        if ($search) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%$search%";
            $types   .= 's';
        }
        if ($category_id) {
            $sql .= " AND p.category_id = ?";
            $params[] = $category_id;
            $types   .= 'i';
        }
        if ($seller_id) {
            $sql .= " AND p.seller_id = ?";
            $params[] = $seller_id;
            $types   .= 'i';
        }

        $sql .= " ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function removeProduct($id) {
        $stmt = $this->conn->prepare(
            "UPDATE products SET is_available = 0 WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getAllCategories() {
        $result = $this->conn->query("SELECT id, name FROM categories ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllSellers() {
        $result = $this->conn->query(
            "SELECT s.id, s.shop_name FROM sellers s WHERE s.status = 'approved' ORDER BY s.shop_name ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>