<?php

require_once __DIR__ . '/../models/Review.php';

class ReviewController {

    private Review $reviewModel;

    public function __construct() {
        $this->reviewModel = new Review();
    }

    // Guard helper
    private function requireSeller(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // -------------------------------------------------------
    // INDEX — list all reviews
    // -------------------------------------------------------
    public function index(): void {
        $this->requireSeller();

        $sellerId      = $_SESSION['seller_id'];
        $reviews       = $this->reviewModel->getAllBySeller($sellerId);
        $avgRating     = $this->reviewModel->getAverageRating($sellerId);
        $unrepliedCount = $this->reviewModel->countUnreplied($sellerId);
        $success       = $_GET['success'] ?? '';
        $error         = $_GET['error']   ?? '';

        $pageTitle    = 'Reviews';
        $pageSubtitle = 'View and reply to customer reviews';

        require_once __DIR__ . '/../views/reviews/index.php';
    }

    // -------------------------------------------------------
    // REPLY — save seller reply to a review
    // -------------------------------------------------------
    public function reply(): void {
        $this->requireSeller();

        $sellerId = $_SESSION['seller_id'];
        $reviewId = (int)($_GET['id'] ?? 0);

        if ($reviewId <= 0) {
            header('Location: index.php?page=reviews&error=Invalid+review');
            exit;
        }

        // Only accept POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=reviews&error=Invalid+request+method');
            exit;
        }

        // Verify review belongs to seller's product
        $review = $this->reviewModel->findByIdAndSeller($reviewId, $sellerId);
        if (!$review) {
            header('Location: index.php?page=reviews&error=Review+not+found');
            exit;
        }

        // Validate reply
        $reply = trim($_POST['reply'] ?? '');

        if (empty($reply)) {
            header('Location: index.php?page=reviews&error=Reply+cannot+be+empty');
            exit;
        }

        if (strlen($reply) < 5) {
            header('Location: index.php?page=reviews&error=Reply+must+be+at+least+5+characters');
            exit;
        }

        if (strlen($reply) > 1000) {
            header('Location: index.php?page=reviews&error=Reply+must+be+under+1000+characters');
            exit;
        }

        $ok = $this->reviewModel->saveReply($reviewId, $reply);

        if ($ok) {
            header('Location: index.php?page=reviews&success=Reply+saved+successfully');
        } else {
            header('Location: index.php?page=reviews&error=Failed+to+save+reply');
        }
        exit;
    }
}