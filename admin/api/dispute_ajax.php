<?php
session_start();
require_once '../config/db.php';

// Only admin allowed
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'get_status') {
    $id   = (int)$_POST['id'];
    $stmt = $conn->prepare("SELECT id, status, admin_note FROM disputes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $row]);
    exit();
}

if ($action === 'resolve') {
    $id         = (int)$_POST['id'];
    $admin_note = trim($_POST['admin_note']);

    if (empty($admin_note)) {
        echo json_encode(['success' => false, 'message' => 'Note required']);
        exit();
    }

    $stmt = $conn->prepare(
        "UPDATE disputes SET status = 'resolved', admin_note = ? WHERE id = ?"
    );
    $stmt->bind_param("si", $admin_note, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dispute resolved']);
    } else {
        echo json_encode(['success' => false, 'message' => 'DB error']);
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>