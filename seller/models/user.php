<?php

class User {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Find user by email
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare(
            "SELECT id, name, email, password, role, phone, is_active
             FROM users
             WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    // Find user by ID
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT id, name, email, role, phone, is_active
             FROM users
             WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    // Create new user — returns new user ID or false
    public function create(array $data): int|false {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, phone, role, is_active, created_at)
             VALUES (?, ?, ?, ?, 'seller', 0, NOW())"
        );
        $stmt->bind_param(
            "ssss",
            $data['name'],
            $data['email'],
            $hashedPassword,
            $data['phone']
        );

        $ok = $stmt->execute();
        $newId = $this->db->insert_id;
        $stmt->close();

        return $ok ? $newId : false;
    }

    // Check if email already exists
    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Verify password
    public function verifyPassword(string $plain, string $hashed): bool {
        return password_verify($plain, $hashed);
    }
}