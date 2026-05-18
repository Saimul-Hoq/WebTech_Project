<?php

class Home extends Controller
{
    public function index()
    {
        $productModel = $this->model('Product');
        $filters = [
            'q' => trim($_GET['q'] ?? ''),
            'category' => $_GET['category'] ?? '',
        ];

        $this->view('home', [
            'title' => 'Fresh finds for every cart',

            'products' => $productModel->featured($filters),

            'categories' => $productModel->categories(),
            
            'filters' => $filters,
        ]);
    }
}
