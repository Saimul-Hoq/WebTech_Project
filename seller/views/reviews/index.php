<?php require_once __DIR__ . '/../layout/header.php'; requireSeller(); ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- STATS ROW -->
<div class="grid-3" style="margin-bottom:24px;">

    <div class="stat-card" style="border-top:4px solid #f59e0b;">
        <div class="stat-label">⭐ Average Rating</div>
        <div class="stat-value"><?= $avgRating ?>/5</div>
        <div class="stat-sub">Across all products</div>
    </div>

    <div class="stat-card" style="border-top:4px solid #7c3aed;">
        <div class="stat-label">💬 Total Reviews</div>
        <div class="stat-value"><?= count($reviews) ?></div>
        <div class="stat-sub">From customers</div>
    </div>

    <div class="stat-card" style="border-top:4px solid #ef4444;">
        <div class="stat-label">📬 Unreplied</div>
        <div class="stat-value"><?= $unrepliedCount ?></div>
        <div class="stat-sub">Awaiting your response</div>
    </div>

</div>

<div class="card">
    <div class="card-title">⭐ Customer Reviews</div>

    <?php if (empty($reviews)): ?>
        <div class="empty-state">
            <div class="icon">⭐</div>
            <p>No reviews yet. Keep selling!</p>
        </div>
    <?php else: ?>

        <?php foreach ($reviews as $r): ?>
        <div style="border:1.5px solid <?= empty($r['seller_reply']) ? '#fef3c7' : '#f3f4f6' ?>;
                    border-radius:12px;padding:20px;margin-bottom:16px;
                    background:<?= empty($r['seller_reply']) ? '#fffbeb' : '#fff' ?>;">

            <div style="display:flex;gap:14px;align-items:flex-start;">

                <!-- PRODUCT IMAGE -->
                <?php if (!empty($r['primary_image'])): ?>
                    <img src="<?= htmlspecialchars($r['primary_image']) ?>"
                         style="width:52px;height:52px;border-radius:8px;
                                object-fit:cover;border:1px solid #e5e7eb;flex-shrink:0;">
                <?php else: ?>
                    <div style="width:52px;height:52px;border-radius:8px;background:#f3f4f6;
                                display:flex;align-items:center;justify-content:center;
                                font-size:22px;flex-shrink:0;">📦</div>
                <?php endif; ?>

                <div style="flex:1;">

                    <!-- TOP ROW -->
                    <div style="display:flex;justify-content:space-between;
                                align-items:flex-start;flex-wrap:wrap;gap:8px;">
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#374151;">
                                <?= htmlspecialchars($r['customer_name']) ?>
                                <span style="font-weight:400;color:#9ca3af;font-size:12px;">
                                    on <?= htmlspecialchars($r['product_name']) ?>
                                </span>
                            </div>
                            <!-- STAR RATING -->
                            <div style="margin-top:4px;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span style="color:<?= $i <= $r['rating'] ? '#f59e0b' : '#e5e7eb' ?>;
                                                 font-size:16px;">★</span>
                                <?php endfor; ?>
                                <span style="font-size:12px;color:#6b7280;margin-left:4px;">
                                    <?= $r['rating'] ?>/5
                                </span>
                            </div>
                        </div>
                        <div style="font-size:11px;color:#9ca3af;white-space:nowrap;">
                            <?= date('M j, Y', strtotime($r['created_at'])) ?>
                        </div>
                    </div>

                    <!-- REVIEW COMMENT -->
                    <div style="margin-top:10px;font-size:14px;color:#374151;
                                line-height:1.6;background:#f9fafb;padding:12px;
                                border-radius:8px;">
                        "<?= htmlspecialchars($r['comment']) ?>"
                    </div>

                    <!-- EXISTING REPLY -->
                    <?php if (!empty($r['seller_reply'])): ?>
                        <div style="margin-top:10px;padding:12px;background:#ede9fe;
                                    border-radius:8px;border-left:3px solid #7c3aed;">
                            <div style="font-size:11px;font-weight:700;color:#5b21b6;
                                        margin-bottom:4px;">
                                🏪 Your Reply
                            </div>
                            <div style="font-size:13px;color:#374151;">
                                <?= htmlspecialchars($r['seller_reply']) ?>
                            </div>
                            <button class="btn btn-secondary btn-sm"
                                    style="margin-top:8px;"
                                    onclick="showReplyForm(<?= $r['id'] ?>)">
                                ✏️ Edit Reply
                            </button>
                        </div>
                    <?php else: ?>
                        <div style="margin-top:10px;">
                            <button class="btn btn-primary btn-sm"
                                    onclick="showReplyForm(<?= $r['id'] ?>)">
                                💬 Reply to Review
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- REPLY FORM (hidden) -->
                    <div id="reply-form-<?= $r['id'] ?>" style="display:none;margin-top:12px;">
                        <form method="POST"
                              action="index.php?page=reviews-reply&id=<?= $r['id'] ?>">
                            <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                                <div style="flex:1;min-width:200px;">
                                    <label class="form-label">Your Reply</label>
                                    <textarea name="reply" rows="2"
                                              class="form-control"
                                              placeholder="Write a helpful, professional reply..."
                                              required><?= htmlspecialchars($r['seller_reply'] ?? '') ?></textarea>
                                </div>
                                <div style="display:flex;flex-direction:column;gap:6px;">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        💾 Save
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm"
                                            onclick="hideReplyForm(<?= $r['id'] ?>)">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

<script>
function showReplyForm(id) {
    document.getElementById('reply-form-' + id).style.display = 'block';
}
function hideReplyForm(id) {
    document.getElementById('reply-form-' + id).style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>