<?php

class Auth extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            set_old(['email' => $email]);

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if (!$user || $user['password'] !== $password) {
                flash('error', 'Invalid email or password.');
                redirect('auth/login');
            }

            if ($user['role'] !== 'customer') {
                flash('error', 'Only customers can login from this page.');
                redirect('auth/login');
            }

            if ((int) $user['is_active'] !== 1) {
                flash('error', 'Your account is inactive. Please contact support.');
                redirect('auth/login');
            }

            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
                'profile_pic' => $user['profile_pic'],
            ];

            clear_old();
            flash('success', 'Welcome back, ' . $user['name'] . '.');
            redirect('');
        }

        $this->view('auth/login', ['title' => 'Customer Login']);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
            ];
            set_old($data);

            if ($data['name'] === '' || $data['email'] === '' || $data['password'] === '') {
                flash('error', 'Name, email, and password are required.');
                redirect('auth/register');
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Please enter a valid email address.');
                redirect('auth/register');
            }

            if ($data['password'] !== $data['confirm_password']) {
                flash('error', 'Passwords do not match.');
                redirect('auth/register');
            }

            $userModel = $this->model('User');
            if ($userModel->findByEmail($data['email'])) {
                flash('error', 'An account already exists with this email.');
                redirect('auth/register');
            }

            $userId = $userModel->createCustomer($data);
            $user = $userModel->find($userId);
            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
                'profile_pic' => $user['profile_pic'],
            ];

            clear_old();
            flash('success', 'Your customer account is ready.');
            redirect('');
        }

        $this->view('auth/register', ['title' => 'Create Account']);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        flash('success', 'You have been logged out.');
        redirect('');
    }
}
