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
use App\Models\Payment;
class CheckoutController extends Controller
{
    private $payos_config = [
        "client_id" => "51569971-2423-4c84-8239-a6cc3f4a94cb",
        "api_key" => "9dfe8a71-1c01-4e95-ad68-db8b2856233d",
        "checksum_key" => "e79303ffa96438e65bb837b3a4dc34752e8c1e1bd90bf7f0474efeab2eca7c69"
    ];
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
        // 1. TRẢ LẠI CÂU BÁO LỖI TIẾNG VIỆT CỦA BẠN
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,payos' 
        ], [
            'customer_name.required' => 'Vui lòng nhập Họ tên.',
            'phone.required' => 'Vui lòng nhập Số điện thoại.',
            'shipping_address.required' => 'Vui lòng nhập Địa chỉ giao hàng.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('home');

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
            // TẠO ĐƠN HÀNG (Mã đơn dùng time() để đảm bảo PayOS đọc được số)
            $orderCodeString = 'ORD' . time() . rand(10,99); 
            $order = Order::create([
                'order_code' => $orderCodeString,
                'qr_code' => 'QR' . time() . rand(100,999), 
                'user_id' => auth()->check() ? auth()->id() : null,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->phone,
                'promotion_id' => $promotion ? $promotion['id'] : null, 
                'order_type' => 'online',
                'shipping_address' => $request->shipping_address, 
                'note' => $request->note,
                'status' => ($request->payment_method == 'payos') ? 'pending_payment' : 'pending', 
                
                // FIX LỖI DB CỦA BẠN: Lưu đúng tiền giảm giá thay vì số 0
                'total_price' => $total,
                'discount_amount' => $discountAmount,
                'final_total' => $finalTotal,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $finalTotal,
                'status' => 'pending' 
            ]);

            // LƯU CHI TIẾT
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null, 
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Trừ kho thực tế
                $product = Product::with('stock')->find($item['product_id']);
                if ($product && $product->stock) {
                    $product->stock->decrement('quantity', $item['quantity']);
                }

                InventoryLog::create([
                    'product_id' => $item['product_id'],
                    'reference_id' => $order->id,
                    'quantity' => -$item['quantity'],
                    'type' => 'XUẤT KHO',
                    'note' => 'Xuất bán Online: ' . $order->order_code,
                    'created_by' => auth()->id()
                ]);
            }

            if ($promotion) {
                Promotion::where('id', $promotion['id'])->increment('used_count');
                session()->forget('promotion');
            }

            DB::commit();

            // 2. UX THÔNG MINH: CHIA LUỒNG XÓA GIỎ HÀNG
            if ($request->payment_method == 'payos') {
                // Nếu là PayOS -> KHÔNG XÓA GIỎ HÀNG VỘI, lỡ khách hủy ngang thì vẫn còn đồ để mua lại
                return $this->processPayOS($order->order_code, $finalTotal);
            } else {
                // Nếu là COD -> Chốt đơn -> Xóa giỏ hàng luôn
                session()->forget('cart');
                return redirect()->route('checkout.success')->with('order_code', $order->order_code);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi: ' . $e->getMessage());
        }
    }

    //HÀM GỌI API TẠO LINK PAYOS
    private function processPayOS($orderCode, $amount) {
        $url = "https://api-merchant.payos.vn/v2/payment-requests";
        
        // PayOS bắt buộc OrderCode phải là SỐ NGUYÊN < 9007199254740991. 
        // Mình sẽ lấy các con số từ chuỗi 'ORD...' ra để làm mã giao dịch
        $orderCodeInt = (int)preg_replace('/\D/', '', $orderCode);

        // Đảm bảo số tiền tối thiểu của PayOS là 2000đ (Quy định của ngân hàng)
        $payAmount = ($amount < 2000) ? 2000 : (int)$amount;

        $data = [
            "orderCode" => $orderCodeInt,
            "amount" => $payAmount,
            "description" => "TT Don " . $orderCodeInt, 
            "cancelUrl" => route('checkout.payos_return', ['orderCode' => $orderCode, 'status' => 'CANCELLED']), 
            "returnUrl" => route('checkout.payos_return', ['orderCode' => $orderCode, 'status' => 'PAID']) 
        ];

        // Tạo chữ ký
        ksort($data);
        $signData = "";
        foreach ($data as $key => $value) {
            $signData .= $key . "=" . $value . "&";
        }
        $signData = rtrim($signData, "&");
        $data['signature'] = hash_hmac('sha256', $signData, $this->payos_config['checksum_key']);

        // Gửi cURL
        $headers = [
            "x-client-id: " . $this->payos_config['client_id'],
            "x-api-key: " . $this->payos_config['api_key'],
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            // Cập nhật lỗi
            Order::where('order_code', $orderCode)->update(['status' => 'cancelled', 'note' => 'Lỗi cURL PayOS']);
            return redirect()->route('checkout.index')->with('error', 'Lỗi kết nối PayOS: ' . $error);
        }
        
        $result = json_decode($response, true);

        if (isset($result['code']) && $result['code'] == '00') {
            // Trả về link trang quét mã QR của PayOS
            return redirect($result['data']['checkoutUrl']);
        } else {
            // Lỗi API từ PayOS (Sai config, sai mã...)
            Order::where('order_code', $orderCode)->update(['status' => 'cancelled', 'note' => 'Lỗi API PayOS']);
            $msg = $result['desc'] ?? 'Lỗi không xác định từ cổng thanh toán';
            return redirect()->route('checkout.index')->with('error', "PayOS Error: " . $msg);
        }
    }
    //HÀM ĐÓN KẾT QUẢ KHI KHÁCH QUÉT MÃ XONG
    public function payosReturn(Request $request) {
        $status = $request->get('status');
        $orderCode = $request->get('orderCode'); // Lấy mã gốc dạng 'ORD...' từ URL
        
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($status == 'PAID') {
            // Khách trả tiền thành công
            $order->update(['status' => 'processing']); 
            Payment::where('order_id', $order->id)->update(['status' => 'completed']);
            
            // XÓA GIỎ HÀNG TẠI ĐÂY LÀ CHUẨN NHẤT
            session()->forget('cart'); 
            
            return redirect()->route('checkout.success')->with('order_code', $orderCode);
        } else {
            // Khách bấm Hủy thanh toán -> Hủy đơn và Trả lại kho
            $order->update(['status' => 'cancelled', 'note' => 'Khách hủy thanh toán qua PayOS.']);
            Payment::where('order_id', $order->id)->update(['status' => 'failed']);
            
            // Hoàn kho (Trả lại số lượng)
            $details = OrderItem::where('order_id', $order->id)->get();
            foreach($details as $item) {
                $product = Product::with('stock')->find($item->product_id);
                if($product && $product->stock) {
                    $product->stock->increment('quantity', $item->quantity);
                }
                
                InventoryLog::create([
                    'product_id' => $item->product_id,
                    'reference_id' => $order->id,
                    'quantity' => $item->quantity,
                    'type' => 'NHẬP KHO',
                    'note' => 'Hoàn kho do hủy thanh toán PayOS: ' . $orderCode,
                    'created_by' => auth()->id()
                ]);
            }
            
            // Trả về trang checkout với lỗi, giỏ hàng vẫn còn nguyên để khách thử lại
            return redirect()->route('checkout.index')->with('error', 'Thanh toán đã bị hủy. Vui lòng thử lại.');
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