<?php

require_once __DIR__ . '/../models/Order.php';

class OrderController {

    private Order $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    // Guard helper
    private function requireSeller(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // -------------------------------------------------------
    // INDEX — list all orders with optional status filter
    // -------------------------------------------------------
    public function index(): void {
        $this->requireSeller();

        $sellerId      = $_SESSION['seller_id'];
        $currentFilter = $_GET['status'] ?? 'all';
        $success       = $_GET['success'] ?? '';
        $error         = $_GET['error']   ?? '';

        // Validate filter value
        $allowed = ['all', 'pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($currentFilter, $allowed)) {
            $currentFilter = 'all';
        }

        $orders = $this->orderModel->getAllBySeller($sellerId, $currentFilter);

        // Get pending count for badge
        $pendingOrders = $this->orderModel->getAllBySeller($sellerId, 'pending');
        $pendingCount  = count($pendingOrders);

        $pageTitle    = 'Orders';
        $pageSubtitle = 'Manage incoming orders for your store';

        require_once __DIR__ . '/../views/orders/index.php';
    }

    // -------------------------------------------------------
    // DETAIL — full order view
    // -------------------------------------------------------
    public function detail(): void {
        $this->requireSeller();

        $sellerId = $_SESSION['seller_id'];
        $orderId  = (int)($_GET['id'] ?? 0);

        if ($orderId <= 0) {
            header('Location: index.php?page=orders&error=Invalid+order');
            exit;
        }

        $order = $this->orderModel->getOrderDetail($orderId, $sellerId);

        if (!$order || empty($order['items'])) {
            header('Location: index.php?page=orders&error=Order+not+found');
            exit;
        }

        $pageTitle    = 'Order #' . str_pad($orderId, 5, '0', STR_PAD_LEFT);
        $pageSubtitle = 'Order details and status management';

        require_once __DIR__ . '/../views/orders/detail.php';
    }

    // -------------------------------------------------------
    // UPDATE STATUS — confirm or ship an order item
    // -------------------------------------------------------
    public function updateStatus(): void {
        $this->requireSeller();

        $sellerId     = $_SESSION['seller_id'];
        $itemId       = (int)($_GET['item_id'] ?? 0);
        $newStatus    = $_GET['status'] ?? '';
        $trackingNote = trim($_POST['tracking_note'] ?? '');

        if ($itemId <= 0 || empty($newStatus)) {
            header('Location: index.php?page=orders&error=Invalid+request');
            exit;
        }

        // Validate new status value
        $validStatuses = ['processing', 'shipped'];
        if (!in_array($newStatus, $validStatuses)) {
            header('Location: index.php?page=orders&error=Invalid+status');
            exit;
        }

        // Get current item — verify ownership
        $item = $this->orderModel->getItem($itemId, $sellerId);
        if (!$item) {
            header('Location: index.php?page=orders&error=Item+not+found');
            exit;
        }

        // Check transition is allowed
        $allowed = $this->orderModel->getAllowedNextStatus($item['status']);
        if (!in_array($newStatus, $allowed)) {
            header('Location: index.php?page=orders&error=Status+transition+not+allowed');
            exit;
        }

        // Tracking note required when shipping
        if ($newStatus === 'shipped' && empty($trackingNote)) {
            $orderId = $item['order_id'];
            header('Location: index.php?page=orders-detail&id=' . $orderId . '&error=Tracking+note+required');
            exit;
        }

        $ok = $this->orderModel->updateItemStatus($itemId, $sellerId, $newStatus, $trackingNote);

        $orderId = $item['order_id'];

        if ($ok) {
            $msg = $newStatus === 'processing' ? 'Order+confirmed' : 'Order+marked+as+shipped';
            header('Location: index.php?page=orders-detail&id=' . $orderId . '&success=' . $msg);
        } else {
            header('Location: index.php?page=orders-detail&id=' . $orderId . '&error=Failed+to+update+status');
        }
        exit;
    }
}