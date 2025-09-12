<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    // danh sách sản phẩm
    public function index()
    {
        $products = Product::latest()->paginate(12);
        return view('user.products.index', compact('products'));
    }

    // chi tiết sản phẩm
    public function show(Product $product)
    {
        return view('user.products.show', compact('product'));
    }
}
