<?php

class Orders extends Controller
{
    public function index()
    {
        require_customer();

        $this->view('orders/index', [
            'title' => 'Order History',
            'orders' => $this->model('Order')->allForUser(current_user()['id']),
        ]);
    }
}
