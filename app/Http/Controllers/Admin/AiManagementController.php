<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use App\Models\ChatbotLog;
use Illuminate\Http\Request;

class AiManagementController extends Controller
{
    // 1. Xem lịch sử hội thoại AI
    public function history()
    {
        // Lấy lịch sử mới nhất
        $logs = ChatbotLog::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.ai.history', compact('logs'));
    }

    // 2. Giao diện Cấu hình/Huấn luyện AI
    public function settings()
    {
        // Lấy cấu hình đầu tiên (hoặc tạo mới nếu chưa có)
        $setting = AiSetting::firstOrCreate(
            ['id' => 1],
            ['system_prompt' => "Bạn là trợ lý ảo của WebTapHoa. Hãy trả lời ngắn gọn, thân thiện.", 'is_active' => 1]
        );
        
        return view('admin.ai.settings', compact('setting'));
    }

    // 3. Lưu cấu hình khi Admin bấm cập nhật
    public function updateSettings(Request $request)
    {
        $request->validate([
            'system_prompt' => 'required|string'
        ]);

        $setting = AiSetting::first();
        $setting->update([
            'system_prompt' => $request->system_prompt,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);

        return back()->with('success', 'Đã cập nhật kiến thức cho AI thành công!');
    }
}