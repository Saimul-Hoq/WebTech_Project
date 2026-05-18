<?php

class Database
{
    private mysqli $conn;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->conn->set_charset('utf8mb4');
    }

    public function query($query, $data = [])
    {
        $stmt = $this->conn->prepare($query);

        if (!empty($data)) {
            $types = '';
            foreach ($data as $value) {
                if (is_int($value)) {
                    $types .= 'i';
                } elseif (is_float($value)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }

            $stmt->bind_param($types, ...$data);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return $stmt->affected_rows;
    }

    public function one($query, $data = [])
    {
        $rows = $this->query($query, $data);
        return $rows[0] ?? null;
    }

    public function insert($query, $data = [])
    {
        $this->query($query, $data);
        return $this->conn->insert_id;
    }

    public function begin()
    {
        $this->conn->begin_transaction();
    }

    public function commit()
    {
        $this->conn->commit();
    }

    public function rollback()
    {
        $this->conn->rollback();
    }
}
