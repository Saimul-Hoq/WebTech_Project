<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- TOOLBAR -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <span style="font-size:14px;color:#6b7280;">
        <?= count($coupons) ?> coupon<?= count($coupons) !== 1 ? 's' : '' ?> total
    </span>
    <a href="index.php?page=coupons-create" class="btn btn-primary">
        ➕ Create Coupon
    </a>
</div>

<div class="card">
    <div class="card-title">🏷️ Your Coupons</div>

    <?php if (empty($coupons)): ?>
        <div class="empty-state">
            <div class="icon">🏷️</div>
            <p>No coupons yet. Create your first promotional code!</p>
            <a href="index.php?page=coupons-create" class="btn btn-primary"
               style="margin-top:16px;">➕ Create Coupon</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Usage</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coupons as $c): ?>
                    <?php
                        $isExpired  = strtotime($c['expires_at']) < time();
                        $usageCount = $usageCounts[$c['id']] ?? 0;
                        $isFull     = $usageCount >= $c['max_uses'];
                    ?>
                    <tr>
                        <!-- CODE -->
                        <td>
                            <div style="font-family:monospace;font-size:15px;font-weight:800;
                                        color:#7c3aed;letter-spacing:1px;background:#ede9fe;
                                        display:inline-block;padding:4px 10px;border-radius:6px;">
                                <?= htmlspecialchars($c['code']) ?>
                            </div>
                        </td>

                        <!-- DISCOUNT -->
                        <td>
                            <span style="font-size:18px;font-weight:800;color:#10b981;">
                                <?= $c['discount_percentage'] ?>%
                            </span>
                            <span style="font-size:12px;color:#6b7280;"> off</span>
                        </td>

                        <!-- USAGE -->
                        <td>
                            <div style="font-size:14px;font-weight:600;color:#374151;">
                                <?= $usageCount ?> / <?= $c['max_uses'] ?>
                            </div>
                            <!-- Progress bar -->
                            <?php $pct = $c['max_uses'] > 0
                                ? min(100, round($usageCount / $c['max_uses'] * 100)) : 0; ?>
                            <div style="height:4px;background:#f3f4f6;border-radius:4px;
                                        margin-top:4px;width:80px;">
                                <div style="height:4px;width:<?= $pct ?>%;
                                            background:<?= $pct >= 100 ? '#ef4444' : '#10b981' ?>;
                                            border-radius:4px;"></div>
                            </div>
                        </td>

                        <!-- EXPIRES -->
                        <td>
                            <div style="font-size:13px;color:<?= $isExpired ? '#ef4444' : '#374151' ?>;
                                        font-weight:<?= $isExpired ? '700' : '400' ?>;">
                                <?= date('M j, Y', strtotime($c['expires_at'])) ?>
                                <?php if ($isExpired): ?>
                                    <div style="font-size:11px;color:#ef4444;">Expired</div>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- STATUS -->
                        <td>
                            <?php if (!$c['is_active']): ?>
                                <span class="badge badge-gray">Inactive</span>
                            <?php elseif ($isExpired): ?>
                                <span class="badge badge-danger">Expired</span>
                            <?php elseif ($isFull): ?>
                                <span class="badge badge-warning">Maxed Out</span>
                            <?php else: ?>
                                <span class="badge badge-success">Active</span>
                            <?php endif; ?>
                        </td>

                        <!-- ACTIONS -->
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="index.php?page=coupons-toggle&id=<?= $c['id'] ?>"
                                   class="btn btn-secondary btn-sm"
                                   onclick="return confirm('Toggle this coupon?')">
                                    <?= $c['is_active'] ? '🔒 Disable' : '✅ Enable' ?>
                                </a>
                                <a href="index.php?page=coupons-delete&id=<?= $c['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this coupon?')">
                                    🗑️
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

<?php require_once __DIR__ . '/../layout/footer.php'; ?>