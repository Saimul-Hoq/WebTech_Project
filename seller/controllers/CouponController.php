<?php

require_once __DIR__ . '/../models/Coupon.php';

class CouponController {

    private Coupon $couponModel;

    public function __construct() {
        $this->couponModel = new Coupon();
    }

    // Guard helper
    private function requireSeller(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // -------------------------------------------------------
    // INDEX — list all coupons
    // -------------------------------------------------------
    public function index(): void {
        $this->requireSeller();

        $sellerId = $_SESSION['seller_id'];
        $coupons  = $this->couponModel->getAllBySeller($sellerId);
        $success  = $_GET['success'] ?? '';
        $error    = $_GET['error']   ?? '';

        // Get usage count for each coupon
        $usageCounts = [];
        foreach ($coupons as $c) {
            $usageCounts[$c['id']] = $this->couponModel->getUsageCount($c['id']);
        }

        $pageTitle    = 'Coupons';
        $pageSubtitle = 'Manage your promotional codes';

        require_once __DIR__ . '/../views/coupons/index.php';
    }

    // -------------------------------------------------------
    // CREATE — show form + handle POST
    // -------------------------------------------------------
    public function create(): void {
        $this->requireSeller();

        $sellerId = $_SESSION['seller_id'];
        $errors   = [];
        $old      = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = [
                'code'                => strtoupper(trim($_POST['code']                ?? '')),
                'discount_percentage' => trim($_POST['discount_percentage'] ?? ''),
                'max_uses'            => trim($_POST['max_uses']            ?? ''),
                'expires_at'          => trim($_POST['expires_at']          ?? ''),
            ];

            // --- Validate ---
            if (empty($old['code'])) {
                $errors['code'] = 'Coupon code is required.';
            } elseif (!preg_match('/^[A-Z0-9]+$/', $old['code'])) {
                $errors['code'] = 'Code must contain letters and numbers only. No spaces.';
            } elseif (strlen($old['code']) < 3 || strlen($old['code']) > 20) {
                $errors['code'] = 'Code must be between 3 and 20 characters.';
            } elseif ($this->couponModel->codeExists($old['code'], $sellerId)) {
                $errors['code'] = 'This coupon code already exists. Use a different one.';
            }

            if ($old['discount_percentage'] === '' || !is_numeric($old['discount_percentage'])) {
                $errors['discount_percentage'] = 'Discount percentage is required.';
            } elseif ($old['discount_percentage'] < 1 || $old['discount_percentage'] > 100) {
                $errors['discount_percentage'] = 'Discount must be between 1% and 100%.';
            }

            if ($old['max_uses'] === '' || !is_numeric($old['max_uses'])) {
                $errors['max_uses'] = 'Maximum uses is required.';
            } elseif ((int)$old['max_uses'] < 1) {
                $errors['max_uses'] = 'Maximum uses must be at least 1.';
            }

            if (empty($old['expires_at'])) {
                $errors['expires_at'] = 'Expiry date is required.';
            } elseif (strtotime($old['expires_at']) <= time()) {
                $errors['expires_at'] = 'Expiry date must be in the future.';
            }

            // --- Save if no errors ---
            if (empty($errors)) {
                $id = $this->couponModel->create([
                    'seller_id'           => $sellerId,
                    'code'                => $old['code'],
                    'discount_percentage' => (float)$old['discount_percentage'],
                    'max_uses'            => (int)$old['max_uses'],
                    'expires_at'          => $old['expires_at'],
                ]);

                if ($id) {
                    header('Location: index.php?page=coupons&success=Coupon+created+successfully');
                    exit;
                } else {
                    $errors['general'] = 'Failed to create coupon. Please try again.';
                }
            }
        }

        $pageTitle    = 'Create Coupon';
        $pageSubtitle = 'Add a new promotional code';

        require_once __DIR__ . '/../views/coupons/create.php';
    }

    // -------------------------------------------------------
    // TOGGLE active/inactive
    // -------------------------------------------------------
    public function toggle(): void {
        $this->requireSeller();

        $sellerId = $_SESSION['seller_id'];
        $couponId = (int)($_GET['id'] ?? 0);

        if ($couponId <= 0) {
            header('Location: index.php?page=coupons&error=Invalid+coupon');
            exit;
        }

        // Verify ownership
        $coupon = $this->couponModel->findByIdAndSeller($couponId, $sellerId);
        if (!$coupon) {
            header('Location: index.php?page=coupons&error=Coupon+not+found');
            exit;
        }

        $this->couponModel->toggle($couponId, $sellerId);

        $msg = $coupon['is_active'] ? 'Coupon+deactivated' : 'Coupon+activated';
        header('Location: index.php?page=coupons&success=' . $msg);
        exit;
    }

    // -------------------------------------------------------
    // DELETE
    // -------------------------------------------------------
    public function delete(): void {
        $this->requireSeller();

        $sellerId = $_SESSION['seller_id'];
        $couponId = (int)($_GET['id'] ?? 0);

        if ($couponId <= 0) {
            header('Location: index.php?page=coupons&error=Invalid+coupon');
            exit;
        }

        // Verify ownership
        $coupon = $this->couponModel->findByIdAndSeller($couponId, $sellerId);
        if (!$coupon) {
            header('Location: index.php?page=coupons&error=Coupon+not+found');
            exit;
        }

        $this->couponModel->delete($couponId, $sellerId);

        header('Location: index.php?page=coupons&success=Coupon+deleted+successfully');
        exit;
    }
}