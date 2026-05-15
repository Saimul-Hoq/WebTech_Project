<?php

require_once __DIR__ . '/../models/Return.php';

class ReturnController {

    private ReturnRequest $returnModel;

    public function __construct() {
        $this->returnModel = new ReturnRequest();
    }

    // Guard helper
    private function requireSeller(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // -------------------------------------------------------
    // INDEX — list all return requests
    // -------------------------------------------------------
    public function index(): void {
        $this->requireSeller();

        $sellerId     = $_SESSION['seller_id'];
        $returns      = $this->returnModel->getAllBySeller($sellerId);
        $pendingCount = $this->returnModel->countPending($sellerId);
        $success      = $_GET['success'] ?? '';
        $error        = $_GET['error']   ?? '';

        $pageTitle    = 'Return Requests';
        $pageSubtitle = 'Manage customer return requests';

        require_once __DIR__ . '/../views/returns/index.php';
    }

    // -------------------------------------------------------
    // ACTION — approve or reject a return request
    // -------------------------------------------------------
    public function action(): void {
        $this->requireSeller();

        $sellerId  = $_SESSION['seller_id'];
        $returnId  = (int)($_GET['id'] ?? 0);

        if ($returnId <= 0) {
            header('Location: index.php?page=returns&error=Invalid+request');
            exit;
        }

        // Only accept POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=returns&error=Invalid+request+method');
            exit;
        }

        // Verify ownership
        $return = $this->returnModel->findByIdAndSeller($returnId, $sellerId);
        if (!$return) {
            header('Location: index.php?page=returns&error=Return+request+not+found');
            exit;
        }

        // Only act on pending requests
        if ($return['status'] !== 'pending') {
            header('Location: index.php?page=returns&error=This+request+has+already+been+resolved');
            exit;
        }

        // Validate decision
        $decision = $_POST['decision'] ?? '';
        if (!in_array($decision, ['approved', 'rejected'])) {
            header('Location: index.php?page=returns&error=Invalid+decision');
            exit;
        }

        // Validate response message
        $sellerResponse = trim($_POST['seller_response'] ?? '');
        if (empty($sellerResponse)) {
            header('Location: index.php?page=returns&error=Please+provide+a+response+reason');
            exit;
        }

        if (strlen($sellerResponse) < 5) {
            header('Location: index.php?page=returns&error=Response+must+be+at+least+5+characters');
            exit;
        }

        // Save decision
        $ok = $this->returnModel->respond($returnId, $decision, $sellerResponse);

        if ($ok) {
            $msg = $decision === 'approved'
                ? 'Return+request+approved+successfully'
                : 'Return+request+rejected';
            header('Location: index.php?page=returns&success=' . $msg);
        } else {
            header('Location: index.php?page=returns&error=Failed+to+update+return+request');
        }
        exit;
    }
}