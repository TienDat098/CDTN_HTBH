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

    // Cập nhật quyền 
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,staff,customer'
        ]);

        // Tránh trường hợp Admin tự hạ quyền của chính mình
        if ($user->id == auth()->id() && $request->role != 'admin') {
            return redirect()->back()->with('error', 'Bạn không thể tự tước quyền Admin của chính mình!');
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'Đã cập nhật vai trò cho ' . $user->name);
    }

    // Xóa tài khoản (Khóa)
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể tự xóa tài khoản của mình!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Đã xóa người dùng thành công!');
    }
}
