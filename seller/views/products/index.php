<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- TOOLBAR -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div>
        <span style="font-size:14px;color:#6b7280;">
            <?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?> in your catalog
        </span>
    </div>
    <a href="index.php?page=products-create" class="btn btn-primary">
        ➕ Add New Product
    </a>
</div>

<!-- LOW STOCK AJAX WIDGET -->
<div id="low-stock-banner" style="display:none;"
     class="alert alert-warning" style="margin-bottom:20px;">
    ⚠️ <strong>Low Stock:</strong>
    <span id="low-stock-list"></span>
    <button onclick="document.getElementById('low-stock-banner').style.display='none'"
            style="float:right;background:none;border:none;cursor:pointer;font-size:16px;">✕</button>
</div>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div class="card-title" style="margin:0;border:none;padding:0;">📦 Your Products</div>
        <button class="btn btn-secondary btn-sm" onclick="checkLowStock()">
            🔍 Check Low Stock
        </button>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <div class="icon">📦</div>
            <p>No products yet. Add your first product!</p>
            <a href="index.php?page=products-create" class="btn btn-primary" style="margin-top:16px;">
                ➕ Add Product
            </a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <!-- PRODUCT INFO -->
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <?php if (!empty($p['primary_image'])): ?>
                                    <img src="<?= htmlspecialchars($p['primary_image']) ?>"
                                         alt=""
                                         style="width:44px;height:44px;border-radius:8px;
                                                object-fit:cover;border:1px solid #e5e7eb;">
                                <?php else: ?>
                                    <div style="width:44px;height:44px;border-radius:8px;
                                                background:#f3f4f6;display:flex;
                                                align-items:center;justify-content:center;
                                                font-size:20px;">📦</div>
                                <?php endif; ?>
                                <div>
                                    <div style="font-weight:600;color:#1a1a2e;font-size:14px;">
                                        <?= htmlspecialchars($p['name']) ?>
                                    </div>
                                    <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                                        ID #<?= $p['id'] ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- CATEGORY -->
                        <td>
                            <span class="badge badge-purple">
                                <?= htmlspecialchars($p['category_name'] ?? 'Uncategorized') ?>
                            </span>
                        </td>

                        <!-- PRICE -->
                        <td>
                            <strong style="color:#1a1a2e;">
                                $<?= number_format($p['price'], 2) ?>
                            </strong>
                        </td>

                        <!-- STOCK with inline AJAX update -->
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <input type="number"
                                       id="stock-<?= $p['id'] ?>"
                                       value="<?= $p['stock_quantity'] ?>"
                                       min="0"
                                       style="width:64px;padding:5px 8px;border:1.5px solid #e5e7eb;
                                              border-radius:6px;font-size:13px;text-align:center;">
                                <button class="btn btn-secondary btn-sm"
                                        onclick="updateStock(<?= $p['id'] ?>)"
                                        id="stock-btn-<?= $p['id'] ?>">
                                    💾
                                </button>
                            </div>
                            <?php if ($p['stock_quantity'] < 10): ?>
                                <div style="font-size:11px;color:#ef4444;margin-top:3px;font-weight:600;">
                                    ⚠️ Low stock
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- STATUS -->
                        <td>
                            <?php if ($p['is_available']): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-gray">Inactive</span>
                            <?php endif; ?>
                        </td>

                        <!-- ACTIONS -->
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="index.php?page=products-edit&id=<?= $p['id'] ?>"
                                   class="btn btn-warning btn-sm">✏️ Edit</a>

                                <a href="index.php?page=products-toggle&id=<?= $p['id'] ?>"
                                   class="btn btn-secondary btn-sm"
                                   onclick="return confirm('Toggle product availability?')">
                                    <?= $p['is_available'] ? '🔒 Disable' : '✅ Enable' ?>
                                </a>

                                <a href="index.php?page=products-delete&id=<?= $p['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this product? This cannot be undone.')">
                                    🗑️ Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- AJAX SCRIPTS -->
<script>
// AJAX: Update stock quantity inline
function updateStock(productId) {
    const input  = document.getElementById('stock-' + productId);
    const btn    = document.getElementById('stock-btn-' + productId);
    const newQty = parseInt(input.value);

    if (isNaN(newQty) || newQty < 0) {
        alert('Please enter a valid stock quantity.');
        return;
    }

    btn.textContent  = '⏳';
    btn.disabled     = true;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'api/stock_update.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        const res = JSON.parse(xhr.responseText);
        if (res.success) {
            btn.textContent = '✅';
            setTimeout(() => {
                btn.textContent = '💾';
                btn.disabled    = false;
            }, 1500);
        } else {
            btn.textContent = '❌';
            btn.disabled    = false;
            alert(res.message || 'Failed to update stock.');
        }
    };

    xhr.onerror = function () {
        btn.textContent = '❌';
        btn.disabled    = false;
        alert('Network error. Try again.');
    };

    xhr.send('product_id=' + productId + '&quantity=' + newQty);
}

// AJAX: Check low stock products
function checkLowStock() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'api/low_stock.php', true);

    xhr.onload = function () {
        const res    = JSON.parse(xhr.responseText);
        const banner = document.getElementById('low-stock-banner');
        const list   = document.getElementById('low-stock-list');

        if (res.success && res.products.length > 0) {
            const names = res.products.map(p => p.name + ' (' + p.stock_quantity + ')').join(', ');
            list.textContent = names;
            banner.style.display = 'block';
        } else if (res.success && res.products.length === 0) {
            alert('✅ All products have sufficient stock!');
        } else {
            alert('Failed to check stock.');
        }
    };

    xhr.send();
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>