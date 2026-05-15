<?php

class Review {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Get all reviews for seller's products
    public function getAllBySeller(int $sellerId): array {
        $stmt = $this->db->prepare(
            "SELECT r.id, r.rating, r.comment, r.seller_reply,
                    r.created_at, r.updated_at,
                    p.name AS product_name, p.primary_image, p.id AS product_id,
                    u.name AS customer_name, u.email AS customer_email
             FROM reviews r
             JOIN products p ON p.id = r.product_id
             JOIN users u    ON u.id = r.user_id
             WHERE p.seller_id = ?
             ORDER BY r.created_at DESC"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    // Get single review — verify product belongs to seller
    public function findByIdAndSeller(int $reviewId, int $sellerId): ?array {
        $stmt = $this->db->prepare(
            "SELECT r.id, r.rating, r.comment, r.seller_reply,
                    r.created_at, r.user_id,
                    p.name AS product_name, p.seller_id,
                    u.name AS customer_name
             FROM reviews r
             JOIN products p ON p.id = r.product_id
             JOIN users u    ON u.id = r.user_id
             WHERE r.id = ? AND p.seller_id = ?
             LIMIT 1"
        );
        $stmt->bind_param("ii", $reviewId, $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ?: null;
    }

    // Save seller reply to a review
    public function saveReply(int $reviewId, string $reply): bool {
        $stmt = $this->db->prepare(
            "UPDATE reviews
             SET seller_reply = ?, updated_at = NOW()
             WHERE id = ?"
        );
        $stmt->bind_param("si", $reply, $reviewId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get average rating for seller
    public function getAverageRating(int $sellerId): float {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(AVG(r.rating), 0) AS avg_rating
             FROM reviews r
             JOIN products p ON p.id = r.product_id
             WHERE p.seller_id = ?"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $avg = $stmt->get_result()->fetch_assoc()['avg_rating'];
        $stmt->close();
        return round((float)$avg, 1);
    }

    // Count unreplied reviews
    public function countUnreplied(int $sellerId): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS cnt
             FROM reviews r
             JOIN products p ON p.id = r.product_id
             WHERE p.seller_id = ? AND (r.seller_reply IS NULL OR r.seller_reply = '')"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $cnt = $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();
        return (int)$cnt;
    }
}