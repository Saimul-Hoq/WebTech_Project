<?php
// Guard: only sellers allowed
function requireSeller() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
        header('Location: index.php?page=login');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Seller Dashboard' ?> | ShopHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #1a1a2e;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid #2d2d4e;
        }

        .sidebar-brand h2 {
            color: #7c3aed;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .sidebar-brand p {
            color: #6b7280;
            font-size: 12px;
            margin-top: 4px;
        }

        .sidebar-nav {
            padding: 16px 0;
            flex: 1;
        }

        .nav-section {
            padding: 8px 20px 4px;
            font-size: 10px;
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: #2d2d4e;
            color: #fff;
            border-left-color: #7c3aed;
        }

        .nav-link.active {
            background: #2d2d4e;
            color: #fff;
            border-left-color: #7c3aed;
        }

        .nav-link .icon { font-size: 18px; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid #2d2d4e;
        }

        .seller-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .seller-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #7c3aed;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 14px;
        }

        .seller-name { color: #fff; font-size: 13px; font-weight: 600; }
        .seller-role { color: #6b7280; font-size: 11px; }

        .logout-btn {
            display: block;
            width: 100%;
            padding: 9px;
            background: #2d2d4e;
            color: #ef4444;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s;
        }

        .logout-btn:hover { background: #3d1a1a; }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            padding: 16px 28px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title { font-size: 20px; font-weight: 700; color: #1a1a2e; }
        .topbar-sub { font-size: 13px; color: #6b7280; margin-top: 2px; }

        .page-body { padding: 28px; flex: 1; }

        /* CARDS */
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary { background: #7c3aed; color: #fff; }
        .btn-primary:hover { background: #6d28d9; }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-warning:hover { background: #d97706; }
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        /* TABLES */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th {
            background: #f9fafb;
            padding: 11px 14px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: 13px 14px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        tr:hover td { background: #fafafa; }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info    { background: #dbeafe; color: #1e40af; }
        .badge-gray    { background: #f3f4f6; color: #6b7280; }
        .badge-purple  { background: #ede9fe; color: #5b21b6; }

        /* FORMS */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #1a1a2e;
            background: #fff;
            transition: border-color 0.2s;
            outline: none;
        }
        .form-control:focus { border-color: #7c3aed; }
        .form-error { color: #ef4444; font-size: 12px; margin-top: 4px; }

        /* ALERTS */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
            font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .alert-danger  { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .alert-warning { background: #fef3c7; color: #92400e; border-left: 4px solid #f59e0b; }

        /* GRID */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }

        /* STAT CARDS */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .stat-label { font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase; }
        .stat-value { font-size: 28px; font-weight: 800; color: #1a1a2e; margin: 6px 0 4px; }
        .stat-sub { font-size: 12px; color: #6b7280; }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #9ca3af;
        }
        .empty-state .icon { font-size: 48px; margin-bottom: 12px; }
        .empty-state p { font-size: 15px; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <h2>🛍️ ShopHub</h2>
        <p>Seller Portal</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="index.php?page=dashboard" class="nav-link <?= ($page ?? '') === 'dashboard' ? 'active' : '' ?>">
            <span class="icon">📊</span> Dashboard
        </a>
        <a href="index.php?page=shop-profile" class="nav-link <?= ($page ?? '') === 'shop-profile' ? 'active' : '' ?>">
            <span class="icon">🏪</span> Shop Profile
        </a>

        <div class="nav-section">Catalog</div>
        <a href="index.php?page=products" class="nav-link <?= ($page ?? '') === 'products' ? 'active' : '' ?>">
            <span class="icon">📦</span> Products
        </a>
        <a href="index.php?page=coupons" class="nav-link <?= ($page ?? '') === 'coupons' ? 'active' : '' ?>">
            <span class="icon">🏷️</span> Coupons
        </a>

        <div class="nav-section">Sales</div>
        <a href="index.php?page=orders" class="nav-link <?= ($page ?? '') === 'orders' ? 'active' : '' ?>">
            <span class="icon">🛒</span> Orders
        </a>
        <a href="index.php?page=returns" class="nav-link <?= ($page ?? '') === 'returns' ? 'active' : '' ?>">
            <span class="icon">↩️</span> Returns
        </a>

        <div class="nav-section">Engage</div>
        <a href="index.php?page=reviews" class="nav-link <?= ($page ?? '') === 'reviews' ? 'active' : '' ?>">
            <span class="icon">⭐</span> Reviews
        </a>

        <div class="nav-section">Insights</div>
        <a href="index.php?page=analytics" class="nav-link <?= ($page ?? '') === 'analytics' ? 'active' : '' ?>">
            <span class="icon">📈</span> Analytics
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="seller-info">
            <div class="seller-avatar">
                <?= isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : 'S' ?>
            </div>
            <div>
                <div class="seller-name"><?= isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Seller' ?></div>
                <div class="seller-role">Seller Account</div>
            </div>
        </div>
        <a href="index.php?page=logout" class="logout-btn">🚪 Logout</a>
    </div>
</aside>

<!-- MAIN -->
<div class="main-content">
    <div class="topbar">
        <div>
            <div class="topbar-title"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard' ?></div>
            <div class="topbar-sub"><?= isset($pageSubtitle) ? htmlspecialchars($pageSubtitle) : '' ?></div>
        </div>
    </div>
    <div class="page-body">