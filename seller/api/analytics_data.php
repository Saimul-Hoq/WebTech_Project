<?php
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

// RBAC check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Only accept GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$sellerId = $_SESSION['seller_id'];
$db       = getDB();

// Build last 7 days date range
$days   = [];
$today  = new DateTime();
for ($i = 6; $i >= 0; $i--) {
    $d      = clone $today;
    $d->modify("-{$i} days");
    $days[] = $d->format('Y-m-d');
}

// Fetch revenue per day
$stmt = $db->prepare(
    "SELECT DATE(o.created_at) AS day,
            COALESCE(SUM(oi.price * oi.quantity), 0) AS revenue
     FROM order_items oi
     JOIN orders o ON o.id = oi.order_id
     WHERE oi.seller_id = ? AND oi.status = 'delivered'
     AND o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
     GROUP BY DATE(o.created_at)
     ORDER BY day ASC"
);
$stmt->bind_param("i", $sellerId);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Map results to day keys
$revenueMap = [];
foreach ($rows as $row) {
    $revenueMap[$row['day']] = (float)$row['revenue'];
}

// Build labels and values — fill 0 for days with no sales
$labels = [];
$values = [];
foreach ($days as $day) {
    $labels[] = date('M j', strtotime($day));
    $values[] = $revenueMap[$day] ?? 0;
}

echo json_encode([
    'success' => true,
    'labels'  => $labels,
    'values'  => $values,
    'period'  => '7 days',
]);