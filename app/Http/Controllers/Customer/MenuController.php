<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        $products = Product::where('is_available', true)->get();

        return view('customer.menu', compact('categories', 'products'));
    }
}