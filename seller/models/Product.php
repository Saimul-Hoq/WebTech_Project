<?php

class Product {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Get all products for a seller
    public function getAllBySeller(int $sellerId): array {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.seller_id = ?
             ORDER BY p.created_at DESC"
        );
        $stmt->bind_param("i", $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    // Get single product — verify it belongs to seller
    public function findByIdAndSeller(int $productId, int $sellerId): ?array {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.id = ? AND p.seller_id = ? LIMIT 1"
        );
        $stmt->bind_param("ii", $productId, $sellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ?: null;
    }

    // Create product — returns new product ID or false
    public function create(array $data): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO products
                (seller_id, category_id, name, description, price, stock_quantity,
                 primary_image, is_available, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())"
        );
        $stmt->bind_param(
            "iissdis",
            $data['seller_id'],
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock_quantity'],
            $data['primary_image']
        );
        $ok    = $stmt->execute();
        $newId = $this->db->insert_id;
        $stmt->close();
        return $ok ? $newId : false;
    }

    // Update product
    public function update(int $productId, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE products
             SET category_id = ?, name = ?, description = ?,
                 price = ?, stock_quantity = ?, updated_at = NOW()
             WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param(
            "issdiii",
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock_quantity'],
            $productId,
            $data['seller_id']
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Update primary image
    public function updateImage(int $productId, string $imagePath): bool {
        $stmt = $this->db->prepare(
            "UPDATE products SET primary_image = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $imagePath, $productId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Toggle availability
    public function toggleAvailability(int $productId, int $sellerId): bool {
        $stmt = $this->db->prepare(
            "UPDATE products
             SET is_available = IF(is_available = 1, 0, 1), updated_at = NOW()
             WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param("ii", $productId, $sellerId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Check if product has pending orders — block delete if true
    public function hasPendingOrders(int $productId): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS cnt
             FROM order_items
             WHERE product_id = ? AND status IN ('pending','confirmed','processing','shipped')"
        );
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $cnt = $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();
        return $cnt > 0;
    }

    // Delete product
    public function delete(int $productId, int $sellerId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM products WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param("ii", $productId, $sellerId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Update stock quantity only
    public function updateStock(int $productId, int $sellerId, int $quantity): bool {
        $stmt = $this->db->prepare(
            "UPDATE products
             SET stock_quantity = ?, updated_at = NOW()
             WHERE id = ? AND seller_id = ?"
        );
        $stmt->bind_param("iii", $quantity, $productId, $sellerId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get low stock products below threshold
    public function getLowStock(int $sellerId, int $threshold = 10): array {
        $stmt = $this->db->prepare(
            "SELECT id, name, stock_quantity
             FROM products
             WHERE seller_id = ? AND stock_quantity < ? AND is_available = 1
             ORDER BY stock_quantity ASC"
        );
        $stmt->bind_param("ii", $sellerId, $threshold);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    // Get all platform categories for dropdown
    public function getCategories(): array {
        $result = $this->db->query("SELECT id, name FROM categories ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Save additional product images
    public function saveAdditionalImage(int $productId, string $imagePath): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO product_images (product_id, image_path, created_at)
             VALUES (?, ?, NOW())"
        );
        $stmt->bind_param("is", $productId, $imagePath);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get additional images for a product
    public function getAdditionalImages(int $productId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM product_images WHERE product_id = ? ORDER BY id ASC"
        );
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }
}