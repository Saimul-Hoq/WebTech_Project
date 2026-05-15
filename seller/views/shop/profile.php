<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
<?php endif; ?>

<div class="grid-2">

    <!-- CURRENT SHOP CARD -->
    <div class="card">
        <div class="card-title">🏪 Current Shop Info</div>

        <div style="text-align:center;margin-bottom:24px;">
            <?php if (!empty($seller['logo'])): ?>
                <img src="<?= htmlspecialchars($seller['logo']) ?>"
                     alt="Shop Logo"
                     style="width:100px;height:100px;border-radius:50%;object-fit:cover;
                            border:3px solid #7c3aed;">
            <?php else: ?>
                <div style="width:100px;height:100px;border-radius:50%;background:#ede9fe;
                            display:flex;align-items:center;justify-content:center;
                            font-size:40px;margin:0 auto;">🏪</div>
            <?php endif; ?>
            <div style="margin-top:12px;font-size:18px;font-weight:800;color:#1a1a2e;">
                <?= htmlspecialchars($seller['shop_name']) ?>
            </div>
            <div style="margin-top:4px;">
                <?php if ($seller['status'] === 'approved'): ?>
                    <span class="badge badge-success">✅ Approved</span>
                <?php elseif ($seller['status'] === 'pending'): ?>
                    <span class="badge badge-warning">⏳ Pending</span>
                <?php else: ?>
                    <span class="badge badge-danger">❌ Rejected</span>
                <?php endif; ?>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:12px;">

            <div style="display:flex;gap:12px;padding:12px;background:#f9fafb;border-radius:8px;">
                <span style="font-size:20px;">📝</span>
                <div>
                    <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                        Description
                    </div>
                    <div style="font-size:14px;color:#374151;margin-top:3px;">
                        <?= htmlspecialchars($seller['shop_description']) ?>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;padding:12px;background:#f9fafb;border-radius:8px;">
                <span style="font-size:20px;">📍</span>
                <div>
                    <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                        Address
                    </div>
                    <div style="font-size:14px;color:#374151;margin-top:3px;">
                        <?= htmlspecialchars($seller['address']) ?>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;padding:12px;background:#f9fafb;border-radius:8px;">
                <span style="font-size:20px;">📧</span>
                <div>
                    <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                        Email
                    </div>
                    <div style="font-size:14px;color:#374151;margin-top:3px;">
                        <?= htmlspecialchars($seller['email']) ?>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;padding:12px;background:#f9fafb;border-radius:8px;">
                <span style="font-size:20px;">📞</span>
                <div>
                    <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                        Phone
                    </div>
                    <div style="font-size:14px;color:#374151;margin-top:3px;">
                        <?= htmlspecialchars($seller['phone']) ?>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;padding:12px;background:#f9fafb;border-radius:8px;">
                <span style="font-size:20px;">📅</span>
                <div>
                    <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">
                        Member Since
                    </div>
                    <div style="font-size:14px;color:#374151;margin-top:3px;">
                        <?= date('F j, Y', strtotime($seller['created_at'])) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- EDIT FORM -->
    <div class="card">
        <div class="card-title">✏️ Update Shop Profile</div>

        <form method="POST" action="index.php?page=shop-profile" enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">Shop Name *</label>
                <input type="text" name="shop_name"
                       class="form-control <?= !empty($errors['shop_name']) ? 'is-error' : '' ?>"
                       value="<?= htmlspecialchars($old['shop_name'] ?? $seller['shop_name']) ?>">
                <?php if (!empty($errors['shop_name'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['shop_name']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Shop Description *</label>
                <textarea name="shop_description"
                          class="form-control <?= !empty($errors['shop_description']) ? 'is-error' : '' ?>"
                          style="min-height:90px;"><?= htmlspecialchars($old['shop_description'] ?? $seller['shop_description']) ?></textarea>
                <?php if (!empty($errors['shop_description'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['shop_description']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Address *</label>
                <input type="text" name="address"
                       class="form-control <?= !empty($errors['address']) ? 'is-error' : '' ?>"
                       value="<?= htmlspecialchars($old['address'] ?? $seller['address']) ?>">
                <?php if (!empty($errors['address'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['address']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Update Logo</label>
                <input type="file" name="shop_logo" class="form-control"
                       accept="image/jpeg,image/png,image/webp">
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                    JPG, PNG or WEBP. Max 2MB. Leave empty to keep current logo.
                </div>
                <?php if (!empty($errors['logo'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['logo']) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                💾 Save Changes
            </button>

        </form>
    </div>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>