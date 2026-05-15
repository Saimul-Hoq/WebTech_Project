<?php
// Redirect if already logged in as seller
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'seller') {
    header('Location: index.php?page=dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login | ShopHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .auth-wrapper {
            display: flex;
            width: 100%;
            max-width: 820px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
            overflow: hidden;
            min-height: 500px;
        }

        /* LEFT PANEL */
        .auth-left {
            width: 360px;
            background: linear-gradient(160deg, #1a1a2e 0%, #4c1d95 100%);
            padding: 48px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand {
            font-size: 22px;
            font-weight: 800;
            color: #a78bfa;
            letter-spacing: -0.5px;
            margin-bottom: 40px;
        }

        .auth-left h1 {
            color: #fff;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .auth-left p {
            color: #c4b5fd;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .perks { list-style: none; }
        .perks li {
            color: #e9d5ff;
            font-size: 13px;
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .perks li:last-child { border-bottom: none; }
        .perks li span { font-size: 18px; }

        /* RIGHT PANEL */
        .auth-right {
            flex: 1;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-right h2 {
            font-size: 24px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .auth-right .sub {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 32px;
        }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #1a1a2e;
            outline: none;
            transition: border-color 0.2s;
            background: #fafafa;
        }

        .form-control:focus {
            border-color: #7c3aed;
            background: #fff;
        }

        .form-control.is-error { border-color: #ef4444; }

        .form-error {
            color: #ef4444;
            font-size: 11px;
            margin-top: 4px;
            font-weight: 600;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: #7c3aed;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
            letter-spacing: 0.3px;
        }

        .btn-submit:hover { background: #6d28d9; }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #6b7280;
        }

        .register-link a {
            color: #7c3aed;
            font-weight: 700;
            text-decoration: none;
        }

        .register-link a:hover { text-decoration: underline; }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-danger  { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }

        .divider {
            text-align: center;
            font-size: 12px;
            color: #d1d5db;
            margin: 24px 0;
            position: relative;
        }

        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #f3f4f6;
        }

        .divider::before { left: 0; }
        .divider::after  { right: 0; }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- LEFT -->
    <div class="auth-left">
        <div class="brand">🛍️ ShopHub</div>
        <h1>Welcome back</h1>
        <p>Log in to manage your store, products, and orders all in one place.</p>
        <ul class="perks">
            <li><span>📊</span> View your sales analytics</li>
            <li><span>🛒</span> Process incoming orders</li>
            <li><span>📦</span> Manage your inventory</li>
            <li><span>⭐</span> Reply to customer reviews</li>
        </ul>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <h2>Sign in to your store</h2>
        <p class="sub">Enter your credentials to access the seller dashboard.</p>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=login">

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control <?= !empty($errors['email']) ? 'is-error' : '' ?>"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    placeholder="you@example.com"
                    autofocus
                >
                <?php if (!empty($errors['email'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control <?= !empty($errors['password']) ? 'is-error' : '' ?>"
                    placeholder="Your password"
                >
                <?php if (!empty($errors['password'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['password']) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">🔐 Sign In</button>

        </form>

        <div class="register-link">
            Don't have a seller account? <a href="index.php?page=register">Register here</a>
        </div>
    </div>

</div>

</body>
</html>