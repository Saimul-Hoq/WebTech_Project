<?php require "../app/views/partials/header.php"; ?>

<section class="page-heading">
    <h1>Shopping Cart</h1>
    <p>Edit quantities, remove products, and review your total.</p>
</section>

<?php if (empty($items)): ?>
    <div class="empty-state">
        <h3>Your cart is empty</h3>
        <p>Products you add from the home page will appear here.</p>
        <a class="btn btn-primary" href="<?= url('') ?>">Browse products</a>
    </div>
<?php else: ?>
    <?php $total = array_sum(array_column($items, 'line_total')); ?>
    <form method="post" action="<?= url('cart/update') ?>" class="cart-layout">
        <div class="cart-list">
            <?php foreach ($items as $item): ?>
                <article class="cart-row">
                    <img src="<?= product_image($item['primary_image']) ?>" alt="<?= esc($item['name']) ?>">
                    <div>
                        <h3><?= esc($item['name']) ?></h3>
                        <p><?= esc($item['shop_name']) ?> · <?= money($item['price']) ?></p>
                        <a href="<?= url('cart/remove/' . $item['id']) ?>">Remove</a>
                    </div>
                    <label>Qty
                        <input class="qty-input" type="number" name="qty[<?= (int) $item['id'] ?>]" value="<?= (int) $item['cart_qty'] ?>" min="0" max="<?= (int) $item['stock_quantity'] ?>" data-price="<?= (float) $item['price'] ?>">
                    </label>
                    <strong class="line-total"><?= money($item['line_total']) ?></strong>
                </article>
            <?php endforeach; ?>
        </div>
        <aside class="summary-box">
            <h2>Summary</h2>
            <div><span>Subtotal</span><strong id="cart-total"><?= money($total) ?></strong></div>
            <div><span>Payment</span><strong>Cash on Delivery</strong></div>
            <button class="btn btn-dark" type="submit">Update Cart</button>
            <a class="btn btn-primary" href="<?= url('checkout') ?>">Checkout</a>
        </aside>
    </form>
<?php endif; ?>

<?php require "../app/views/partials/footer.php"; ?>
