<?php

class Wishlist extends Controller
{
    public function index()
    {
        require_customer();
        $items = $this->model('WishlistModel')->allForUser(current_user()['id']);

        $this->view('account/wishlist', [
            'title' => 'Wishlist',
            'items' => $items,
        ]);
    }

    public function add($id = null)
    {
        require_customer();
        $this->model('WishlistModel')->add(current_user()['id'], (int) $id);
        flash('success', 'Product saved to wishlist.');
        redirect('wishlist');
    }

    public function remove($id = null)
    {
        require_customer();
        $this->model('WishlistModel')->remove(current_user()['id'], (int) $id);
        flash('success', 'Product removed from wishlist.');
        redirect('wishlist');
    }
}
