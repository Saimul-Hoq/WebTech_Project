    </main>

    <footer class="site-footer">
        <div>
            <h3><?= APP_NAME ?></h3>
            <p>Quality products, simple checkout, and customer-first shopping.</p>
        </div>
        <div>
            <h4>Customer Care</h4>
            <a href="<?= url('orders') ?>">Order history</a>
            <a href="<?= url('wishlist') ?>">Wishlist</a>
            <a href="<?= url('account/profile') ?>">Account</a>
        </div>
        <div>
            <h4>Shop</h4>
            <a href="<?= url('') ?>">All products</a>
            <a href="<?= url('cart') ?>">Cart</a>
            <a href="<?= url('checkout') ?>">Checkout</a>
        </div>
        <div>
            <h4>Contact</h4>
            <p>Dhaka, Bangladesh</p>
            <p>support@shophub.local</p>
        </div>
    </footer>
    <script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>
