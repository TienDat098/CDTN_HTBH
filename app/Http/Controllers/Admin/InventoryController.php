<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;  
use App\Models\ProductVariant; 
class InventoryController extends Controller
{
    public function index(Request $request)
    {
        
        $query = InventoryLog::with(['product.stock', 'user']);

        // Lọc theo từ khóa (Tìm tên sản phẩm)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('product', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }

        // Lọc theo loại (Nhập / Xuất)
        if ($request->filled('type')) {
            $type = $request->type;
            if ($type == 'import') {
                $query->whereIn('type', ['import', 'NHẬP KHO', 'Nhập kho']);
            } elseif ($type == 'export') {
                $query->whereIn('type', ['export', 'XUẤT KHO', 'Xuất kho']);
            }
        }

        // Sắp xếp mới nhất và phân trang 15 dòng
        $logs = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.inventory.index', compact('logs'));
    }
    // Hiển thị form nhập hàng
    public function create()
    {
        // Lấy sản phẩm kèm theo các biến thể của nó
        $products = Product::with(['variants', 'stock'])->where('status', 1)->get();
        
        return view('admin.inventory.create', compact('products'));
    }

    // Xử lý lưu nhập hàng
    public function store(Request $request)
    {
        $request->validate([
            'item_data' => 'required', // Nhận chuỗi dạng "productID-variantID"
            'quantity' => 'required|integer|min:1',
        ]);

        // Tách ID: Ví dụ "5-0" (Sản phẩm 5, không biến thể) hoặc "5-12" (Sản phẩm 5, biến thể 12)
        $parts = explode('-', $request->item_data);
        $productId = $parts[0];
        $variantId = $parts[1] == '0' ? null : $parts[1];

        // 1. Cập nhật tồn kho trong bảng product_stocks
        $stock = \App\Models\ProductStock::updateOrCreate(
            ['product_id' => $productId, 'variant_id' => $variantId],
            ['quantity' => \DB::raw("quantity + $request->quantity")]
        );

        // 2. Nếu là biến thể, phải cập nhật cả số lượng trong bảng product_variants cho đồng bộ
        if ($variantId) {
            \App\Models\ProductVariant::where('id', $variantId)->increment('stock_quantity', $request->quantity);
        }

        // 3. Lấy lại số lượng mới nhất sau khi cộng để ghi Log
        $newStock = \App\Models\ProductStock::where('product_id', $productId)
                    ->where('variant_id', $variantId)->first();

        // 4. Ghi log nhập kho
        \App\Models\InventoryLog::create([
            'product_id' => $productId,
            'quantity' => $request->quantity,
            'balance_after' => $newStock->quantity,
            'type' => 'import',
            'note' => $request->note ?? 'Nhập thêm hàng hóa vào kho',
            'created_by' => auth()->id() ?? 1
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Nhập kho thành công!');
    }
}
