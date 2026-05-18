<?php require "../app/views/partials/header.php"; ?>

<?php $total = array_sum(array_column($items, 'line_total')); ?>

<section class="page-heading">
    <h1>Checkout</h1>
    <p>Cash on delivery is selected for this order.</p>
</section>

<section class="checkout-layout">
    <form method="post" action="<?= url('checkout/place') ?>" class="form-card">
        <label>Shipping Address
            <textarea name="shipping_address" rows="6" required><?= esc(current_user()['phone'] ? '' : '') ?></textarea>
        </label>
        <label>Payment Method
            <input type="text" value="Cash on Delivery" disabled>
        </label>
        <button class="btn btn-primary" type="submit">Place Order</button>
    </form>
    <aside class="summary-box">
        <h2>Order Summary</h2>
        <?php foreach ($items as $item): ?>
            <div>
                <span><?= esc($item['name']) ?> x <?= (int) $item['cart_qty'] ?></span>
                <strong><?= money($item['line_total']) ?></strong>
            </div>
        <?php endforeach; ?>
        <div class="summary-total"><span>Total</span><strong><?= money($total) ?></strong></div>
    </aside>
</section>

<?php require "../app/views/partials/footer.php"; ?>
