<?php
require_once __DIR__ . '/BaseModel.php';

class OrderModel extends BaseModel {

    public function getReadyToDispatchOrders() {
        $sql = "SELECT DISTINCT o.id, u.name AS customer_name, o.shipping_address,
                o.total_amount, o.created_at
                FROM orders o
                JOIN users u ON o.user_id = u.id
                JOIN order_items oi ON o.id = oi.order_id
                WHERE oi.status = 'shipped'
                AND o.id NOT IN (SELECT order_id FROM delivery_assignments)
                ORDER BY o.created_at ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderById($id) {
        $stmt = $this->conn->prepare(
            "SELECT o.*, u.name AS customer_name, u.email, u.phone
             FROM orders o
             JOIN users u ON o.user_id = u.id
             WHERE o.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getOrderItems($order_id) {
        $stmt = $this->conn->prepare(
            "SELECT oi.*, p.name AS product_name, s.shop_name
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             JOIN sellers s ON oi.seller_id = s.id
             WHERE oi.order_id = ?"
        );
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}