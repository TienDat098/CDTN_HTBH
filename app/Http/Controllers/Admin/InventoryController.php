<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index()
    {
        $logs = InventoryLog::with('product', 'user')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.inventory.index', compact('logs'));
    }
    //hiển thị form nhập hàng
    public function create()
    {
        $products=Product :: where('status',1)->get();
        return view('admin.inventory.create',compact('products'));
        
    }
    //xử lý lưu nhập hàng
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',//ít nhất 1 sp
        ]);

            $product = Product::findOrFail($request->product_id);
            //lấy số lượng hiện tại của sản phẩm
            $currentQuantity = $product->stock->quantity ?? 0;
            //cộng số lượng mới vào số kho
            $product->stock()->updateOrCreate(
            ['product_id' => $product->id],
            ['quantity' => $currentQuantity + $request->quantity]
        );
           //ghi log nhập kho
           
        InventoryLog::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'type' => 'import',
            'note' => $request->note ?? 'Nhập thêm hàng hóa vào kho', // Nếu có ghi chú thì lấy, không thì dùng câu mặc định
            'created_by' => auth()->id() ?? 1
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Nhập hàng thành công!Số lượng đã được cộng dồn vào kho.');
    }
}
