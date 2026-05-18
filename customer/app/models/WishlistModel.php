<?php

class WishlistModel extends Model
{
    public function allForUser($userId)
    {
        return $this->db->query(
            "SELECT w.*, p.name, p.price, p.primary_image, p.stock_quantity, p.is_available, s.shop_name
             FROM wishlists w
             JOIN products p ON p.id = w.product_id
             JOIN sellers s ON s.id = p.seller_id
             WHERE w.user_id = ?
             ORDER BY w.added_at DESC",
            [(int) $userId]
        );
    }

    public function add($userId, $productId)
    {
        $exists = $this->db->one(
            "SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?",
            [(int) $userId, (int) $productId]
        );

        if ($exists) {
            return false;
        }

        $this->db->insert(
            "INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)",
            [(int) $userId, (int) $productId]
        );

        return true;
    }

    public function remove($userId, $productId)
    {
        return $this->db->query(
            "DELETE FROM wishlists WHERE user_id = ? AND product_id = ?",
            [(int) $userId, (int) $productId]
        );
    }
}
