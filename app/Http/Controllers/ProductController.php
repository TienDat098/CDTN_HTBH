<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    public function show($slug)
    {
        // Lấy sản phẩm và các quan hệ cần thiết cho giao diện chi tiết
        $product = Product::with([
            'variants' => function($query) {
                $query->where('status', 1);
            }, 
            'brand', 
            'category', 
            'images', // Kéo thêm ảnh để làm chức năng Zoom
            'stock'   // Kéo thêm tồn kho để check Còn hàng/Hết hàng
        ])->where('slug', $slug)->firstOrFail();

        // Lấy danh sách ảnh truyền ra View
        $images = $product->images;

        return view('product.show', compact('product', 'images'));
    }
}
