<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    //  Xem danh sách mã giảm giá
    public function index()
    {
        $promotions = Promotion::orderBy('id', 'desc')->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    //  Hiển thị Form tạo mã mới
    public function create()
    {
        return view('admin.promotions.create');
    }

    //  Xử lý lưu mã vào Database
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code',
            'quantity' => 'required|integer|min:1',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'code.unique' => 'Mã giảm giá này đã tồn tại!',
            'end_date.after' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.'
        ]);

        Promotion::create([
            'code' => strtoupper($request->code), 
            'quantity' => $request->quantity,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_value' => $request->min_order_value ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 1
        ]);

        return redirect()->route('admin.promotions.index')->with('success', 'Tạo mã giảm giá thành công!');
    }

    // Hiển thị Form cập nhật mã
    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    // Xử lý cập nhật vào Database
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'quantity' => 'required|integer|min:1',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'code.unique' => 'Mã giảm giá này đã tồn tại!',
            'end_date.after' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.'
        ]);

        $promotion->update([
            'code' => strtoupper($request->code),
            'quantity' => $request->quantity,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_value' => $request->min_order_value ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->has('status') ? 1 : 0 
        ]);

        return redirect()->route('admin.promotions.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }
    //  Xóa mã giảm giá
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Đã xóa mã giảm giá!');
    }
}
