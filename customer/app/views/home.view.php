<?php require "../app/views/partials/header.php"; ?>

<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">Customer marketplace</p>
        <h1>Shop useful products from trusted sellers.</h1>
        <p>Browse available products, add them to your cart, and checkout with cash on delivery.</p>
        <a class="btn btn-primary" href="#products">Start shopping</a>
    </div>
    <div class="hero-panel">
        <strong><?= count($products) ?></strong>
        <span>products available now</span>
    </div>
</section>

<section class="toolbar" id="products">
    <div>
        <h2>Products</h2>
        <p>Fresh listings from approved marketplace sellers.</p>
    </div>
    <form class="filters" method="get" action="<?= url('') ?>">
        <input type="hidden" name="url" value="home">
        <select name="category">
            <option value="">All categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>" <?= (string) $filters['category'] === (string) $category['id'] ? 'selected' : '' ?>>
                    <?= esc($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="search" name="q" placeholder="Search" value="<?= esc($filters['q']) ?>">
        <button class="btn btn-dark" type="submit">Filter</button>
    </form>
</section>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <h3>No products found</h3>
        <p>Try another search or category.</p>
    </div>
<?php else: ?>
    <section class="product-grid">
        <?php foreach ($products as $product): ?>
            <article class="product-card">
                <a href="<?= url('products/show/' . $product['id']) ?>" class="product-image">
                    <img src="<?= product_image($product['primary_image']) ?>" alt="<?= esc($product['name']) ?>">
                </a>
                <div class="product-body">
                    <p class="meta"><?= esc($product['category_name']) ?> · <?= esc($product['shop_name']) ?></p>
                    <h3><a href="<?= url('products/show/' . $product['id']) ?>"><?= esc($product['name']) ?></a></h3>
                    <div class="product-bottom">
                        <strong><?= money($product['price']) ?></strong>
                        <span><?= (int) $product['stock_quantity'] ?> in stock</span>
                    </div>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="<?= url('cart/add/' . $product['id']) ?>">Add to Cart</a>
                        <?php if (is_logged_in()): ?>
                            <a class="btn btn-ghost" href="<?= url('wishlist/add/' . $product['id']) ?>">Wishlist</a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php require "../app/views/partials/footer.php"; ?>
