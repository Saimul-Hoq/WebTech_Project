<?php require "../app/views/partials/header.php"; ?>

<section class="product-detail">
    <div class="detail-image">
        <img src="<?= product_image($product['primary_image']) ?>" alt="<?= esc($product['name']) ?>">
    </div>
    <div class="detail-info">
        <p class="eyebrow"><?= esc($product['category_name']) ?> · <?= esc($product['shop_name']) ?></p>
        <h1><?= esc($product['name']) ?></h1>
        <p class="price"><?= money($product['price']) ?></p>
        <p><?= nl2br(esc($product['description'])) ?></p>
        <p class="stock"><?= (int) $product['stock_quantity'] ?> items in stock</p>
        <div class="card-actions">
            <a class="btn btn-primary" href="<?= url('cart/add/' . $product['id']) ?>">Add to Cart</a>
            <?php if (is_logged_in()): ?>
                <a class="btn btn-ghost" href="<?= url('wishlist/add/' . $product['id']) ?>">Save to Wishlist</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require "../app/views/partials/footer.php"; ?>
