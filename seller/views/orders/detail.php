<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<div style="margin-bottom:20px;">
    <a href="index.php?page=orders" class="btn btn-secondary btn-sm">← Back to Orders</a>
</div>

<div class="grid-2">

    <!-- ORDER SUMMARY -->
    <div class="card">
        <div class="card-title">📋 Order Summary</div>

        <div style="display:flex;flex-direction:column;gap:10px;">

            <div style="display:flex;justify-content:space-between;padding:10px;
                        background:#f9fafb;border-radius:8px;">
                <span style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                    Order ID
                </span>
                <span style="font-family:monospace;font-weight:800;color:#7c3aed;font-size:15px;">
                    #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                </span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:10px;
                        background:#f9fafb;border-radius:8px;">
                <span style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                    Date Placed
                </span>
                <span style="font-size:13px;color:#374151;">
                    <?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?>
                </span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:10px;
                        background:#f9fafb;border-radius:8px;">
                <span style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                    Payment Method
                </span>
                <span class="badge badge-info">
                    <?= htmlspecialchars(ucfirst($order['payment_method'])) ?>
                </span>
            </div>

            <div style="display:flex;justify-content:space-between;padding:10px;
                        background:#f9fafb;border-radius:8px;">
                <span style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                    Order Total
                </span>
                <span style="font-size:16px;font-weight:800;color:#1a1a2e;">
                    $<?= number_format($order['total_amount'], 2) ?>
                </span>
            </div>

        </div>

        <!-- SHIPPING ADDRESS -->
        <div style="margin-top:20px;">
            <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;
                        margin-bottom:10px;">
                📍 Shipping Address
            </div>
            <div style="background:#f0fdf4;border-radius:8px;padding:14px;
                        font-size:14px;color:#374151;line-height:1.6;
                        border-left:3px solid #10b981;">
                <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
            </div>
        </div>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="card">
        <div class="card-title">👤 Customer Information</div>

        <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;
                    padding:16px;background:#f9fafb;border-radius:10px;">
            <div style="width:52px;height:52px;border-radius:50%;background:#7c3aed;
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-size:20px;font-weight:800;flex-shrink:0;">
                <?= strtoupper(substr($order['customer_name'], 0, 1)) ?>
            </div>
            <div>
                <div style="font-size:16px;font-weight:700;color:#1a1a2e;">
                    <?= htmlspecialchars($order['customer_name']) ?>
                </div>
                <div style="font-size:13px;color:#6b7280;margin-top:2px;">
                    <?= htmlspecialchars($order['customer_email']) ?>
                </div>
                <?php if (!empty($order['customer_phone'])): ?>
                <div style="font-size:13px;color:#6b7280;margin-top:2px;">
                    <?= htmlspecialchars($order['customer_phone']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- ORDER ITEMS FROM YOUR STORE -->
<div class="card">
    <div class="card-title">📦 Your Items in This Order</div>

    <?php if (empty($order['items'])): ?>
        <div class="empty-state">
            <div class="icon">📭</div>
            <p>No items from your store in this order.</p>
        </div>
    <?php else: ?>

        <?php foreach ($order['items'] as $item): ?>
        <?php
        $badgeMap = [
            'pending'    => 'badge-warning',
            'processing' => 'badge-purple',
            'shipped'    => 'badge-info',
            'delivered'  => 'badge-success',
            'cancelled'  => 'badge-danger',
        ];
        $badge = $badgeMap[$item['status']] ?? 'badge-gray';
        ?>
        <div style="border:1.5px solid #f3f4f6;border-radius:10px;padding:18px;
                    margin-bottom:14px;">

            <div style="display:flex;gap:14px;align-items:flex-start;">

                <!-- PRODUCT IMAGE -->
                <?php if (!empty($item['primary_image'])): ?>
                    <img src="<?= htmlspecialchars($item['primary_image']) ?>"
                         alt=""
                         style="width:70px;height:70px;border-radius:8px;
                                object-fit:cover;border:1px solid #e5e7eb;flex-shrink:0;">
                <?php else: ?>
                    <div style="width:70px;height:70px;border-radius:8px;
                                background:#f3f4f6;display:flex;align-items:center;
                                justify-content:center;font-size:28px;flex-shrink:0;">📦</div>
                <?php endif; ?>

                <!-- PRODUCT DETAILS -->
                <div style="flex:1;">
                    <div style="font-size:15px;font-weight:700;color:#1a1a2e;">
                        <?= htmlspecialchars($item['product_name']) ?>
                    </div>
                    <div style="display:flex;gap:20px;margin-top:6px;flex-wrap:wrap;">
                        <div style="font-size:13px;color:#6b7280;">
                            Qty: <strong style="color:#374151;"><?= $item['quantity'] ?></strong>
                        </div>
                        <div style="font-size:13px;color:#6b7280;">
                            Unit price: <strong style="color:#374151;">$<?= number_format($item['price'], 2) ?></strong>
                        </div>
                        <div style="font-size:13px;color:#6b7280;">
                            Subtotal: <strong style="color:#1a1a2e;font-size:15px;">
                                $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                            </strong>
                        </div>
                    </div>

                    <!-- STATUS + TRACKING -->
                    <div style="display:flex;align-items:center;gap:10px;margin-top:10px;flex-wrap:wrap;">
                        <span class="badge <?= $badge ?>"><?= ucfirst($item['status']) ?></span>

                        <?php if (!empty($item['tracking_note'])): ?>
                            <span style="font-size:12px;color:#6b7280;">
                                📦 <?= htmlspecialchars($item['tracking_note']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div style="display:flex;flex-direction:column;gap:8px;flex-shrink:0;">
                    <?php if ($item['status'] === 'pending'): ?>
                        <a href="index.php?page=orders-update&item_id=<?= $item['item_id'] ?>&status=processing"
                           class="btn btn-success btn-sm"
                           onclick="return confirm('Confirm this order item?')">
                            ⚙️ Confirm
                        </a>
                    <?php elseif ($item['status'] === 'processing'): ?>
                        <button class="btn btn-primary btn-sm"
                                onclick="showShipForm(<?= $item['item_id'] ?>)">
                            🚚 Mark Shipped
                        </button>
                    <?php endif; ?>
                </div>

            </div>

            <!-- SHIP FORM (hidden, shown via JS) -->
            <?php if ($item['status'] === 'processing'): ?>
            <div id="ship-form-<?= $item['item_id'] ?>"
                 style="display:none;margin-top:14px;padding-top:14px;
                        border-top:1px solid #f3f4f6;">
                <form method="POST"
                      action="index.php?page=orders-update&item_id=<?= $item['item_id'] ?>&status=shipped">
                    <div style="display:flex;gap:10px;align-items:flex-end;">
                        <div style="flex:1;">
                            <label class="form-label">Tracking Note / Courier Info</label>
                            <input type="text" name="tracking_note"
                                   class="form-control"
                                   placeholder="e.g. DHL TN123456789 — dispatched today">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            🚚 Ship It
                        </button>
                        <button type="button" class="btn btn-secondary"
                                onclick="hideShipForm(<?= $item['item_id'] ?>)">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

<script>
function showShipForm(itemId) {
    document.getElementById('ship-form-' + itemId).style.display = 'block';
}
function hideShipForm(itemId) {
    document.getElementById('ship-form-' + itemId).style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>