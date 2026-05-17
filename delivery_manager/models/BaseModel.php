<?php
if (!defined('DB_HOST')) require_once __DIR__ . '/../config/db.php';

class BaseModel {
    protected $conn;

    public function __construct() {
        $this->conn = getDB();
    }

    public function __destruct() {
        $this->conn->close();
    }
}