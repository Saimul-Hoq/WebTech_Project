<?php
// No sidebar on auth pages — standalone design
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration | ShopHub</title>
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
            max-width: 960px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
            overflow: hidden;
            min-height: 600px;
        }

        /* LEFT PANEL */
        .auth-left {
            width: 380px;
            background: linear-gradient(160deg, #1a1a2e 0%, #4c1d95 100%);
            padding: 48px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-shrink: 0;
        }

        .auth-left h1 {
            color: #fff;
            font-size: 28px;
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

        .brand {
            margin-bottom: 40px;
            font-size: 22px;
            font-weight: 800;
            color: #a78bfa;
            letter-spacing: -0.5px;
        }

        /* RIGHT PANEL */
        .auth-right {
            flex: 1;
            padding: 40px 44px;
            overflow-y: auto;
        }

        .auth-right h2 {
            font-size: 22px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .auth-right .sub {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 28px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .form-group { margin-bottom: 16px; }

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
            padding: 10px 14px;
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

        textarea.form-control { resize: vertical; min-height: 80px; }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #7c3aed;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }

        .btn-submit:hover { background: #6d28d9; }

        .login-link {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #6b7280;
        }

        .login-link a { color: #7c3aed; font-weight: 700; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .alert-danger  { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }

        .section-divider {
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 0 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f3f4f6;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- LEFT -->
    <div class="auth-left">
        <div class="brand">🛍️ ShopHub</div>
        <h1>Start selling today</h1>
        <p>Join thousands of sellers on ShopHub and grow your business with powerful tools.</p>
        <ul class="perks">
            <li><span>📦</span> Full product catalog management</li>
            <li><span>📊</span> Real-time sales analytics</li>
            <li><span>🏷️</span> Create promotional coupons</li>
            <li><span>⭐</span> Manage customer reviews</li>
            <li><span>🚀</span> Fast order processing</li>
        </ul>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <h2>Create Seller Account</h2>
        <p class="sub">Fill in your details. Your account will go live after admin approval.</p>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=register" enctype="multipart/form-data">

            <div class="section-divider">Personal Info</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control <?= !empty($errors['name']) ? 'is-error' : '' ?>"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="John Doe">
                    <?php if (!empty($errors['name'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['name']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number *</label>
                    <input type="text" name="phone" class="form-control <?= !empty($errors['phone']) ? 'is-error' : '' ?>"
                           value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="+1 234 567 8900">
                    <?php if (!empty($errors['phone'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['phone']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control <?= !empty($errors['email']) ? 'is-error' : '' ?>"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="you@example.com">
                <?php if (!empty($errors['email'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control <?= !empty($errors['password']) ? 'is-error' : '' ?>"
                           placeholder="Min 8 characters">
                    <?php if (!empty($errors['password'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['password']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="confirm_password" class="form-control <?= !empty($errors['confirm_password']) ? 'is-error' : '' ?>"
                           placeholder="Repeat password">
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['confirm_password']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-divider">Shop Info</div>

            <div class="form-group">
                <label class="form-label">Shop Name *</label>
                <input type="text" name="shop_name" class="form-control <?= !empty($errors['shop_name']) ? 'is-error' : '' ?>"
                       value="<?= htmlspecialchars($old['shop_name'] ?? '') ?>" placeholder="My Awesome Store">
                <?php if (!empty($errors['shop_name'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['shop_name']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Shop Description *</label>
                <textarea name="shop_description" class="form-control <?= !empty($errors['shop_description']) ? 'is-error' : '' ?>"
                          placeholder="Tell customers what your shop is about..."><?= htmlspecialchars($old['shop_description'] ?? '') ?></textarea>
                <?php if (!empty($errors['shop_description'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['shop_description']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Shop Address *</label>
                <input type="text" name="address" class="form-control <?= !empty($errors['address']) ? 'is-error' : '' ?>"
                       value="<?= htmlspecialchars($old['address'] ?? '') ?>" placeholder="123 Market St, City, Country">
                <?php if (!empty($errors['address'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['address']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Shop Logo (optional)</label>
                <input type="file" name="shop_logo" class="form-control" accept="image/jpeg,image/png,image/webp">
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">JPG, PNG or WEBP. Max 2MB.</div>
            </div>

            <button type="submit" class="btn-submit">🚀 Submit for Approval</button>

        </form>

        <div class="login-link">
            Already have an account? <a href="index.php?page=login">Sign in here</a>
        </div>
    </div>

</div>

</body>
</html>