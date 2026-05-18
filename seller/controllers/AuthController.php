<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Seller.php';

class AuthController {

    private User $userModel;
    private Seller $sellerModel;

    public function __construct() {
        $this->userModel   = new User();
        $this->sellerModel = new Seller();
    }

    // -------------------------------------------------------
    // REGISTER
    // -------------------------------------------------------
    public function register(): void {
        // Already logged in — go to dashboard
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'seller') {
            header('Location: index.php?page=dashboard');
            exit;
        }

        $errors  = [];
        $old     = [];
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect raw input
            $old = [
                'name'             => trim($_POST['name']             ?? ''),
                'email'            => trim($_POST['email']            ?? ''),
                'phone'            => trim($_POST['phone']            ?? ''),
                'shop_name'        => trim($_POST['shop_name']        ?? ''),
                'shop_description' => trim($_POST['shop_description'] ?? ''),
                'address'          => trim($_POST['address']          ?? ''),
            ];
            $password        = $_POST['password']         ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // --- Validate ---
            if (empty($old['name'])) {
                $errors['name'] = 'Full name is required.';
            }

            if (empty($old['email'])) {
                $errors['email'] = 'Email is required.';
            } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Enter a valid email address.';
            } elseif ($this->userModel->emailExists($old['email'])) {
                $errors['email'] = 'This email is already registered.';
            }

            if (empty($old['phone'])) {
                $errors['phone'] = 'Phone number is required.';
            } elseif (!preg_match('/^\+?[0-9\s\-]{7,15}$/', $old['phone'])) {
                $errors['phone'] = 'Enter a valid phone number.';
            }

            if (strlen($password) < 8) {
                $errors['password'] = 'Password must be at least 8 characters.';
            }

            if ($password !== $confirmPassword) {
                $errors['confirm_password'] = 'Passwords do not match.';
            }

            if (empty($old['shop_name'])) {
                $errors['shop_name'] = 'Shop name is required.';
            }

            if (empty($old['shop_description'])) {
                $errors['shop_description'] = 'Shop description is required.';
            }

            if (empty($old['address'])) {
                $errors['address'] = 'Shop address is required.';
            }

            // --- Handle logo upload ---
            $logoPath = null;
            if (!empty($_FILES['shop_logo']['name'])) {
                $allowed   = ['image/jpeg', 'image/png', 'image/webp'];
                $maxSize   = 2 * 1024 * 1024; // 2MB
                $fileType  = $_FILES['shop_logo']['type'];
                $fileSize  = $_FILES['shop_logo']['size'];
                $tmpPath   = $_FILES['shop_logo']['tmp_name'];

                if (!in_array($fileType, $allowed)) {
                    $errors['logo'] = 'Logo must be JPG, PNG, or WEBP.';
                } elseif ($fileSize > $maxSize) {
                    $errors['logo'] = 'Logo must be under 2MB.';
                } else {
                    $ext      = pathinfo($_FILES['shop_logo']['name'], PATHINFO_EXTENSION);
                    $filename = 'logo_' . uniqid() . '.' . $ext;
                    $dest     = __DIR__ . '/../uploads/logos/' . $filename;
                    if (move_uploaded_file($tmpPath, $dest)) {
                        $logoPath = 'uploads/logos/' . $filename;
                    } else {
                        $errors['logo'] = 'Failed to upload logo. Try again.';
                    }
                }
            }

            // --- If no errors, save to DB ---
            if (empty($errors)) {
                $userId = $this->userModel->create([
                    'name'     => $old['name'],
                    'email'    => $old['email'],
                    'password' => $password,
                    'phone'    => $old['phone'],
                ]);

                if ($userId) {
                    $sellerId = $this->sellerModel->create([
                        'user_id'          => $userId,
                        'shop_name'        => $old['shop_name'],
                        'shop_description' => $old['shop_description'],
                        'address'          => $old['address'],
                        'logo'             => $logoPath,
                    ]);

                    if ($sellerId) {
                        $success = '✅ Registration successful! Your account is pending admin approval. You can log in once approved.';
                        $old     = []; // clear form
                    } else {
                        $errors['general'] = 'Failed to create shop profile. Please try again.';
                    }
                } else {
                    $errors['general'] = 'Failed to create account. Please try again.';
                }
            }
        }

        // Load view
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // -------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------
    public function login(): void {
        // Already logged in
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'seller') {
            header('Location: index.php?page=dashboard');
            exit;
        }

        $errors  = [];
        $old     = [];
        $success = '';

        // Show success message after registration
        if (isset($_GET['registered'])) {
            $success = '✅ Account created! Please wait for admin approval before logging in.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['email'] = trim($_POST['email'] ?? '');
            $password     = $_POST['password'] ?? '';

            // --- Validate ---
            if (empty($old['email'])) {
                $errors['email'] = 'Email is required.';
            } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Enter a valid email address.';
            }

            if (empty($password)) {
                $errors['password'] = 'Password is required.';
            }

            // --- Attempt login if no field errors ---
            if (empty($errors)) {
                $user = $this->userModel->findByEmail($old['email']);

                if (!$user) {
                    $errors['general'] = 'No account found with that email.';
                } elseif ($user['role'] !== 'seller') {
                    $errors['general'] = 'This portal is for sellers only.';
                } elseif ($password !== $user['password']) {
                    $errors['general'] = 'Incorrect password.';
                } else {
                    // Check seller approval
                    $seller = $this->sellerModel->findByUserId($user['id']);

                    if (!$seller) {
                        $errors['general'] = 'Seller profile not found. Contact support.';
                    } elseif ($seller['status'] !== 'approved') {
                        $errors['general'] = '⏳ Your account is pending admin approval. Please wait.';
                    } elseif ($user['is_active'] == 0) {
                        $errors['general'] = '🚫 Your account has been deactivated. Contact support.';
                    } else {
                        // ✅ All good — set session
                        session_regenerate_id(true);

                        $_SESSION['user_id']   = $user['id'];
                        $_SESSION['name']      = $user['name'];
                        $_SESSION['email']     = $user['email'];
                        $_SESSION['role']      = $user['role'];
                        $_SESSION['seller_id'] = $seller['id'];
                        $_SESSION['shop_name'] = $seller['shop_name'];

                        header('Location: index.php?page=dashboard');
                        exit;
                    }
                }
            }
        }

        // Load view
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // -------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------
    public function logout(): void {
        session_unset();
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}