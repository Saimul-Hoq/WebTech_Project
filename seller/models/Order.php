<?php

class Order {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Get all order items for a seller with filter
    public function getAllBySeller(int $sellerId, string $status = ''): array {
        if ($status && $status !== 'all') {
            $stmt = $this->db->prepare(
                "SELECT oi.id AS item_id, oi.status, oi.quantity, oi.price,
                        oi.tracking_note, oi.updated_at AS item_updated,
                        o.id AS order_id, o.created_at, o.payment_method,
                        p.name AS product_name, p.primary_image,
                        u.name AS customer_name, u.email AS customer_email
                 FROM order_items oi
                 JOIN orders o   ON o.id  = oi.order_id
                 JOIN products p ON p.id  = oi.product_id
                 JOIN users u    ON u.id  = o.user_id
                 WHERE oi.seller_id = ? AND oi.status = ?
                 ORDER BY o.created_at DESC"
            );
            $stmt->bind_param("is", $sellerId, $status);
        } else {
            $stmt = $this->db->prepare(
                "SELECT oi.id AS item_id, oi.status, oi.quantity, oi.price,
                        oi.tracking_note, oi.updated_at AS item_updated,
                        o.id AS order_id, o.created_at, o.payment_method,
                        p.name AS product_name, p.primary_image,
                        u.name AS customer_name, u.email AS customer_email
                 FROM order_items oi
                 JOIN orders o   ON o.id  = oi.order_id
                 JOIN products p ON p.id  = oi.product_id
                 JOIN users u    ON u.id  = o.user_id
                 WHERE oi.seller_id = ?
                 ORDER BY o.created_at DESC"
            );
            $stmt->bind_param("i", $sellerId);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    // Get full order detail for seller
    public function getOrderDetail(int $orderId, int $sellerId): ?array {
        // Order header
        $stmt = $this->db->prepare(
            "SELECT o.id, o.created_at, o.payment_method,
                    o.shipping_address, o.total_amount,
                    u.name AS customer_name, u.email AS customer_email,
                    u.phone AS customer_phone
             FROM orders o
             JOIN users u ON u.id = o.user_id
             WHERE o.id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$order) return null;

        // Order items from this seller only
        $stmt = $this->db->prepare(
            "SELECT oi.id AS item_id, oi.status, oi.quantity, oi.price,
                    oi.tracking_note,
                    p.name AS product_name, p.primary_image
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = ? AND oi.seller_id = ?"
        );
        $stmt->bind_param("ii", $orderId, $sellerId);
        $stmt->execute();
        $order['items'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $order;
    }

    // Update order item status
    public function updateItemStatus(int $itemId, int $sellerId, string $status, string $trackingNote = ''): bool {
        $stmt = $this->db->prepare(
            "UPDATE order_items
             SET status = ?, tracking_note = ?, updated_at = NOW()
             WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param("ssii", $status, $trackingNote, $itemId, $sellerId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get single order item — verify belongs to seller
    public function getItem(int $itemId, int $sellerId): ?array {
        $stmt = $this->db->prepare(
            "SELECT oi.*, p.name AS product_name
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.id = ? AND oi.seller_id = ? LIMIT 1"
        );
        $stmt->bind_param("ii", $itemId, $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ?: null;
    }

    // Valid status transitions for seller
    public function getAllowedNextStatus(string $currentStatus): array {
        $transitions = [
            'pending'    => ['processing'],
            'processing' => ['shipped'],
            'shipped'    => [],
            'delivered'  => [],
            'cancelled'  => [],
        ];
        return $transitions[$currentStatus] ?? [];
    }
}