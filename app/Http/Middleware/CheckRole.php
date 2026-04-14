<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Kiểm tra xem đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Lấy chức vụ của người đang đăng nhập
        $userRole = Auth::user()->role;

        // 3. Kiểm tra xem chức vụ đó có nằm trong danh sách được phép vào không
        if (in_array($userRole, $roles)) {
            return $next($request); // Cho phép đi tiếp
        }

        // 4. Nếu không có quyền -> Báo lỗi 403 (Cấm truy cập) hoặc đá về trang chủ
        abort(403, 'Bạn không có quyền truy cập vào khu vực này!');
    }
}