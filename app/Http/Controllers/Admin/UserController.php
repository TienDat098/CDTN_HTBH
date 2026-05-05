<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{

    public function index()
    {
        // người mới đăng ký lên đầu, phân trang 10 người
        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    // Cập nhật quyền 
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,staff,customer',
            'status' => 'required|boolean'
        ]);

        // Tránh trường hợp Admin tự hạ quyền của chính mình
        if ($user->id == auth()->id() && $request->role != 'admin') {
            return redirect()->back()->with('error', 'Bạn không thể tự tước quyền Admin của chính mình!');
        }
        // Tránh Admin tự khóa tài khoản của chính mình
        if ($user->id == auth()->id() && $request->status == 0) {
            return redirect()->back()->with('error', 'Bạn không thể tự khóa tài khoản của mình!');
        }
        $user->update([
            'role' => $request->role, 
            'status' => $request->status
            ]);

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật thông tin cho ' . $user->name);
    }

    // Xóa tài khoản (Khóa)
    // Xóa tài khoản
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể tự xóa tài khoản của mình!');
        }

        try {
            $user->delete();
            return redirect()->back()->with('success', 'Đã xóa người dùng thành công!');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Bắt lỗi 23000 (Lỗi ràng buộc khóa ngoại của MySQL)
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'Không thể xóa! Người dùng này đã có lịch sử đơn hàng hoặc giao dịch. Vui lòng bấm Sửa và dùng chức năng "Khóa tài khoản" thay vì xóa.');
            }
            
            // Bắt các lỗi Database khác nếu có
            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
}
