<?php
require_once __DIR__ . '/BaseModel.php';

class AgentModel extends BaseModel {

    public function getAllAgents() {
        $sql = "SELECT da.id, u.name, u.email, da.phone, da.vehicle_type, da.is_active,
                COUNT(CASE WHEN dassign.status IN ('assigned','picked_up','in_transit') THEN 1 END) AS active_deliveries
                FROM delivery_agents da
                JOIN users u ON da.user_id = u.id
                LEFT JOIN delivery_assignments dassign ON da.id = dassign.agent_id
                GROUP BY da.id, u.name, u.email, da.phone, da.vehicle_type, da.is_active";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAgentById($id) {
        $stmt = $this->conn->prepare(
            "SELECT da.*, u.name, u.email FROM delivery_agents da
             JOIN users u ON da.user_id = u.id
             WHERE da.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAvailableAgents() {
        $stmt = $this->conn->prepare(
            "SELECT da.id, u.name, da.vehicle_type FROM delivery_agents da
             JOIN users u ON da.user_id = u.id
             WHERE da.is_active = 1"
        );
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createAgent($name, $email, $phone, $vehicle_type) {
        // Create user first
        $password = password_hash('agent1234', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'delivery_manager')"
        );
        $stmt->bind_param("ssss", $name, $email, $password, $phone);
        $stmt->execute();
        $user_id = $this->conn->insert_id;
        $stmt->close();

        // Create agent record
        $stmt2 = $this->conn->prepare(
            "INSERT INTO delivery_agents (user_id, phone, vehicle_type) VALUES (?, ?, ?)"
        );
        $stmt2->bind_param("iss", $user_id, $phone, $vehicle_type);
        $stmt2->execute();
        $stmt2->close();
        return true;
    }

    public function updateAgent($id, $phone, $vehicle_type) {
        $stmt = $this->conn->prepare(
            "UPDATE delivery_agents SET phone = ?, vehicle_type = ? WHERE id = ?"
        );
        $stmt->bind_param("ssi", $phone, $vehicle_type, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function toggleStatus($id, $status) {
        $stmt = $this->conn->prepare(
            "UPDATE delivery_agents SET is_active = ? WHERE id = ?"
        );
        $stmt->bind_param("ii", $status, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}