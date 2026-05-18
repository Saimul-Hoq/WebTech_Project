<?php

function esc($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url($path = '')
{
    return ROOT . ltrim($path, '/');
}

function project_url($path = '')
{
    return PROJECT_ROOT . ltrim($path, '/');
}

function redirect($path = '')
{
    header('Location: ' . url($path));
    exit;
}

function flash($key, $message = null)
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    if (!empty($_SESSION['flash'][$key])) {
        $value = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $value;
    }

    return null;
}

function old($key, $default = '')
{
    return esc($_SESSION['old'][$key] ?? $default);
}

function set_old($data)
{
    $_SESSION['old'] = $data;
}

function clear_old()
{
    unset($_SESSION['old']);
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in()
{
    return !empty($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'customer';
}

function require_customer()
{
    if (!is_logged_in()) {
        flash('error', 'Please login as a customer first.');
        redirect('auth/login');
    }
}

function money($amount)
{
    return '৳' . number_format((float) $amount, 2);
}

function cart_count()
{
    return array_sum(array_map('intval', $_SESSION['cart'] ?? []));
}

function product_image($path)
{
    $path = trim((string) $path);

    if ($path !== '') {
        $customerFile = dirname(__DIR__, 2) . '/public/' . $path;
        $sellerFile = dirname(__DIR__, 3) . '/seller/' . $path;

        if (is_file($customerFile)) {
            return url($path);
        }

        if (is_file($sellerFile)) {
            return project_url('seller/' . $path);
        }

        if (preg_match('#^https?://#', $path)) {
            return $path;
        }
    }

    return url('assets/images/default-product.svg');
}

function profile_image($path)
{
    $path = trim((string) $path);
    if ($path !== '' && is_file(dirname(__DIR__, 2) . '/public/' . $path)) {
        return url($path);
    }

    return url('assets/images/default-avatar.svg');
}
