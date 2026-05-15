<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<div style="margin-bottom:20px;">
    <a href="index.php?page=products" class="btn btn-secondary btn-sm">← Back to Products</a>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-title">➕ Add New Product</div>

    <form method="POST" action="index.php?page=products-create" enctype="multipart/form-data">

        <div class="grid-2">

            <!-- LEFT COLUMN -->
            <div>
                <div class="form-group">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name"
                           class="form-control <?= !empty($errors['name']) ? 'is-error' : '' ?>"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                           placeholder="e.g. Wireless Bluetooth Headphones">
                    <?php if (!empty($errors['name'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['name']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id"
                            class="form-control <?= !empty($errors['category_id']) ? 'is-error' : '' ?>">
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= (isset($old['category_id']) && $old['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['category_id'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['category_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Price ($) *</label>
                        <input type="number" name="price" step="0.01" min="0"
                               class="form-control <?= !empty($errors['price']) ? 'is-error' : '' ?>"
                               value="<?= htmlspecialchars($old['price'] ?? '') ?>"
                               placeholder="0.00">
                        <?php if (!empty($errors['price'])): ?>
                            <div class="form-error"><?= htmlspecialchars($errors['price']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" min="0"
                               class="form-control <?= !empty($errors['stock_quantity']) ? 'is-error' : '' ?>"
                               value="<?= htmlspecialchars($old['stock_quantity'] ?? '') ?>"
                               placeholder="0">
                        <?php if (!empty($errors['stock_quantity'])): ?>
                            <div class="form-error"><?= htmlspecialchars($errors['stock_quantity']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <textarea name="description" rows="5"
                              class="form-control <?= !empty($errors['description']) ? 'is-error' : '' ?>"
                              placeholder="Describe your product in detail..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    <?php if (!empty($errors['description'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['description']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT COLUMN — IMAGES -->
            <div>
                <div class="form-group">
                    <label class="form-label">Primary Image *</label>
                    <input type="file" name="primary_image"
                           class="form-control <?= !empty($errors['primary_image']) ? 'is-error' : '' ?>"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewImage(this, 'primary-preview')">
                    <?php if (!empty($errors['primary_image'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['primary_image']) ?></div>
                    <?php endif; ?>

                    <!-- Preview box -->
                    <div id="primary-preview"
                         style="margin-top:10px;width:100%;height:180px;border-radius:10px;
                                border:2px dashed #e5e7eb;display:flex;align-items:center;
                                justify-content:center;background:#f9fafb;overflow:hidden;">
                        <span style="color:#9ca3af;font-size:13px;">Image preview</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Additional Images <span style="color:#9ca3af;">(up to 4)</span></label>
                    <input type="file" name="additional_images[]"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp"
                           multiple>
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                        Hold Ctrl/Cmd to select multiple files. Max 2MB each.
                    </div>
                </div>

                <!-- IMAGE TIPS -->
                <div style="background:#f0fdf4;border-radius:10px;padding:16px;margin-top:8px;">
                    <div style="font-size:12px;font-weight:700;color:#065f46;margin-bottom:8px;">
                        📸 Image Tips
                    </div>
                    <ul style="font-size:12px;color:#374151;padding-left:16px;line-height:1.8;">
                        <li>Use a white or neutral background</li>
                        <li>Minimum 500×500px recommended</li>
                        <li>JPG, PNG or WEBP only</li>
                        <li>Max 2MB per image</li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- SUBMIT -->
        <div style="display:flex;gap:12px;margin-top:8px;padding-top:20px;
                    border-top:1px solid #f3f4f6;">
            <button type="submit" class="btn btn-primary">
                💾 Save Product
            </button>
            <a href="index.php?page=products" class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>