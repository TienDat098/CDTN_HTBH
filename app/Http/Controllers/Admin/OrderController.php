<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

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
        $order = Order::with(['items.product', 'staff', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
}
