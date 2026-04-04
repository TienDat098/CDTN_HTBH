<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Models\InventoryLog;
class CheckoutController extends Controller
{
    
    public function index()
    {
        $cart = session()->get('cart', []);
        
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Tính tổng tiền
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

       
        return view('checkout.index', compact('cart', 'total'));
    }

    //lưu dữ liệu vào db
    public function process(Request $request)
    {
        
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
        ], [
            'customer_name.required' => 'Vui lòng nhập Họ tên.',
            'phone.required' => 'Vui lòng nhập Số điện thoại.',
            'shipping_address.required' => 'Vui lòng nhập Địa chỉ giao hàng.',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home');
        }

        // Tính tổng tiền final
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction();

        try {
            // TẠO ĐƠN HÀNG MỚI TRONG BẢNG orders
            $order = Order::create([
                'order_code' => 'ORD' . strtoupper(Str::random(8)),
                'qr_code' => 'QR' . time() . rand(100,999), 
                'user_id' => auth()->check() ? auth()->id() : null,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->phone,
                
                'order_type' => 'online',
                'shipping_address' => $request->shipping_address, 
                'note' => $request->note,
                'status' => 'pending', 
                'total_price' => $total,
                'discount_amount' => 0,
                'final_total' => $total,
            ]);

        // LƯU CHI TIẾT ĐƠN HÀNG + TRỪ KHO + GHI LOG
            foreach ($cart as $item) {
                // 1. Lưu sản phẩm vào đơn
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null, 
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // 2. Trừ tồn kho thực tế
                $product = Product::with('stock')->find($item['product_id']);
                if ($product && $product->stock) {
                    $product->stock->decrement('quantity', $item['quantity']);
                }

                // 3. Ghi vào bảng Lịch sử Nhập/Xuất kho
                InventoryLog::create([
                    'product_id' => $item['product_id'],
                    'reference_id' => $order->id, // Lưu ID đơn hàng để đối chiếu
                    'quantity' => -$item['quantity'], // Số âm thể hiện xuất kho
                    'type' => 'XUẤT KHO',
                    'note' => 'Xuất bán đơn hàng Online: ' . $order->order_code,
                    'created_by' => auth()->id() // Ai đặt hàng thì ghi người đó, khách vãng lai sẽ là null
                ]);
            }
             DB::commit();
                session()->forget('cart');
                return redirect()->route('checkout.success')->with('order_code', $order->order_code);
        } catch (\Exception $e) {
            DB::rollBack(); // Nếu có lỗi (ví dụ hết hàng) thì hủy toàn bộ, không tạo đơn
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage());
        }
    }

    // 3. Trang thông báo thành công
    public function success()
    {
        if (!session('order_code')) {
            return redirect()->route('home');
        }
        return view('checkout.success');
    }
}