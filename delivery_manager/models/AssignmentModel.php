<?php
require_once __DIR__ . '/BaseModel.php';

class AssignmentModel extends BaseModel {

    public function assignAgent($order_id, $agent_id, $delivery_zone) {
        $stmt = $this->conn->prepare(
            "INSERT INTO delivery_assignments (order_id, agent_id, delivery_zone, status)
             VALUES (?, ?, ?, 'assigned')"
        );
        $stmt->bind_param("iis", $order_id, $agent_id, $delivery_zone);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function getActiveDeliveries() {
        $sql = "SELECT da.id, da.order_id, da.status, da.assigned_at, da.delivery_zone,
                u.name AS customer_name, o.shipping_address,
                ag_user.name AS agent_name, da.agent_id
                FROM delivery_assignments da
                JOIN orders o ON da.order_id = o.id
                JOIN users u ON o.user_id = u.id
                JOIN delivery_agents ag ON da.agent_id = ag.id
                JOIN users ag_user ON ag.user_id = ag_user.id
                WHERE da.status IN ('assigned','picked_up','in_transit')
                ORDER BY da.assigned_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAssignmentById($id) {
        $stmt = $this->conn->prepare(
            "SELECT da.*, ag_user.name AS agent_name, u.name AS customer_name
             FROM delivery_assignments da
             JOIN delivery_agents ag ON da.agent_id = ag.id
             JOIN users ag_user ON ag.user_id = ag_user.id
             JOIN orders o ON da.order_id = o.id
             JOIN users u ON o.user_id = u.id
             WHERE da.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare(
            "UPDATE delivery_assignments SET status = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function reassignAgent($id, $agent_id) {
        $stmt = $this->conn->prepare(
            "UPDATE delivery_assignments SET agent_id = ?, status = 'assigned' WHERE id = ?"
        );
        $stmt->bind_param("ii", $agent_id, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function getDeliveryHistory() {
        $sql = "SELECT da.id, da.order_id, da.status, da.assigned_at, da.delivery_zone,
                u.name AS customer_name, ag_user.name AS agent_name
                FROM delivery_assignments da
                JOIN orders o ON da.order_id = o.id
                JOIN users u ON o.user_id = u.id
                JOIN delivery_agents ag ON da.agent_id = ag.id
                JOIN users ag_user ON ag.user_id = ag_user.id
                WHERE da.status IN ('delivered','failed')
                ORDER BY da.assigned_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
