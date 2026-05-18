<?php

class Products extends Controller
{
    public function show($id = null)
    {
        $productModel = $this->model('Product');
        $product = $productModel->find((int) $id);

        if (!$product) {
            flash('error', 'Product not found.');
            redirect('');
        }

        $this->view('products/show', [
            'title' => $product['name'],
            'product' => $product,
        ]);
    }
}
