<?php
require_once '../config/db.php';

class DashboardModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getTotalUsers() {
        $result = $this->conn->query("SELECT role, COUNT(*) as total FROM users GROUP BY role");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[$row['role']] = $row['total'];
        }
        return $data;
    }

    public function getTotalActiveSellers() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM sellers WHERE status = 'approved'");
        return $result->fetch_assoc()['total'];
    }

    public function getTodayOrders() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURDATE()");
        return $result->fetch_assoc()['total'];
    }

    public function getMonthlyRevenue() {
        $result = $this->conn->query("SELECT SUM(total_amount) as revenue FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        return $result->fetch_assoc()['revenue'] ?? 0;
    }

    public function getTotalOrders() {
    $result = $this->conn->query("SELECT COUNT(*) as total FROM orders");
    return $result->fetch_assoc()['total'];
    }

    public function getPendingDisputes() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM disputes WHERE status = 'open'");
        return $result->fetch_assoc()['total'];
    }
}
?>