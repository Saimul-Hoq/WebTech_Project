<?php
require_once __DIR__ . '/BaseModel.php';

class ReportModel extends BaseModel {

    public function getDashboardStats() {
        $stats = [];

        // Pending dispatch count
        $sql = "SELECT COUNT(DISTINCT o.id) AS count FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                WHERE oi.status = 'shipped'
                AND o.id NOT IN (SELECT order_id FROM delivery_assignments)";
        $stats['pending_dispatch'] = $this->conn->query($sql)->fetch_assoc()['count'];

        // Active deliveries
        $sql = "SELECT COUNT(*) AS count FROM delivery_assignments
                WHERE status IN ('assigned','picked_up','in_transit')";
        $stats['active_deliveries'] = $this->conn->query($sql)->fetch_assoc()['count'];

        // Delivered today
        $sql = "SELECT COUNT(*) AS count FROM delivery_assignments
                WHERE status = 'delivered'
                AND DATE(assigned_at) = CURDATE()";
        $stats['delivered_today'] = $this->conn->query($sql)->fetch_assoc()['count'];

        return $stats;
    }

    public function getAgentPerformance() {
        $sql = "SELECT u.name AS agent_name, da.vehicle_type,
                COUNT(CASE WHEN ass.status = 'delivered' THEN 1 END) AS completed,
                COUNT(CASE WHEN ass.status = 'failed' THEN 1 END) AS failed,
                COUNT(ass.id) AS total
                FROM delivery_agents da
                JOIN users u ON da.user_id = u.id
                LEFT JOIN delivery_assignments ass ON da.id = ass.agent_id
                GROUP BY da.id, u.name, da.vehicle_type
                ORDER BY completed DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getZonePerformance() {
        $sql = "SELECT delivery_zone,
                COUNT(*) AS total_deliveries,
                COUNT(CASE WHEN status = 'delivered' THEN 1 END) AS delivered,
                COUNT(CASE WHEN status = 'failed' THEN 1 END) AS failed
                FROM delivery_assignments
                GROUP BY delivery_zone
                ORDER BY total_deliveries DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getDailySummary() {
        $sql = "SELECT DATE(assigned_at) AS date,
                COUNT(*) AS total,
                COUNT(CASE WHEN status = 'delivered' THEN 1 END) AS delivered,
                COUNT(CASE WHEN status = 'failed' THEN 1 END) AS failed,
                COUNT(CASE WHEN status IN ('assigned','picked_up','in_transit') THEN 1 END) AS in_transit
                FROM delivery_assignments
                WHERE assigned_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(assigned_at)
                ORDER BY date DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}