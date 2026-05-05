<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
class StoreController extends Controller
{
    public function index()
    {
        //  Lấy danh mục
        $categories = Category::where('status', 1)->get();

        //  Lấy sản phẩm bán chạy 
        $bestSellers = Product::where('status', 1)->orderBy('total_sold', 'desc')->take(4)->get();

        //  Lấy sản phẩm hiển thị chung
        $products = Product::where('status', 1)->orderBy('id', 'desc')->paginate(12);

        //  LẤY MÃ GIẢM GIÁ HOT 
        $activePromotions = Promotion::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereRaw('used_count < quantity')
            ->orderBy('id', 'desc') 
            ->take(6)               
            ->get();

        return view('home', compact('categories', 'bestSellers', 'products', 'activePromotions'));
    }
}
