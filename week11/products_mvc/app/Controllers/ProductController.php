<?php

namespace App\Controllers;

use Core\Controller;

class ProductController extends Controller {
    // Static storage for demo purposes
    private static $products = [
        ['id' => 1, 'name' => 'MacBook Pro', 'price' => 2500],
        ['id' => 2, 'name' => 'iPhone 15', 'price' => 1000],
    ];

    public function index() {
        return $this->view('products/index', ['products' => self::$products]);
    }

    public function create() {
        return $this->view('products/create');
    }

    public function store() {
        // In a real app, we'd save to DB
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        
        // Simulating success
        header('Location: /products');
        exit;
    }
}
