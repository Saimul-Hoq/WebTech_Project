<?php

class Cart extends Controller
{
    public function index()
    {
        $this->view('cart/index', [
            'title' => 'Shopping Cart',
            'items' => $this->cartItems(),
        ]);
    }

    public function add($id = null)
    {
        $product = $this->model('Product')->find((int) $id);
        if (!$product || !(int) $product['is_available']) {
            flash('error', 'This product is not available.');
            redirect('');
        }

        if ((int) $product['stock_quantity'] < 1) {
            flash('error', 'This product is currently out of stock.');
            redirect('');
        }

        $_SESSION['cart'] = $_SESSION['cart'] ?? [];
        $currentQty = (int) ($_SESSION['cart'][$product['id']] ?? 0);
        $_SESSION['cart'][$product['id']] = min($currentQty + 1, (int) $product['stock_quantity']);

        flash('success', 'Product added to cart.');
        redirect('cart');
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('cart');
        }

        $quantities = $_POST['qty'] ?? [];
        $_SESSION['cart'] = $_SESSION['cart'] ?? [];
        $products = $this->model('Product')->findMany(array_keys($quantities));
        $stock = [];

        foreach ($products as $product) {
            $stock[$product['id']] = (int) $product['stock_quantity'];
        }

        foreach ($quantities as $id => $qty) {
            $id = (int) $id;
            $qty = max(0, (int) $qty);

            if ($qty === 0) {
                unset($_SESSION['cart'][$id]);
                continue;
            }

            if (isset($stock[$id])) {
                $_SESSION['cart'][$id] = min($qty, $stock[$id]);
            }
        }

        flash('success', 'Cart updated.');
        redirect('cart');
    }

    public function remove($id = null)
    {
        unset($_SESSION['cart'][(int) $id]);
        flash('success', 'Product removed from cart.');
        redirect('cart');
    }

    private function cartItems()
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            return [];
        }

        $products = $this->model('Product')->findMany(array_keys($cart));
        foreach ($products as &$product) {
            $product['cart_qty'] = (int) ($cart[$product['id']] ?? 0);
            $product['line_total'] = $product['cart_qty'] * (float) $product['price'];
        }

        return $products;
    }
}
