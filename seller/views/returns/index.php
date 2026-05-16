<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div class="card-title" style="margin:0;border:none;padding:0;">↩️ Return Requests</div>
        <?php if ($pendingCount > 0): ?>
            <span style="background:#ef4444;color:#fff;border-radius:20px;
                         padding:4px 12px;font-size:12px;font-weight:700;">
                <?= $pendingCount ?> pending
            </span>
        <?php endif; ?>
    </div>

    <?php if (empty($returns)): ?>
        <div class="empty-state">
            <div class="icon">↩️</div>
            <p>No return requests yet.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Order</th>
                        <th>Reason</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($returns as $r): ?>
                    <?php
                    $badgeMap = [
                        'pending'  => 'badge-warning',
                        'approved' => 'badge-success',
                        'rejected' => 'badge-danger',
                    ];
                    $badge = $badgeMap[$r['status']] ?? 'badge-gray';
                    ?>
                    <tr>
                        <!-- PRODUCT -->
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <?php if (!empty($r['primary_image'])): ?>
                                    <img src="<?= htmlspecialchars($r['primary_image']) ?>"
                                         style="width:36px;height:36px;border-radius:6px;
                                                object-fit:cover;border:1px solid #e5e7eb;">
                                <?php else: ?>
                                    <div style="width:36px;height:36px;border-radius:6px;
                                                background:#f3f4f6;display:flex;align-items:center;
                                                justify-content:center;font-size:16px;">📦</div>
                                <?php endif; ?>
                                <span style="font-size:13px;font-weight:600;color:#374151;">
                                    <?= htmlspecialchars($r['product_name']) ?>
                                </span>
                            </div>
                        </td>

                        <!-- CUSTOMER -->
                        <td>
                            <div style="font-weight:600;font-size:13px;color:#374151;">
                                <?= htmlspecialchars($r['customer_name']) ?>
                            </div>
                            <div style="font-size:11px;color:#9ca3af;">
                                <?= htmlspecialchars($r['customer_email']) ?>
                            </div>
                        </td>

                        <!-- ORDER -->
                        <td>
                            <a href="index.php?page=orders-detail&id=<?= $r['order_id'] ?>"
                               style="font-family:monospace;font-weight:700;color:#7c3aed;
                                      text-decoration:none;">
                                #<?= str_pad($r['order_id'], 5, '0', STR_PAD_LEFT) ?>
                            </a>
                        </td>

                        <!-- REASON -->
                        <td>
                            <div style="font-size:13px;color:#374151;max-width:160px;
                                        overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                 title="<?= htmlspecialchars($r['reason']) ?>">
                                <?= htmlspecialchars($r['reason']) ?>
                            </div>
                        </td>

                        <!-- AMOUNT -->
                        <td>
                            <strong style="color:#1a1a2e;">
                                Tk <?= number_format($r['price'] * $r['quantity'], 2) ?>
                            </strong>
                        </td>

                        <!-- STATUS -->
                        <td>
                            <span class="badge <?= $badge ?>">
                                <?= ucfirst($r['status']) ?>
                            </span>
                            <?php if (!empty($r['seller_response'])): ?>
                                <div style="font-size:11px;color:#6b7280;margin-top:3px;
                                            max-width:120px;overflow:hidden;text-overflow:ellipsis;
                                            white-space:nowrap;"
                                     title="<?= htmlspecialchars($r['seller_response']) ?>">
                                    💬 <?= htmlspecialchars($r['seller_response']) ?>
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- DATE -->
                        <td style="font-size:12px;color:#6b7280;white-space:nowrap;">
                            <?= date('M j, Y', strtotime($r['created_at'])) ?>
                        </td>

                        <!-- ACTIONS -->
                        <td>
                            <?php if ($r['status'] === 'pending'): ?>
                                <button class="btn btn-success btn-sm"
                                        onclick="showResponseForm(<?= $r['id'] ?>, 'approved')">
                                    ✅ Approve
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        style="margin-top:4px;"
                                        onclick="showResponseForm(<?= $r['id'] ?>, 'rejected')">
                                    ❌ Reject
                                </button>
                            <?php else: ?>
                                <span style="font-size:12px;color:#9ca3af;">Resolved</span>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- INLINE RESPONSE FORM -->
                    <?php if ($r['status'] === 'pending'): ?>
                    <tr id="response-row-<?= $r['id'] ?>" style="display:none;">
                        <td colspan="8" style="background:#f9fafb;padding:16px;">
                            <form method="POST"
                                  action="index.php?page=returns-action&id=<?= $r['id'] ?>">
                                <input type="hidden" name="decision" id="decision-<?= $r['id'] ?>">
                                <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                                    <div style="flex:1;min-width:200px;">
                                        <label class="form-label">
                                            Response / Reason <span style="color:#ef4444;">*</span>
                                        </label>
                                        <input type="text" name="seller_response"
                                               class="form-control"
                                               placeholder="e.g. Approved — please ship item back to us"
                                               required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        💾 Submit
                                    </button>
                                    <button type="button" class="btn btn-secondary"
                                            onclick="hideResponseForm(<?= $r['id'] ?>)">
                                        Cancel
                                    </button>
                                </div>
                                <div id="decision-label-<?= $r['id'] ?>"
                                     style="margin-top:8px;font-size:12px;font-weight:700;"></div>
                            </form>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function showResponseForm(id, decision) {
    document.getElementById('response-row-' + id).style.display = 'table-row';
    document.getElementById('decision-' + id).value = decision;
    const label = document.getElementById('decision-label-' + id);
    if (decision === 'approved') {
        label.textContent  = '✅ You are APPROVING this return request.';
        label.style.color  = '#065f46';
    } else {
        label.textContent  = '❌ You are REJECTING this return request.';
        label.style.color  = '#991b1b';
    }
}

function hideResponseForm(id) {
    document.getElementById('response-row-' + id).style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>