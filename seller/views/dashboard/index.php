<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<!-- STAT CARDS -->
<div class="grid-4" style="margin-bottom:24px;">

    <div class="stat-card" style="border-top:4px solid #7c3aed;">
        <div class="stat-label">💰 Total Revenue</div>
        <div class="stat-value">$<?= number_format($totalRevenue, 2) ?></div>
        <div class="stat-sub">From delivered orders</div>
    </div>

    <div class="stat-card" style="border-top:4px solid #10b981;">
        <div class="stat-label">🛒 Total Orders</div>
        <div class="stat-value"><?= $totalOrders ?></div>
        <div class="stat-sub">All time</div>
    </div>

    <div class="stat-card" style="border-top:4px solid #f59e0b;">
        <div class="stat-label">📦 Products</div>
        <div class="stat-value"><?= $totalProducts ?></div>
        <div class="stat-sub">In your catalog</div>
    </div>

    <div class="stat-card" style="border-top:4px solid #ef4444;">
        <div class="stat-label">⏳ Pending Orders</div>
        <div class="stat-value"><?= $pendingOrders ?></div>
        <div class="stat-sub">Awaiting confirmation</div>
    </div>

</div>

<div class="grid-2">

    <!-- RECENT ORDERS -->
    <div class="card">
        <div class="card-title">🛒 Recent Orders</div>
        <?php if (empty($recentOrders)): ?>
            <div class="empty-state">
                <div class="icon">📭</div>
                <p>No orders yet</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['product_name']) ?></td>
                            <td>$<?= number_format($order['price'] * $order['quantity'], 2) ?></td>
                            <td>
                                <?php
                                $badgeMap = [
                                    'pending'    => 'badge-warning',
                                    'confirmed'  => 'badge-info',
                                    'processing' => 'badge-purple',
                                    'shipped'    => 'badge-info',
                                    'delivered'  => 'badge-success',
                                    'cancelled'  => 'badge-danger',
                                ];
                                $badge = $badgeMap[$order['status']] ?? 'badge-gray';
                                ?>
                                <span class="badge <?= $badge ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:14px;">
                <a href="index.php?page=orders" class="btn btn-secondary btn-sm">View All Orders →</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- RIGHT COLUMN -->
    <div>

        <!-- LOW STOCK ALERT -->
        <div class="card" style="border-left:4px solid #ef4444;">
            <div class="card-title">⚠️ Low Stock Alert</div>
            <?php if (empty($lowStockProducts)): ?>
                <div style="color:#10b981;font-size:14px;font-weight:600;">
                    ✅ All products have sufficient stock
                </div>
            <?php else: ?>
                <?php foreach ($lowStockProducts as $p): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;
                            padding:10px 0;border-bottom:1px solid #f3f4f6;">
                    <span style="font-size:14px;color:#374151;">
                        <?= htmlspecialchars($p['name']) ?>
                    </span>
                    <span class="badge badge-danger"><?= $p['stock_quantity'] ?> left</span>
                </div>
                <?php endforeach; ?>
                <div style="margin-top:14px;">
                    <a href="index.php?page=products" class="btn btn-secondary btn-sm">Manage Stock →</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- TOP PRODUCTS -->
        <div class="card">
            <div class="card-title">🏆 Top Products</div>
            <?php if (empty($topProducts)): ?>
                <div class="empty-state">
                    <div class="icon">📦</div>
                    <p>No sales data yet</p>
                </div>
            <?php else: ?>
                <?php foreach ($topProducts as $i => $p): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;
                            padding:10px 0;border-bottom:1px solid #f3f4f6;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:18px;">
                            <?= ['🥇','🥈','🥉','4️⃣','5️⃣'][$i] ?? '' ?>
                        </span>
                        <span style="font-size:13px;color:#374151;">
                            <?= htmlspecialchars($p['name']) ?>
                        </span>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:13px;font-weight:700;color:#1a1a2e;">
                            $<?= number_format($p['revenue'], 2) ?>
                        </div>
                        <div style="font-size:11px;color:#6b7280;">
                            <?= $p['units_sold'] ?> sold
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>