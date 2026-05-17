<?php
if (!class_exists('BaseModel')) require_once __DIR__ . '/BaseModel.php';

class ZoneModel extends BaseModel {

    public function getAllZones() {
        $result = $this->conn->query("SELECT * FROM delivery_zones ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getZoneById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM delivery_zones WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createZone($zone_name, $delivery_fee, $estimated_days) {
        $stmt = $this->conn->prepare(
            "INSERT INTO delivery_zones (zone_name, delivery_fee, estimated_days) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sdi", $zone_name, $delivery_fee, $estimated_days);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function updateZone($id, $zone_name, $delivery_fee, $estimated_days) {
        $stmt = $this->conn->prepare(
            "UPDATE delivery_zones SET zone_name = ?, delivery_fee = ?, estimated_days = ? WHERE id = ?"
        );
        $stmt->bind_param("sdii", $zone_name, $delivery_fee, $estimated_days, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function deleteZone($id) {
        $stmt = $this->conn->prepare("DELETE FROM delivery_zones WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}