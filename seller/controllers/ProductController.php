<?php

require_once __DIR__ . '/../models/Product.php';

class ProductController {

    private Product $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    // Guard helper
    private function requireSeller(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Upload image helper — returns path string or null
    private function uploadImage(array $file, string $folder): ?string {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;

        if ($file['error'] !== UPLOAD_ERR_OK)  return null;
        if (!in_array($file['type'], $allowed)) return null;
        if ($file['size'] > $maxSize)           return null;

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $folder . '_' . uniqid() . '.' . $ext;
        $dest     = __DIR__ . '/../uploads/products/' . $filename;

        return move_uploaded_file($file['tmp_name'], $dest)
            ? 'uploads/products/' . $filename
            : null;
    }

    // -------------------------------------------------------
    // INDEX — list all products
    // -------------------------------------------------------
    public function index(): void {
        $this->requireSeller();

        $sellerId = $_SESSION['seller_id'];
        $products = $this->productModel->getAllBySeller($sellerId);
        $success  = $_GET['success'] ?? '';
        $error    = $_GET['error']   ?? '';

        $pageTitle    = 'Products';
        $pageSubtitle = 'Manage your product catalog';

        require_once __DIR__ . '/../views/products/index.php';
    }

    // -------------------------------------------------------
    // CREATE — show form + handle POST
    // -------------------------------------------------------
    public function create(): void {
        $this->requireSeller();

        $sellerId  = $_SESSION['seller_id'];
        $errors    = [];
        $old       = [];
        $categories = $this->productModel->getCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = [
                'name'           => trim($_POST['name']           ?? ''),
                'description'    => trim($_POST['description']    ?? ''),
                'price'          => trim($_POST['price']          ?? ''),
                'stock_quantity' => trim($_POST['stock_quantity'] ?? ''),
                'category_id'   => trim($_POST['category_id']    ?? ''),
            ];

            // Validate
            if (empty($old['name'])) {
                $errors['name'] = 'Product name is required.';
            } elseif (strlen($old['name']) < 3) {
                $errors['name'] = 'Name must be at least 3 characters.';
            }

            if (empty($old['category_id'])) {
                $errors['category_id'] = 'Please select a category.';
            }

           if ($old['price'] === '' || !is_numeric($old['price']) || $old['price'] <= 0) {
                 $errors['price'] = 'Price must be greater than $0.';
            }

            if ($old['stock_quantity'] === '' || !is_numeric($old['stock_quantity']) || $old['stock_quantity'] < 0) {
                $errors['stock_quantity'] = 'Enter a valid stock quantity.';
            }

            if (empty($old['description'])) {
                $errors['description'] = 'Description is required.';
            } elseif (strlen($old['description']) < 10) {
                $errors['description'] = 'Description must be at least 10 characters.';
            }

            // Primary image — required on create
            $primaryImagePath = null;
            if (empty($_FILES['primary_image']['name'])) {
                $errors['primary_image'] = 'Primary image is required.';
            } else {
                $primaryImagePath = $this->uploadImage($_FILES['primary_image'], 'primary');
                if (!$primaryImagePath) {
                    $errors['primary_image'] = 'Invalid image. JPG/PNG/WEBP under 2MB only.';
                }
            }

            if (empty($errors)) {
                $productId = $this->productModel->create([
                    'seller_id'      => $sellerId,
                    'category_id'    => (int)$old['category_id'],
                    'name'           => $old['name'],
                    'description'    => $old['description'],
                    'price'          => (float)$old['price'],
                    'stock_quantity' => (int)$old['stock_quantity'],
                    'primary_image'  => $primaryImagePath,
                ]);

                if ($productId) {
                    // Handle additional images (up to 4)
                    if (!empty($_FILES['additional_images']['name'][0])) {
                        $count = min(count($_FILES['additional_images']['name']), 4);
                        for ($i = 0; $i < $count; $i++) {
                            $singleFile = [
                                'name'     => $_FILES['additional_images']['name'][$i],
                                'type'     => $_FILES['additional_images']['type'][$i],
                                'tmp_name' => $_FILES['additional_images']['tmp_name'][$i],
                                'error'    => $_FILES['additional_images']['error'][$i],
                                'size'     => $_FILES['additional_images']['size'][$i],
                            ];
                            $path = $this->uploadImage($singleFile, 'extra');
                            if ($path) {
                                $this->productModel->saveAdditionalImage($productId, $path);
                            }
                        }
                    }

                    header('Location: index.php?page=products&success=Product+added+successfully');
                    exit;
                } else {
                    $errors['general'] = 'Failed to save product. Please try again.';
                }
            }
        }

        $pageTitle    = 'Add Product';
        $pageSubtitle = 'Add a new product to your catalog';

        require_once __DIR__ . '/../views/products/create.php';
    }

