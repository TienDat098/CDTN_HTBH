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

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng trống!']);
        }

        try {
            DB::beginTransaction(); 

            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['qty'];
            }

            
            $orderCode = 'POS-' . strtoupper(Str::random(6)); 

            $order = Order::create([
                'order_code'     => $orderCode,
                'user_id'        => null, 
                'staff_id'       => Auth::id(), 
                'total_price'    => $totalAmount,
                'discount_amount'=> 0,
                'final_total'    => $totalAmount,
                'order_type'     => 'pos', 
                'payment_status' => 'paid', 
                'status'         => 'completed', 
            ]);

            // 2. Lưu chi tiết vào bảng order_items và trừ kho
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price']
                ]);

                // Trừ số lượng trong kho
                $product = Product::with('stock')->find($item['id']);
                if ($product && $product->stock) {
                    $product->stock->decrement('quantity', $item['qty']);
                }
            }

            DB::commit(); 
            return response()->json(['success' => true, 'message' => 'Thanh toán đơn hàng ' . $orderCode . ' thành công!']);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }

}
