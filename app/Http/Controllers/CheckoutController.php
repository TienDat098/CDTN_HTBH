<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Models\InventoryLog;
use App\Models\Promotion;
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

        // Kiểm tra xem khách đã áp mã giảm giá trước đó chưa
        $promotion = session('promotion');
        
        // KIỂM TRA BẢO MẬT: Nhỡ khách quay lại giỏ hàng xóa bớt sản phẩm làm tổng tiền bị tụt xuống dưới mức tối thiểu thì sao?
        if ($promotion) {
            $promoCheck = Promotion::where('code', $promotion['code'])->first();
            // Nếu không đủ điều kiện nữa -> Tự động xóa mã khỏi đơn
            if (!$promoCheck || $total < $promoCheck->min_order_value) {
                session()->forget('promotion');
                $promotion = null;
            } else {
                // Nếu là mã giảm theo %, phải tính lại số tiền giảm nếu giỏ hàng thay đổi
                $discountAmount = $promoCheck->discount_type == 'percent' 
                    ? $total * ($promoCheck->discount_value / 100) 
                    : $promoCheck->discount_value;
                    
                if ($discountAmount > $total) $discountAmount = $total;
                
                $promotion['discount_amount'] = $discountAmount;
                session()->put('promotion', $promotion);
            }
        }

        $discountAmount = $promotion ? $promotion['discount_amount'] : 0;
        $finalTotal = $total - $discountAmount;
        if($finalTotal < 0) $finalTotal = 0; // Không để tiền âm
       
        return view('checkout.index', compact('cart', 'total', 'discountAmount', 'finalTotal', 'promotion'));
    }

    // HÀM MỚI: Xử lý Ajax áp dụng mã giảm giá
    public function applyPromotion(Request $request)
    {
        $code = strtoupper($request->code);
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng trống.']);
        }

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Xóa mã cũ ngay lập tức mỗi khi khách thử nhập mã mới
        session()->forget('promotion');

        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại!', 'final_total_formatted' => number_format($total) . 'đ']);
        }

        if ($promotion->status != 1 || now()->lt($promotion->start_date) || now()->gt($promotion->end_date)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn hoặc chưa kích hoạt.', 'final_total_formatted' => number_format($total) . 'đ']);
        }

        // Kiểm tra số lượng lượt dùng
        if ($promotion->used_count >= $promotion->quantity) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết số lượt sử dụng.', 'final_total_formatted' => number_format($total) . 'đ']);
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($total < $promotion->min_order_value) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($promotion->min_order_value) . 'đ', 'final_total_formatted' => number_format($total) . 'đ']);
        }

        //  Tính toán tiền giảm
        $discountAmount = 0;
        if ($promotion->discount_type == 'percent') {
            $discountAmount = $total * ($promotion->discount_value / 100);
        } else {
            $discountAmount = $promotion->discount_value;
        }

        if ($discountAmount > $total) {
            $discountAmount = $total;
        }

        // 6. Lưu vào session
        session()->put('promotion', [
            'id' => $promotion->id,
            'code' => $promotion->code,
            'discount_amount' => $discountAmount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã thành công!',
            'discount_amount_formatted' => number_format($discountAmount) . 'đ',
            'final_total_formatted' => number_format($total - $discountAmount) . 'đ'
        ]);
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
        $promotion = session('promotion');
        $discountAmount = $promotion ? $promotion['discount_amount'] : 0;
        $finalTotal = $total - $discountAmount;
        if($finalTotal < 0) $finalTotal = 0;
        DB::beginTransaction();

        try {
            // TẠO ĐƠN HÀNG MỚI TRONG BẢNG orders
            $order = Order::create([
                'order_code' => 'ORD' . strtoupper(Str::random(8)),
                'qr_code' => 'QR' . time() . rand(100,999), 
                'user_id' => auth()->check() ? auth()->id() : null,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->phone,
                'promotion_id' => $promotion ? $promotion['id'] : null, // Lưu ID khuyến mãi
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
            // NẾU CÓ DÙNG MÃ GIẢM GIÁ -> TĂNG SỐ LƯỢT ĐÃ DÙNG (USED_COUNT) LÊN 1
            if ($promotion) {
                Promotion::where('id', $promotion['id'])->increment('used_count');
                session()->forget('promotion'); // Xóa mã khỏi giỏ hàng
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