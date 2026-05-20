<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductStock; 
use App\Models\InventoryLog;
use App\Models\Payment;
use App\Models\OrderStatusHistory;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query và tải kèm các bảng liên quan để tối ưu tốc độ load
        $query = Order::with(['staff', 'user', 'payment']);

        //  NGHIỆP VỤ TÌM KIẾM 
        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function ($q) use ($kw) {
                $q->where('order_code', 'like', "%{$kw}%")
                  ->orWhere('customer_name', 'like', "%{$kw}%")
                  ->orWhere('customer_phone', 'like', "%{$kw}%")
                  ->orWhereHas('user', function ($userQuery) use ($kw) {
                      $userQuery->where('name', 'like', "%{$kw}%")
                                ->orWhere('phone', 'like', "%{$kw}%");
                  });
            });
        }

        //  NGHIỆP VỤ LỌC THEO TRẠNG THÁI
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        //  NGHIỆP VỤ SẮP XẾP
        $sort = $request->input('sort', 'newest'); 
        switch ($sort) {
            case 'oldest':
                $query->orderBy('id', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('final_total', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('final_total', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        // Phân trang 
        $orders = $query->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'items.variant', 'staff', 'user', 'statusHistory' => function($q){
            $q->latest();
        }])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $newStatus = $request->status;

        // Tránh tình trạng submit rỗng hoặc cập nhật lại trạng thái cũ
        if ($newStatus == $order->status || empty($newStatus)) {
            return redirect()->back();
        }

        //  NGHIỆP VỤ HỦY ĐƠN VÀ HOÀN KHO
        if ($newStatus == 'cancelled') {
            foreach ($order->items as $item) {
                // 1. Cộng lại số lượng vào kho (ProductStock)
                $stockQuery = ProductStock::where('product_id', $item->product_id)
                                          ->where('variant_id', $item->variant_id);
                $stockQuery->increment('quantity', $item->quantity);

                // 2. (Quan trọng) Đồng bộ luôn vào bảng ProductVariant nếu có biến thể
                if ($item->variant_id) {
                    \App\Models\ProductVariant::where('id', $item->variant_id)
                        ->increment('stock_quantity', $item->quantity);
                }

                // 3. Lấy ra số dư tồn kho MỚI NHẤT sau khi đã cộng
                $freshStock = $stockQuery->first();
                $balanceAfter = $freshStock ? $freshStock->quantity : $item->quantity;

                // 4. Ghi Log và ĐƯA BIẾN $balanceAfter VÀO ĐÚNG CỘT
                InventoryLog::create([
                    'product_id'    => $item->product_id,
                    // 'variant_id' => $item->variant_id, // Mở comment nếu bảng InventoryLog của bạn có cột này
                    'reference_id'  => $order->id,
                    'quantity'      => $item->quantity,
                    'balance_after' => $balanceAfter, // Dòng này là chìa khóa để hết bị số 0
                    'type'          => 'import',
                    'note'          => 'Hoàn kho do Hủy đơn hàng ' . $order->order_code,
                    'created_by'    => auth()->id() ?? 1
                ]);
            }
        }

        //  NGHIỆP VỤ GIAO THÀNH CÔNG -> TỰ ĐỘNG ĐÃ THANH TOÁN
        if ($newStatus == 'completed') {
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method' => 'cod',
                    'amount' => $order->final_total, 
                    'status' => 'completed',
                    'transaction_code' => 'COD_' . time()
                ]
            );
        }

        // 3. Cập nhật trạng thái cho Order
        $order->update(['status' => $newStatus]);

        // 4. Lưu vào bảng Lịch sử (order_status_history)
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'note' => 'Cập nhật trạng thái sang: ' . $this->getStatusName($newStatus),
            'created_by' => auth()->id() ?? 1
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    }

    // Hàm phụ trợ dịch tên trạng thái sang Tiếng Việt để lưu Log
    private function getStatusName($status) {
        $map = [
            'pending_payment' => 'Chờ thanh toán',
            'pending' => 'Chờ xử lý',
            'preparing' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Khách đã nhận hàng',
            'cancelled' => 'Hủy đơn hàng'
        ];
        return $map[$status] ?? $status;
    }
}