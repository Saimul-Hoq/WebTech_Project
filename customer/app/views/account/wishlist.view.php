<?php require "../app/views/partials/header.php"; ?>

<section class="page-heading">
    <h1>Wishlist</h1>
    <p>Products you saved for later.</p>
</section>

<?php if (empty($items)): ?>
    <div class="empty-state">
        <h3>No saved products yet</h3>
        <a class="btn btn-primary" href="<?= url('') ?>">Explore products</a>
    </div>
<?php else: ?>
    <section class="product-grid">
        <?php foreach ($items as $item): ?>
            <article class="product-card">
                <a href="<?= url('products/show/' . $item['product_id']) ?>" class="product-image">
                    <img src="<?= product_image($item['primary_image']) ?>" alt="<?= esc($item['name']) ?>">
                </a>
                <div class="product-body">
                    <p class="meta"><?= esc($item['shop_name']) ?></p>
                    <h3><?= esc($item['name']) ?></h3>
                    <div class="product-bottom">
                        <strong><?= money($item['price']) ?></strong>
                        <span><?= (int) $item['stock_quantity'] ?> in stock</span>
                    </div>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="<?= url('cart/add/' . $item['product_id']) ?>">Add to Cart</a>
                        <a class="btn btn-ghost" href="<?= url('wishlist/remove/' . $item['product_id']) ?>">Remove</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php require "../app/views/partials/footer.php"; ?>