    // -------------------------------------------------------
    // EDIT — show form + handle POST
    // -------------------------------------------------------
    public function edit(): void {
        $this->requireSeller();

        $sellerId   = $_SESSION['seller_id'];
        $productId  = (int)($_GET['id'] ?? 0);
        $errors     = [];
        $old        = [];
        $categories = $this->productModel->getCategories();

        $product = $this->productModel->findByIdAndSeller($productId, $sellerId);
        if (!$product) {
            header('Location: index.php?page=products&error=Product+not+found');
            exit;
        }

        $additionalImages = $this->productModel->getAdditionalImages($productId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = [
                'name'           => trim($_POST['name']           ?? ''),
                'description'    => trim($_POST['description']    ?? ''),
                'price'          => trim($_POST['price']          ?? ''),
                'stock_quantity' => trim($_POST['stock_quantity'] ?? ''),
                'category_id'   => trim($_POST['category_id']    ?? ''),
            ];

            // Validate
            if (empty($old['name'])) {
                $errors['name'] = 'Product name is required.';
            } elseif (strlen($old['name']) < 3) {
                $errors['name'] = 'Name must be at least 3 characters.';
            }

            if (empty($old['category_id'])) {
                $errors['category_id'] = 'Please select a category.';
            }

            if ($old['price'] === '' || !is_numeric($old['price']) || $old['price'] <= 0) {
                $errors['price'] = 'Price must be greater than $0.';
            }

            if ($old['stock_quantity'] === '' || !is_numeric($old['stock_quantity']) || $old['stock_quantity'] < 0) {
                $errors['stock_quantity'] = 'Enter a valid stock quantity.';
            }

            if (empty($old['description'])) {
                $errors['description'] = 'Description is required.';
            } elseif (strlen($old['description']) < 10) {
                $errors['description'] = 'Description must be at least 10 characters.';
            }

            if (empty($errors)) {
                $this->productModel->update($productId, [
                    'seller_id'      => $sellerId,
                    'category_id'    => (int)$old['category_id'],
                    'name'           => $old['name'],
                    'description'    => $old['description'],
                    'price'          => (float)$old['price'],
                    'stock_quantity' => (int)$old['stock_quantity'],
                ]);

                // Replace primary image if new one uploaded
                if (!empty($_FILES['primary_image']['name'])) {
                    $newPath = $this->uploadImage($_FILES['primary_image'], 'primary');
                    if ($newPath) {
                        // Delete old image
                        if (!empty($product['primary_image'])) {
                            $oldFile = __DIR__ . '/../' . $product['primary_image'];
                            if (file_exists($oldFile)) unlink($oldFile);
                        }
                        $this->productModel->updateImage($productId, $newPath);
                    }
                }

                // Additional images
                if (!empty($_FILES['additional_images']['name'][0])) {
                    $existing = count($additionalImages);
                    $slots    = max(0, 4 - $existing);
                    $count    = min(count($_FILES['additional_images']['name']), $slots);

                    for ($i = 0; $i < $count; $i++) {
                        $singleFile = [
                            'name'     => $_FILES['additional_images']['name'][$i],
                            'type'     => $_FILES['additional_images']['type'][$i],
                            'tmp_name' => $_FILES['additional_images']['tmp_name'][$i],
                            'error'    => $_FILES['additional_images']['error'][$i],
                            'size'     => $_FILES['additional_images']['size'][$i],
                        ];
                        $path = $this->uploadImage($singleFile, 'extra');
                        if ($path) {
                            $this->productModel->saveAdditionalImage($productId, $path);
                        }
                    }
                }

                header('Location: index.php?page=products&success=Product+updated+successfully');
                exit;
            }
        }

        $pageTitle    = 'Edit Product';
        $pageSubtitle = 'Update product details';

        require_once __DIR__ . '/../views/products/edit.php';
    }

    // -------------------------------------------------------
    // TOGGLE availability
    // -------------------------------------------------------
    public function toggle(): void {
        $this->requireSeller();

        $sellerId  = $_SESSION['seller_id'];
        $productId = (int)($_GET['id'] ?? 0);

        $this->productModel->toggleAvailability($productId, $sellerId);

        header('Location: index.php?page=products&success=Product+availability+updated');
        exit;
    }

    // -------------------------------------------------------
    // DELETE
    // -------------------------------------------------------
    public function delete(): void {
        $this->requireSeller();

        $sellerId  = $_SESSION['seller_id'];
        $productId = (int)($_GET['id'] ?? 0);

        // Block delete if pending orders exist
        if ($this->productModel->hasPendingOrders($productId)) {
            header('Location: index.php?page=products&error=Cannot+delete+product+with+active+orders');
            exit;
        }

        $this->productModel->delete($productId, $sellerId);

        header('Location: index.php?page=products&success=Product+deleted+successfully');
        exit;
    }
}