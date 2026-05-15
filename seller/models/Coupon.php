<?php

class Coupon {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Get all coupons for a seller
    public function getAllBySeller(int $sellerId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM coupons
             WHERE seller_id = ?
             ORDER BY created_at DESC"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    // Get single coupon — verify belongs to seller
    public function findByIdAndSeller(int $couponId, int $sellerId): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM coupons
             WHERE id = ? AND seller_id = ? LIMIT 1"
        );
        $stmt->bind_param("ii", $couponId, $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ?: null;
    }

    // Check if coupon code already exists for this seller
    public function codeExists(string $code, int $sellerId, ?int $excludeId = null): bool {
        if ($excludeId) {
            $stmt = $this->db->prepare(
                "SELECT id FROM coupons
                 WHERE code = ? AND seller_id = ? AND id != ? LIMIT 1"
            );
            $stmt->bind_param("sii", $code, $sellerId, $excludeId);
        } else {
            $stmt = $this->db->prepare(
                "SELECT id FROM coupons
                 WHERE code = ? AND seller_id = ? LIMIT 1"
            );
            $stmt->bind_param("si", $code, $sellerId);
        }
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Create coupon
    public function create(array $data): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO coupons
                (seller_id, code, discount_percentage, max_uses, expires_at, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, 1, NOW())"
        );
        $stmt->bind_param(
            "issds",
            $data['seller_id'],
            $data['code'],
            $data['discount_percentage'],
            $data['max_uses'],
            $data['expires_at']
        );
        $ok    = $stmt->execute();
        $newId = $this->db->insert_id;
        $stmt->close();
        return $ok ? $newId : false;
    }

    // Toggle active/inactive
    public function toggle(int $couponId, int $sellerId): bool {
        $stmt = $this->db->prepare(
            "UPDATE coupons
             SET is_active = IF(is_active = 1, 0, 1)
             WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param("ii", $couponId, $sellerId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Delete coupon
    public function delete(int $couponId, int $sellerId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM coupons WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param("ii", $couponId, $sellerId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get usage count for a coupon
    public function getUsageCount(int $couponId): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS cnt FROM orders WHERE coupon_id = ?"
        );
        $stmt->bind_param("i", $couponId);
        $stmt->execute();
        $cnt = $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();
        return (int)$cnt;
    }
}