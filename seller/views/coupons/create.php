<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<div style="margin-bottom:20px;">
    <a href="index.php?page=coupons" class="btn btn-secondary btn-sm">← Back to Coupons</a>
</div>

<div style="max-width:620px;">
    <div class="card">
        <div class="card-title">🏷️ Create New Coupon</div>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=coupons-create">

            <div class="form-group">
                <label class="form-label">Coupon Code *</label>
                <input type="text" name="code"
                       class="form-control <?= !empty($errors['code']) ? 'is-error' : '' ?>"
                       value="<?= htmlspecialchars($old['code'] ?? '') ?>"
                       placeholder="e.g. SUMMER20"
                       style="text-transform:uppercase;font-family:monospace;
                              font-size:16px;letter-spacing:1px;"
                       oninput="this.value = this.value.toUpperCase()">
                <?php if (!empty($errors['code'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['code']) ?></div>
                <?php endif; ?>
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                    Letters and numbers only. No spaces.
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Discount Percentage *</label>
                    <div style="position:relative;">
                        <input type="number" name="discount_percentage" min="1" max="100"
                               class="form-control <?= !empty($errors['discount_percentage']) ? 'is-error' : '' ?>"
                               value="<?= htmlspecialchars($old['discount_percentage'] ?? '') ?>"
                               placeholder="e.g. 20"
                               style="padding-right:36px;">
                        <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                                     color:#6b7280;font-weight:700;">%</span>
                    </div>
                    <?php if (!empty($errors['discount_percentage'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['discount_percentage']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Maximum Uses *</label>
                    <input type="number" name="max_uses" min="1"
                           class="form-control <?= !empty($errors['max_uses']) ? 'is-error' : '' ?>"
                           value="<?= htmlspecialchars($old['max_uses'] ?? '') ?>"
                           placeholder="e.g. 100">
                    <?php if (!empty($errors['max_uses'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['max_uses']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Expiry Date *</label>
                <input type="date" name="expires_at"
                       class="form-control <?= !empty($errors['expires_at']) ? 'is-error' : '' ?>"
                       value="<?= htmlspecialchars($old['expires_at'] ?? '') ?>"
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                <?php if (!empty($errors['expires_at'])): ?>
                    <div class="form-error"><?= htmlspecialchars($errors['expires_at']) ?></div>
                <?php endif; ?>
            </div>

            <!-- PREVIEW -->
            <div style="background:#f9fafb;border-radius:10px;padding:16px;
                        margin-bottom:20px;border:1.5px dashed #e5e7eb;">
                <div style="font-size:11px;font-weight:700;color:#6b7280;
                            text-transform:uppercase;margin-bottom:8px;">
                    Preview
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="font-family:monospace;font-size:18px;font-weight:800;
                                color:#7c3aed;background:#ede9fe;padding:6px 14px;
                                border-radius:8px;" id="preview-code">
                        CODE
                    </div>
                    <div style="font-size:14px;color:#374151;">
                        gives <strong id="preview-pct" style="color:#10b981;">?%</strong> off
                        · max <strong id="preview-uses">?</strong> uses
                        · expires <strong id="preview-date">?</strong>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn btn-primary">🏷️ Create Coupon</button>
                <a href="index.php?page=coupons" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

<script>
// Live preview update
function updatePreview() {
    const code = document.querySelector('[name="code"]').value || 'CODE';
    const pct  = document.querySelector('[name="discount_percentage"]').value || '?';
    const uses = document.querySelector('[name="max_uses"]').value || '?';
    const date = document.querySelector('[name="expires_at"]').value;

    document.getElementById('preview-code').textContent = code;
    document.getElementById('preview-pct').textContent  = pct + (pct !== '?' ? '%' : '');
    document.getElementById('preview-uses').textContent = uses;
    document.getElementById('preview-date').textContent = date
        ? new Date(date).toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric'})
        : '?';
}

document.querySelectorAll('input').forEach(el => el.addEventListener('input', updatePreview));
updatePreview();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>