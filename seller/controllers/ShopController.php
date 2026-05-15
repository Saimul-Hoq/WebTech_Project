<?php

require_once __DIR__ . '/../models/Seller.php';

class ShopController {

    private Seller $sellerModel;

    public function __construct() {
        $this->sellerModel = new Seller();
    }

    public function index(): void {
        // RBAC
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            header('Location: index.php?page=login');
            exit;
        }

        $userId   = $_SESSION['user_id'];
        $errors   = [];
        $old      = [];
        $success  = '';

        // Load current seller data
        $seller = $this->sellerModel->findByUserId($userId);

        if (!$seller) {
            header('Location: index.php?page=logout');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect input
            $old = [
                'shop_name'        => trim($_POST['shop_name']        ?? ''),
                'shop_description' => trim($_POST['shop_description'] ?? ''),
                'address'          => trim($_POST['address']          ?? ''),
            ];

            // --- Validate ---
            if (empty($old['shop_name'])) {
                $errors['shop_name'] = 'Shop name is required.';
            } elseif (strlen($old['shop_name']) < 3) {
                $errors['shop_name'] = 'Shop name must be at least 3 characters.';
            }

            if (empty($old['shop_description'])) {
                $errors['shop_description'] = 'Description is required.';
            } elseif (strlen($old['shop_description']) < 10) {
                $errors['shop_description'] = 'Description must be at least 10 characters.';
            }

            if (empty($old['address'])) {
                $errors['address'] = 'Address is required.';
            }

            // --- Handle logo upload ---
            $newLogo = null;
            if (!empty($_FILES['shop_logo']['name'])) {
                $allowed  = ['image/jpeg', 'image/png', 'image/webp'];
                $maxSize  = 2 * 1024 * 1024;
                $fileType = $_FILES['shop_logo']['type'];
                $fileSize = $_FILES['shop_logo']['size'];
                $tmpPath  = $_FILES['shop_logo']['tmp_name'];

                if (!in_array($fileType, $allowed)) {
                    $errors['logo'] = 'Logo must be JPG, PNG, or WEBP.';
                } elseif ($fileSize > $maxSize) {
                    $errors['logo'] = 'Logo must be under 2MB.';
                } else {
                    $ext      = pathinfo($_FILES['shop_logo']['name'], PATHINFO_EXTENSION);
                    $filename = 'logo_' . uniqid() . '.' . $ext;
                    $dest     = __DIR__ . '/../uploads/logos/' . $filename;

                    if (move_uploaded_file($tmpPath, $dest)) {
                        // Delete old logo if exists
                        if (!empty($seller['logo'])) {
                            $oldFile = __DIR__ . '/../' . $seller['logo'];
                            if (file_exists($oldFile)) {
                                unlink($oldFile);
                            }
                        }
                        $newLogo = 'uploads/logos/' . $filename;
                    } else {
                        $errors['logo'] = 'Failed to upload logo. Try again.';
                    }
                }
            }

            // --- Save if no errors ---
            if (empty($errors)) {
                $updated = $this->sellerModel->updateProfile($userId, [
                    'shop_name'        => $old['shop_name'],
                    'shop_description' => $old['shop_description'],
                    'address'          => $old['address'],
                ]);

                // Update logo separately if new one uploaded
                if ($newLogo) {
                    $this->sellerModel->updateLogo($userId, $newLogo);
                }

                if ($updated) {
                    // Update session shop name
                    $_SESSION['shop_name'] = $old['shop_name'];

                    $success = '✅ Shop profile updated successfully.';
                    $old     = [];

                    // Reload fresh seller data
                    $seller = $this->sellerModel->findByUserId($userId);
                } else {
                    $errors['general'] = 'Failed to update profile. Please try again.';
                }
            }
        }

        $pageTitle    = 'Shop Profile';
        $pageSubtitle = 'Manage your store information';

        require_once __DIR__ . '/../views/shop/profile.php';
    }
}