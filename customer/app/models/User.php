<?php

class User extends Model
{
    public function findByEmail($email)
    {
        return $this->db->one("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function find($id)
    {
        return $this->db->one("SELECT * FROM users WHERE id = ?", [(int) $id]);
    }

    public function createCustomer($data)
    {
        return $this->db->insert(
            "INSERT INTO users (name, email, password, phone, role, is_active) VALUES (?, ?, ?, ?, 'customer', 1)",
            [$data['name'], $data['email'], $data['password'], $data['phone']]
        );
    }

    public function updateProfile($id, $data)
    {
        return $this->db->query(
            "UPDATE users SET name = ?, phone = ? WHERE id = ? AND role = 'customer'",
            [$data['name'], $data['phone'], (int) $id]
        );
    }
}
