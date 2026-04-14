<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy danh sách danh mục để hiện ở menu trái
        $globalCategories = \App\Models\Category::where('status', 1)->get();

        // 2. Khởi tạo truy vấn (Query Builder) cho sản phẩm đang bán
        $query = Product::where('status', 1);

        // ================= TÍNH NĂNG LỌC =================
        // A. Lọc theo giá thấp nhất (min_price)
        if ($request->filled('min_price')) {
            $query->where('sell_price', '>=', $request->min_price);
        }

        // B. Lọc theo giá cao nhất (max_price)
        if ($request->filled('max_price')) {
            $query->where('sell_price', '<=', $request->max_price);
        }

        // ================= TÍNH NĂNG SẮP XẾP =================
        // Bắt tham số 'sort' từ form select trên View
        $sort = $request->get('sort', 'new'); // Mặc định là 'new' (Mới nhất)

        if ($sort == 'price_asc') {
            $query->orderBy('sell_price', 'asc');   // Giá: Thấp đến Cao
        } elseif ($sort == 'price_desc') {
            $query->orderBy('sell_price', 'desc');  // Giá: Cao đến Thấp
        } else {
            $query->orderBy('id', 'desc');          // Mới nhất (Sắp xếp theo ID giảm dần)
        }

        // 3. Lấy dữ liệu và Phân trang (12 sản phẩm 1 trang)
        // Lưu ý: appends(request()->query()) ở bên file blade sẽ giúp giữ lại bộ lọc khi bạn bấm qua trang 2, trang 3
        $products = $query->paginate(12);
        
        // 4. Truyền dữ liệu ra View
        return view('product.index', compact('products', 'globalCategories'));
    }
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
