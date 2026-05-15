<?php

class ReturnRequest {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Get all return requests for seller's products
    public function getAllBySeller(int $sellerId): array {
        $stmt = $this->db->prepare(
            "SELECT rr.id, rr.reason, rr.status, rr.seller_response,
                    rr.created_at, rr.updated_at,
                    oi.quantity, oi.price,
                    p.name AS product_name, p.primary_image,
                    u.name AS customer_name, u.email AS customer_email,
                    o.id AS order_id
             FROM return_requests rr
             JOIN order_items oi ON oi.id  = rr.order_item_id
             JOIN products p     ON p.id   = oi.product_id
             JOIN orders o       ON o.id   = oi.order_id
             JOIN users u        ON u.id   = rr.user_id
             WHERE oi.seller_id = ?
             ORDER BY rr.created_at DESC"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    // Get single return request — verify belongs to seller
    public function findByIdAndSeller(int $returnId, int $sellerId): ?array {
        $stmt = $this->db->prepare(
            "SELECT rr.id, rr.reason, rr.status, rr.seller_response,
                    rr.created_at, rr.user_id,
                    oi.quantity, oi.price, oi.seller_id,
                    p.name AS product_name,
                    u.name AS customer_name,
                    o.id AS order_id
             FROM return_requests rr
             JOIN order_items oi ON oi.id  = rr.order_item_id
             JOIN products p     ON p.id   = oi.product_id
             JOIN orders o       ON o.id   = oi.order_id
             JOIN users u        ON u.id   = rr.user_id
             WHERE rr.id = ? AND oi.seller_id = ?
             LIMIT 1"
        );
        $stmt->bind_param("ii", $returnId, $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ?: null;
    }

    // Approve or reject return request
    public function respond(int $returnId, string $status, string $response): bool {
        $stmt = $this->db->prepare(
            "UPDATE return_requests
             SET status = ?, seller_response = ?, updated_at = NOW()
             WHERE id = ?"
        );
        $stmt->bind_param("ssi", $status, $response, $returnId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Count pending return requests
    public function countPending(int $sellerId): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS cnt
             FROM return_requests rr
             JOIN order_items oi ON oi.id = rr.order_item_id
             WHERE oi.seller_id = ? AND rr.status = 'pending'"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $cnt = $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();
        return (int)$cnt;
    }
}