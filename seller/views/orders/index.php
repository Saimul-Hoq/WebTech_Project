<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- FILTER TABS -->
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <?php
    $filters = [
        'all'        => '🗂️ All',
        'pending'    => '⏳ Pending',
        'processing' => '⚙️ Processing',
        'shipped'    => '🚚 Shipped',
        'delivered'  => '✅ Delivered',
        'cancelled'  => '❌ Cancelled',
    ];
    foreach ($filters as $val => $label):
        $active = ($currentFilter === $val) ? 'btn-primary' : 'btn-secondary';
    ?>
        <a href="index.php?page=orders&status=<?= $val ?>"
           class="btn <?= $active ?> btn-sm">
            <?= $label ?>
            <?php if ($val === 'pending' && $pendingCount > 0): ?>
                <span style="background:#ef4444;color:#fff;border-radius:10px;
                             padding:1px 6px;font-size:10px;margin-left:4px;">
                    <?= $pendingCount ?>
                </span>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-title">
        🛒 Orders
        <span style="font-size:13px;font-weight:400;color:#6b7280;margin-left:8px;">
            <?= count($orders) ?> item<?= count($orders) !== 1 ? 's' : '' ?>
        </span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <div class="icon">📭</div>
            <p>No orders found<?= $currentFilter !== 'all' ? ' for this status' : '' ?>.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <!-- ORDER ID -->
                        <td>
                            <a href="index.php?page=orders-detail&id=<?= $o['order_id'] ?>"
                               style="font-weight:700;color:#7c3aed;text-decoration:none;
                                      font-family:monospace;">
                                #<?= str_pad($o['order_id'], 5, '0', STR_PAD_LEFT) ?>
                            </a>
                            <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                                Item #<?= $o['item_id'] ?>
                            </div>
                        </td>

                        <!-- CUSTOMER -->
                        <td>
                            <div style="font-weight:600;font-size:13px;color:#374151;">
                                <?= htmlspecialchars($o['customer_name']) ?>
                            </div>
                            <div style="font-size:11px;color:#9ca3af;">
                                <?= htmlspecialchars($o['customer_email']) ?>
                            </div>
                        </td>

                        <!-- PRODUCT -->
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <?php if (!empty($o['primary_image'])): ?>
                                    <img src="<?= htmlspecialchars($o['primary_image']) ?>"
                                         style="width:36px;height:36px;border-radius:6px;
                                                object-fit:cover;border:1px solid #e5e7eb;">
                                <?php endif; ?>
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:#374151;">
                                        <?= htmlspecialchars($o['product_name']) ?>
                                    </div>
                                    <div style="font-size:11px;color:#9ca3af;">
                                        Qty: <?= $o['quantity'] ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- AMOUNT -->
                        <td>
                            <strong style="color:#1a1a2e;">
                                $<?= number_format($o['price'] * $o['quantity'], 2) ?>
                            </strong>
                            <div style="font-size:11px;color:#9ca3af;">
                                $<?= number_format($o['price'], 2) ?> each
                            </div>
                        </td>

                        <!-- PAYMENT -->
                        <td>
                            <span class="badge badge-info">
                                <?= htmlspecialchars(ucfirst($o['payment_method'])) ?>
                            </span>
                        </td>

                        <!-- STATUS -->
                        <td>
                            <?php
                            $badgeMap = [
                                'pending'    => 'badge-warning',
                                'processing' => 'badge-purple',
                                'shipped'    => 'badge-info',
                                'delivered'  => 'badge-success',
                                'cancelled'  => 'badge-danger',
                            ];
                            $badge = $badgeMap[$o['status']] ?? 'badge-gray';
                            ?>
                            <span class="badge <?= $badge ?>">
                                <?= ucfirst($o['status']) ?>
                            </span>
                            <?php if (!empty($o['tracking_note'])): ?>
                                <div style="font-size:11px;color:#6b7280;margin-top:3px;
                                            max-width:120px;overflow:hidden;text-overflow:ellipsis;
                                            white-space:nowrap;"
                                     title="<?= htmlspecialchars($o['tracking_note']) ?>">
                                    📦 <?= htmlspecialchars($o['tracking_note']) ?>
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- DATE -->
                        <td style="font-size:12px;color:#6b7280;white-space:nowrap;">
                            <?= date('M j, Y', strtotime($o['created_at'])) ?>
                            <div><?= date('g:i A', strtotime($o['created_at'])) ?></div>
                        </td>

                        <!-- ACTIONS -->
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="index.php?page=orders-detail&id=<?= $o['order_id'] ?>"
                                   class="btn btn-secondary btn-sm">
                                    👁️ View
                                </a>
                                <?php
                                $allowed = [
                                    'pending'    => ['processing' => '⚙️ Confirm'],
                                    'processing' => ['shipped'    => '🚚 Ship'],
                                ];
                                if (isset($allowed[$o['status']])):
                                    foreach ($allowed[$o['status']] as $next => $label):
                                ?>
                                    <a href="index.php?page=orders-update&item_id=<?= $o['item_id'] ?>&status=<?= $next ?>"
                                       class="btn btn-success btn-sm"
                                       onclick="return confirm('Mark this item as <?= $next ?>?')">
                                        <?= $label ?>
                                    </a>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>