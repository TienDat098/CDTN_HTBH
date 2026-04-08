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

        //  Lấy sản phẩm bán chạy (Ví dụ lấy 4 sản phẩm)
        $bestSellers = Product::where('status', 1)->orderBy('total_sold', 'desc')->take(4)->get();

        //  Lấy sản phẩm hiển thị chung
        $products = Product::where('status', 1)->orderBy('id', 'desc')->paginate(12);

        //  LẤY MÃ GIẢM GIÁ HOT 
        $activePromotions = Promotion::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereRaw('used_count < quantity')
            ->orderBy('id', 'desc') // Ưu tiên xếp mã mới tạo lên trước
            ->take(6)               // CHỈ LẤY ĐÚNG 6 MÃ ĐỂ HIỂN THỊ ĐẸP TRÊN 1 HÀNG NGANG
            ->get();

        return view('home', compact('categories', 'bestSellers', 'products', 'activePromotions'));
    }
}
