<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['images', 'stock'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(8);

        //danh mục nổi bật
         $categories = Category::where('status', 1)
        ->latest()
        ->take(10)
        ->get();
        //Sản phẩm bán chạy 
        $bestSellers = Product::with(['images'])
        ->where('status', 1)
        ->withSum(['orderItems as total_sold' => function ($q) {
            $q->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed');
        }], 'quantity')
        ->orderByDesc('total_sold')
        ->take(8)
        ->get();

        return view('home', compact('products','categories','bestSellers'));
    }
}