<?php
require_once '../config/db.php';

class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUsers($role, $search = '') {
        $search = "%$search%";
        $stmt = $this->conn->prepare(
            "SELECT * FROM users 
             WHERE role = ? AND (name LIKE ? OR email LIKE ?)
             ORDER BY created_at DESC"
        );
        $stmt->bind_param("sss", $role, $search, $search);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function toggleActive($id, $is_active) {
        $stmt = $this->conn->prepare(
            "UPDATE users SET is_active = ? WHERE id = ?"
        );
        $stmt->bind_param("ii", $is_active, $id);
        return $stmt->execute();
    }
}
?>