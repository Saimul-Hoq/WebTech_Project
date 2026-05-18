<?php
require_once '../config/db.php';

class CategoryModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCategories() {
        $result = $this->conn->query(
            "SELECT c.*, p.name as parent_name 
             FROM categories c 
             LEFT JOIN categories p ON c.parent_id = p.id 
             ORDER BY c.parent_id ASC, c.name ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addCategory($name, $description, $parent_id) {
        $stmt = $this->conn->prepare(
            "INSERT INTO categories (name, description, parent_id) VALUES (?, ?, ?)"
        );
        $parent_id = $parent_id ?: null;
        $stmt->bind_param("ssi", $name, $description, $parent_id);
        return $stmt->execute();
    }

    public function renameCategory($id, $name, $description) {
        $stmt = $this->conn->prepare(
            "UPDATE categories SET name = ?, description = ? WHERE id = ?"
        );
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    public function hasProducts($id) {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as total FROM products WHERE category_id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'] > 0;
    }

    public function deleteCategory($id) {
        $stmt = $this->conn->prepare(
            "DELETE FROM categories WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>