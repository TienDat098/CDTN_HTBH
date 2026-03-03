<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 12 sản phẩm mới nhất kèm ảnh để hiển thị
        $products = Product::with('images')->orderBy('id', 'desc')->paginate(12);
        return view('home', compact('products'));
    }
}