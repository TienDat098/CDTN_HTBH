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
    public function index()
    {
        // Lấy danh sách đơn hàng kèm thông tin nhân viên/khách hàng, xếp mới nhất lên đầu
        $orders = Order::with(['staff', 'user'])->orderBy('id', 'desc')->paginate(10);
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

        // 1. NGHIỆP VỤ HỦY ĐƠN VÀ HOÀN KHO
        if ($newStatus == 'cancelled') {
            // Sử dụng $order->items theo đúng relation của bạn
            foreach ($order->items as $item) {
                // Cộng lại số lượng vào kho
                ProductStock::where('product_id', $item->product_id)
                    ->where('variant_id', $item->variant_id) // Nếu không dùng biến thể, có thể bỏ dòng này
                    ->increment('quantity', $item->quantity);

                // Ghi Log hoàn kho
                InventoryLog::create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'reference_id' => $order->id,
                    'quantity' => $item->quantity,
                    'type' => 'import',
                    'note' => 'Hoàn kho do Hủy đơn hàng ' . $order->order_code,
                    'created_by' => auth()->id() ?? 1
                ]);
            }
        }

        // 2. NGHIỆP VỤ GIAO THÀNH CÔNG -> TỰ ĐỘNG ĐÃ THANH TOÁN
        if ($newStatus == 'completed') {
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method' => 'cod',
                    'amount' => $order->final_total, // Đảm bảo bảng orders của bạn có cột final_total
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
            'pending' => 'Chờ xử lý',
            'preparing' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Khách đã nhận hàng',
            'cancelled' => 'Hủy đơn hàng'
        ];
        return $map[$status] ?? $status;
    }
}
