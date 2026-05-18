<?php
require_once '../config/db.php';

class AdminModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findAdminByEmail($email) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email = ? AND role = 'admin' AND is_active = 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>