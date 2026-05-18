<?php

class Product extends Model
{
    public function featured($filters = [])
    {
        $sql = "SELECT p.*, c.name AS category_name, s.shop_name
                FROM products p
                JOIN categories c ON c.id = p.category_id
                JOIN sellers s ON s.id = p.seller_id
                WHERE p.is_available = 1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = (int) $filters['category'];
        }

        if (!empty($filters['q'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR s.shop_name LIKE ?)";
            $term = '%' . $filters['q'] . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY p.created_at DESC";
        return $this->db->query($sql, $params);
    }

    public function find($id)
    {
        return $this->db->one(
            "SELECT p.*, c.name AS category_name, s.shop_name
             FROM products p
             JOIN categories c ON c.id = p.category_id
             JOIN sellers s ON s.id = p.seller_id
             WHERE p.id = ?",
            [(int) $id]
        );
    }

    public function findMany($ids)
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        return $this->db->query(
            "SELECT p.*, c.name AS category_name, s.shop_name
             FROM products p
             JOIN categories c ON c.id = p.category_id
             JOIN sellers s ON s.id = p.seller_id
             WHERE p.id IN ($placeholders)",
            $ids
        );
    }

    public function categories()
    {
        return $this->db->query("SELECT * FROM categories ORDER BY name");
    }
}
