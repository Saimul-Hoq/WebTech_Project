<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<div style="margin-bottom:20px;">
    <a href="index.php?page=products" class="btn btn-secondary btn-sm">← Back to Products</a>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-title">✏️ Edit Product</div>

    <form method="POST" action="index.php?page=products-edit&id=<?= $product['id'] ?>"
          enctype="multipart/form-data">

        <div class="grid-2">

            <!-- LEFT COLUMN -->
            <div>
                <div class="form-group">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name"
                           class="form-control <?= !empty($errors['name']) ? 'is-error' : '' ?>"
                           value="<?= htmlspecialchars($old['name'] ?? $product['name']) ?>"
                           placeholder="Product name">
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
                            <?php
                            $selected = ($old['category_id'] ?? $product['category_id']) == $cat['id']
                                        ? 'selected' : '';
                            ?>
                            <option value="<?= $cat['id'] ?>" <?= $selected ?>>
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
                               value="<?= htmlspecialchars($old['price'] ?? $product['price']) ?>"
                               placeholder="0.00">
                        <?php if (!empty($errors['price'])): ?>
                            <div class="form-error"><?= htmlspecialchars($errors['price']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" min="0"
                               class="form-control <?= !empty($errors['stock_quantity']) ? 'is-error' : '' ?>"
                               value="<?= htmlspecialchars($old['stock_quantity'] ?? $product['stock_quantity']) ?>"
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
                              placeholder="Describe your product..."><?= htmlspecialchars($old['description'] ?? $product['description']) ?></textarea>
                    <?php if (!empty($errors['description'])): ?>
                        <div class="form-error"><?= htmlspecialchars($errors['description']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT COLUMN — IMAGES -->
            <div>

                <!-- CURRENT PRIMARY IMAGE -->
                <div class="form-group">
                    <label class="form-label">Current Primary Image</label>
                    <div style="width:100%;height:180px;border-radius:10px;
                                border:2px solid #e5e7eb;overflow:hidden;background:#f9fafb;">
                        <?php if (!empty($product['primary_image'])): ?>
                            <img src="<?= htmlspecialchars($product['primary_image']) ?>"
                                 alt="Current image"
                                 style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                            <div style="display:flex;align-items:center;justify-content:center;
                                        height:100%;color:#9ca3af;font-size:13px;">
                                No image
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- REPLACE PRIMARY IMAGE -->
                <div class="form-group">
                    <label class="form-label">Replace Primary Image</label>
                    <input type="file" name="primary_image"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewImage(this, 'new-preview')">
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                        Leave empty to keep current image.
                    </div>

                    <div id="new-preview"
                         style="margin-top:10px;width:100%;height:120px;border-radius:10px;
                                border:2px dashed #e5e7eb;display:none;overflow:hidden;">
                    </div>
                </div>

                <!-- ADDITIONAL IMAGES -->
                <?php if (!empty($additionalImages)): ?>
                <div class="form-group">
                    <label class="form-label">Additional Images</label>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <?php foreach ($additionalImages as $img): ?>
                            <img src="<?= htmlspecialchars($img['image_path']) ?>"
                                 alt=""
                                 style="width:64px;height:64px;border-radius:8px;
                                        object-fit:cover;border:1px solid #e5e7eb;">
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- ADD MORE IMAGES -->
                <div class="form-group">
                    <label class="form-label">Add More Images <span style="color:#9ca3af;">(up to 4 total)</span></label>
                    <input type="file" name="additional_images[]"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp"
                           multiple>
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                        Max 2MB each. JPG, PNG or WEBP.
                    </div>
                </div>

            </div>
        </div>

        <!-- SUBMIT -->
        <div style="display:flex;gap:12px;margin-top:8px;padding-top:20px;
                    border-top:1px solid #f3f4f6;">
            <button type="submit" class="btn btn-primary">
                💾 Update Product
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
            preview.style.display = 'block';
            preview.innerHTML = '<img src="' + e.target.result +
                '" style="width:100%;height:100%;object-fit:cover;">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>