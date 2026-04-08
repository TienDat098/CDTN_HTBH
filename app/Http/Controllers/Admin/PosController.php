<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order; 
use App\Models\OrderItem; 
use App\Models\User;
use App\Models\InventoryLog;
use App\Models\LoyaltyPoint;
use Illuminate\Support\Facades\Hash;
class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('stock')->where('status', 1)->get();
        return view('admin.pos.index', compact('products'));
    }
    public function checkout(Request $request)
    {
        $cart = $request->input('cart');
        $phone = $request->input('phone');
        $usedPoints = (int) $request->input('used_points', 0);

        if (empty($cart)) return response()->json(['success' => false, 'message' => 'Giỏ hàng trống!']);

        try {
            DB::beginTransaction(); 

            //  TÌM HOẶC TẠO KHÁCH HÀNG QUA SĐT
            $user = null;
            if (!empty($phone)) {
                $user = User::firstOrCreate(
                    ['phone' => $phone],
                    [
                        'name' => 'Khách hàng ' . $phone,
                        'email' => $phone . '@khachhang.com', // Cột email thường bắt buộc nên fake tạm
                        'password' => Hash::make(Str::random(10)),
                        'role' => 'user'
                    ]
                );
            }

            //  TÍNH TOÁN TIỀN VÀ KIỂM TRA LUẬT 30%
            $subTotal = 0;
            foreach ($cart as $item) $subTotal += $item['price'] * $item['qty'];

            $discountAmount = $usedPoints * 100; 
            $finalTotal = $subTotal - $discountAmount;

            // Chặn hack từ frontend: Quá 30% là báo lỗi
            if ($discountAmount > ($subTotal * 0.3)) {
                throw new \Exception("Lỗi: Không được giảm giá vượt quá 30% giá trị đơn!");
            }

            // Chặn hack: Khách không đủ điểm
            if ($user && $usedPoints > 0 && $user->points_balance < $usedPoints) {
                throw new \Exception("Khách hàng chỉ còn " . $user->points_balance . " điểm!");
            }

            //  TẠO ĐƠN HÀNG
            $order = Order::create([
                'order_code'     => 'POS-' . strtoupper(Str::random(6)),
                'user_id'        => $user ? $user->id : null,
                'staff_id'       => Auth::id(),
                'total_price'    => $subTotal,
                'discount_amount'=> $discountAmount,
                'final_total'    => $finalTotal,
                'order_type'     => 'pos',
                'status'         => 'completed',
            ]);

            DB::table('payments')->insert([
                'order_id' => $order->id,
                'payment_method' => 'cash', // Đơn POS tại quầy mặc định thu tiền mặt
                'amount' => $finalTotal,
                'status' => 'completed', 
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // LƯU CHI TIẾT SẢN PHẨM + TRỪ KHO + GHI LOG
            foreach ($cart as $item) {
                // Lưu vào order_items
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price']
                ]);

                // Trừ tồn kho
                $product = Product::with('stock')->find($item['id']);
                if ($product && $product->stock) {
                    $product->stock->decrement('quantity', $item['qty']);
                }

                // Ghi vào bảng Lịch sử Nhập/Xuất kho
                InventoryLog::create([
                    'product_id' => $item['id'],
                    'reference_id' => $order->id,
                    'quantity' => -$item['qty'], // Số âm thể hiện xuất kho
                    'type' => 'XUẤT KHO',
                    'note' => 'Xuất bán POS tại quầy: ' . $order->order_code,
                    'created_by' => Auth::id() 
                ]);
            }

            //  LƯU SỔ CÁI TÍCH ĐIỂM (CỘNG / TRỪ ĐIỂM)
            if ($user) {
                $currentBalance = $user->points_balance;

                // TRỪ ĐIỂM (Nếu có dùng)
                if ($usedPoints > 0) {
                    $currentBalance -= $usedPoints;
                    LoyaltyPoint::create([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'type' => 'redeem',
                        'points' => -$usedPoints, // Điểm âm
                        'balance_after' => $currentBalance,
                        'reason' => 'Tiêu điểm cho đơn hàng ' . $order->order_code,
                    ]);
                }

                // CỘNG ĐIỂM (Dựa trên số tiền thực trả: 10k = 1đ)
                $earnedPoints = floor($finalTotal / 10000); 
                if ($earnedPoints > 0) {
                    $currentBalance += $earnedPoints;
                    LoyaltyPoint::create([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'type' => 'earn',
                        'points' => $earnedPoints, // Điểm dương
                        'balance_after' => $currentBalance,
                        'reason' => 'Tích điểm từ đơn hàng ' . $order->order_code,
                    ]);
                }
            }

            DB::commit(); 
            return response()->json(['success' => true, 'message' => 'Thanh toán thành công!']);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // Hàm này sẽ nhận SĐT và trả về Tên + Điểm của khách
    public function checkCustomer(Request $request)
    {
        $phone = $request->input('phone');
        if (!$phone) {
            return response()->json(['success' => false]);
        }

        // Tìm khách trong DB
        $user = \App\Models\User::where('phone', $phone)->first();
        
        if ($user) {
            return response()->json([
                'success' => true,
                'name' => $user->name,
                'points' => $user->points_balance 
            ]);
        }

        
        return response()->json(['success' => false]);
    }

}
