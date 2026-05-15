<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce_marketplace');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die(json_encode([
            'error' => 'Database connection failed: ' . $conn->connect_error
        ]));
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}