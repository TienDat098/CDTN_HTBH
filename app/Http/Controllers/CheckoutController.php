<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

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

        // LƯU CHI TIẾT ĐƠN HÀNG TRONG BẢNG order_items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null, 
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        session()->forget('cart');

        return redirect()->route('checkout.success')->with('order_code', $order->order_code);
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