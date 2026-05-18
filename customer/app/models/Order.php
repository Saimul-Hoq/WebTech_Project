<?php

class Order extends Model
{
    public function create($userId, $shippingAddress, $items)
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float) $item['price'] * (int) $item['cart_qty'];
        }

        $this->db->begin();

        try {
            $orderId = $this->db->insert(
                "INSERT INTO orders (user_id, shipping_address, payment_method, subtotal, discount_amount, total_amount)
                 VALUES (?, ?, 'cash_on_delivery', ?, 0, ?)",
                [(int) $userId, $shippingAddress, $subtotal, $subtotal]
            );

            foreach ($items as $item) {
                $qty = (int) $item['cart_qty'];
                $this->db->insert(
                    "INSERT INTO order_items (order_id, product_id, seller_id, quantity, price, status)
                     VALUES (?, ?, ?, ?, ?, 'pending')",
                    [$orderId, (int) $item['id'], (int) $item['seller_id'], $qty, (float) $item['price']]
                );

                $this->db->query(
                    "UPDATE products SET stock_quantity = GREATEST(stock_quantity - ?, 0), updated_at = NOW() WHERE id = ?",
                    [$qty, (int) $item['id']]
                );
            }

            $this->db->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function allForUser($userId)
    {
        $orders = $this->db->query(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC",
            [(int) $userId]
        );

        foreach ($orders as &$order) {
            $order['items'] = $this->items($order['id'], $userId);
        }

        return $orders;
    }

    public function items($orderId, $userId)
    {
        return $this->db->query(
            "SELECT oi.*, p.name, p.primary_image, s.shop_name
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id
             JOIN products p ON p.id = oi.product_id
             JOIN sellers s ON s.id = oi.seller_id
             WHERE oi.order_id = ? AND o.user_id = ?
             ORDER BY oi.id",
            [(int) $orderId, (int) $userId]
        );
    }
}
