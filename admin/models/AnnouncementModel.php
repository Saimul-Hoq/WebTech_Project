<?php
require_once '../config/db.php';

class AnnouncementModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllAnnouncements() {
        $result = $this->conn->query(
            "SELECT a.*, u.name as admin_name
             FROM announcements a
             JOIN users u ON a.created_by = u.id
             ORDER BY a.created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addAnnouncement($title, $message, $admin_id) {
        $stmt = $this->conn->prepare(
            "INSERT INTO announcements (title, message, created_by) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("ssi", $title, $message, $admin_id);
        return $stmt->execute();
    }

    public function deleteAnnouncement($id) {
        $stmt = $this->conn->prepare(
            "DELETE FROM announcements WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>