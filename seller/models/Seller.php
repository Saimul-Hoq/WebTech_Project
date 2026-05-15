<?php

class Seller {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Get seller profile by user_id
    public function findByUserId(int $userId): ?array {
        $stmt = $this->db->prepare(
            "SELECT s.*, u.name, u.email, u.phone, u.is_active
             FROM sellers s
             JOIN users u ON u.id = s.user_id
             WHERE s.user_id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $seller = $result->fetch_assoc();
        $stmt->close();
        return $seller ?: null;
    }

    // Get seller by seller id
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT s.*, u.name, u.email, u.phone
             FROM sellers s
             JOIN users u ON u.id = s.user_id
             WHERE s.id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $seller = $result->fetch_assoc();
        $stmt->close();
        return $seller ?: null;
    }

    // Create seller profile linked to user_id
    public function create(array $data): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO sellers (user_id, shop_name, shop_description, address, logo, status, created_at)
             VALUES (?, ?, ?, ?, ?, 'pending', NOW())"
        );
        $stmt->bind_param(
            "issss",
            $data['user_id'],
            $data['shop_name'],
            $data['shop_description'],
            $data['address'],
            $data['logo']
        );

        $ok = $stmt->execute();
        $newId = $this->db->insert_id;
        $stmt->close();

        return $ok ? $newId : false;
    }

    // Update shop profile
    public function updateProfile(int $userId, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE sellers
             SET shop_name = ?, shop_description = ?, address = ?, updated_at = NOW()
             WHERE user_id = ?"
        );
        $stmt->bind_param(
            "sssi",
            $data['shop_name'],
            $data['shop_description'],
            $data['address'],
            $userId
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Update logo only
    public function updateLogo(int $userId, string $logoPath): bool {
        $stmt = $this->db->prepare(
            "UPDATE sellers SET logo = ?, updated_at = NOW() WHERE user_id = ?"
        );
        $stmt->bind_param("si", $logoPath, $userId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Check approval status
    public function isApproved(int $userId): bool {
        $stmt = $this->db->prepare(
            "SELECT status FROM sellers WHERE user_id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row && $row['status'] === 'approved';
    }
}