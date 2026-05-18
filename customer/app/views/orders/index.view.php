<?php require "../app/views/partials/header.php"; ?>

<section class="page-heading">
    <h1>Order History</h1>
    <p>Review your cash-on-delivery purchases and item statuses.</p>
</section>

<?php if (empty($orders)): ?>
    <div class="empty-state">
        <h3>No orders yet</h3>
        <a class="btn btn-primary" href="<?= url('') ?>">Start shopping</a>
    </div>
<?php else: ?>
    <section class="order-list">
        <?php foreach ($orders as $order): ?>
            <article class="order-card">
                <div class="order-head">
                    <div>
                        <h2>Order #<?= (int) $order['id'] ?></h2>
                        <p><?= esc(date('M d, Y h:i A', strtotime($order['created_at']))) ?></p>
                    </div>
                    <strong><?= money($order['total_amount']) ?></strong>
                </div>
                <p class="address"><?= nl2br(esc($order['shipping_address'])) ?></p>
                <?php foreach ($order['items'] as $item): ?>
                    <div class="order-item">
                        <img src="<?= product_image($item['primary_image']) ?>" alt="<?= esc($item['name']) ?>">
                        <div>
                            <h3><?= esc($item['name']) ?></h3>
                            <p><?= esc($item['shop_name']) ?> · Qty <?= (int) $item['quantity'] ?> · <?= money($item['price']) ?></p>
                        </div>
                        <span class="status"><?= esc(ucwords(str_replace('_', ' ', $item['status']))) ?></span>
                    </div>
                <?php endforeach; ?>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php require "../app/views/partials/footer.php"; ?>
