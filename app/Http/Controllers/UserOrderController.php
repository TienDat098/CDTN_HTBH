<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
class UserOrderController extends Controller
{
     public function index(Request $request)
    {
        // Lấy danh sách đơn hàng của user đang đăng nhập, mới nhất xếp lên đầu
        $orders = \App\Models\Order::where('user_id', $request->user()->id)
                    ->orderBy('id', 'desc')
                    ->paginate(10);

       return view('orders.index', compact('orders'));
    }
    
    public function show($id)
    {
        $order = \App\Models\Order::with(['items.product', 'items.variant', 'payment'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }
    public function reorder($id)
    {
        // 1. Kéo cả đơn hàng, kèm thông tin sản phẩm và biến thể
        $order = \App\Models\Order::with(['items.product', 'items.variant'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $cart = session()->get('cart', []);

        foreach ($order->items as $item) {
            $cartKey = $item->product_id . '_' . ($item->variant_id ?? '0');

            // --- KIỂM TRA LẤY GIÁ ---
            // Ưu tiên 1: Giá của biến thể (nếu có)
            // Ưu tiên 2: Giá của sản phẩm gốc
            // Ưu tiên 3 (Phòng hờ): Lấy chính cái giá lúc khách mua lưu trong bảng order_items
            $price = 0;
            if ($item->variant && $item->variant->price > 0) {
                $price = $item->variant->price;
            } elseif ($item->product && $item->product->price > 0) {
                $price = $item->product->price;
            } else {
                // Nếu sản phẩm/biến thể gốc bị xóa hoặc giá bằng 0, lấy lại giá cũ lúc mua
                $price = $item->price; 
            }

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $item->quantity;
            } else {
                $cart[$cartKey] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'name' => $item->product->name ?? 'Sản phẩm đã xóa', // Tránh lỗi nếu sản phẩm không còn
                    'price' => $price, // Gán biến $price đã xử lý ở trên vào đây
                    'quantity' => $item->quantity,
                    'image' => $item->product->thumbnail ?? '',
                    'variant_name' => $item->variant ? $item->variant->name : null
                ];
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm các sản phẩm vào giỏ hàng thành công!');
    }
}
