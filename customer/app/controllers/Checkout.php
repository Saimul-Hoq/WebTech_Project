<?php

class Checkout extends Controller
{
    public function index()
    {
        require_customer();

        $items = $this->cartItems();
        if (empty($items)) {
            flash('error', 'Your cart is empty.');
            redirect('cart');
        }

        $this->view('cart/checkout', [
            'title' => 'Checkout',
            'items' => $items,
        ]);
    }

    public function place()
    {
        require_customer();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('checkout');
        }

        $address = trim($_POST['shipping_address'] ?? '');
        if ($address === '') {
            flash('error', 'Shipping address is required.');
            redirect('checkout');
        }

        $items = $this->cartItems();
        if (empty($items)) {
            flash('error', 'Your cart is empty.');
            redirect('cart');
        }

        foreach ($items as $item) {
            if ((int) $item['cart_qty'] > (int) $item['stock_quantity']) {
                flash('error', $item['name'] . ' has less stock than requested.');
                redirect('cart');
            }
        }

        $orderId = $this->model('Order')->create(current_user()['id'], $address, $items);
        unset($_SESSION['cart']);

        flash('success', 'Order #' . $orderId . ' placed successfully.');
        redirect('orders');
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
