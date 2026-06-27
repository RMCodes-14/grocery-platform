<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Agar database khali hai, to manually data insert karo
        if (Product::count() === 0) {
            Product::create(['name' => "Olper's Milk 1L", 'category' => 'Dairy', 'price' => 290.00, 'stock_quantity' => 25]);
            Product::create(['name' => 'Dawn Bread Large', 'category' => 'Bakery', 'price' => 140.00, 'stock_quantity' => 15]);
            Product::create(['name' => 'Fresh Eggs 12pc', 'category' => 'Dairy', 'price' => 320.00, 'stock_quantity' => 40]);
            Product::create(['name' => 'Coca Cola 1.5L', 'category' => 'Beverages', 'price' => 190.00, 'stock_quantity' => 50]);
            Product::create(['name' => 'Lays Masala Family Pack', 'category' => 'Snacks', 'price' => 160.00, 'stock_quantity' => 30]);
        }

        // Ab database se data uthao
        $products = Product::latest()->get();
        
        return response()->json([
            'status' => 'success',
            'total_items' => $products->count(),
            'data' => $products
        ]);
    }
}