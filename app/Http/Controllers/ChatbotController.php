<?php

namespace App\Http\Controllers;

use App\Models\ChatbotLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable; 

class ChatbotController extends Controller
{
    public function askGemini(Request $request)
    {
        // BỌC TOÀN BỘ CONTROLLER BẰNG TRY-CATCH ĐỂ KHÔNG BAO GIỜ BỊ SẬP SERVER
        try {
            $request->validate([
                'message' => 'required|string'
            ]);

            $userMessage = $request->message;
            $userId = auth()->check() ? auth()->id() : null;

            if (!session()->has('bot_session_id')) {
                session(['bot_session_id' => 'bot_' . Str::random(10)]);
            }
            $sessionId = session('bot_session_id');
            
            $aiSetting = \App\Models\AiSetting::first();
            
            if ($aiSetting && $aiSetting->is_active == 0) {
                return response()->json([
                    'success' => true,
                    'bot_response' => 'Hiện tại trợ lý AI đang được bảo trì. Quý khách vui lòng để lại tin nhắn cho nhân viên nhé!'
                ]);
            }
            
            $systemPrompt = $aiSetting ? $aiSetting->system_prompt : "Bạn là trợ lý AI.";
            
            // ========================================================
            // BƯỚC NÂNG CẤP: BƠM DỮ LIỆU ĐỘNG TỪ DATABASE CHO AI
            // ========================================================
            $dynamicContext = "";
            
            try {
                // Lấy danh sách Danh Mục
                // (Nếu file Model Category của bạn tên khác, nó sẽ báo lỗi ra màn hình ngay)
                $categories = \App\Models\Category::pluck('name')->toArray();
                $catString = implode(', ', $categories);

                // Lấy danh sách Sản Phẩm
                $products = \App\Models\Product::select('name', 'sell_price')
                                ->where('status', 1) 
                                ->limit(50) 
                                ->get();
                
                $prodString = "";
                foreach($products as $p) {
                    $price = floatval($p->sell_price ?? 0);
                    $prodString .= "- " . $p->name . ": " . number_format($price) . " VNĐ\n";
                }

                $dynamicContext = "
                \n\n--- DỮ LIỆU CỬA HÀNG THỰC TẾ ---
                Các danh mục: {$catString}
                Danh sách sản phẩm hiện có (báo giá theo danh sách này):
                {$prodString}
                -----------------------------------
                ";

            } catch (Throwable $e) {
                // Nếu sai tên cột hoặc sai tên Model, giấu lỗi đi và chèn vào prompt để AI báo cáo
                $dynamicContext = "\n\n[Hệ thống nội bộ đang bị lỗi SQL: " . $e->getMessage() . "]";
            }

            $fullPrompt = $systemPrompt . $dynamicContext . "\n\nCâu hỏi của khách hàng: " . $userMessage;

            // 2. GỌI API GEMINI
            $apiKey = env('GEMINI_API_KEY');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

            // Gọi API, thêm timeout(30) để nếu Google lag quá 30 giây thì tự ngắt
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $fullPrompt]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                $botResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, hệ thống AI đang bận.';
                
                $log = ChatbotLog::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'user_message' => $userMessage,
                    'bot_response' => $botResponse,
                    'intent_name' => 'Tu_Van_Chung',
                    'confidence_score' => 0.99
                ]);

                return response()->json([
                    'success' => true,
                    'bot_response' => $botResponse,
                    'session_id' => $sessionId
                ]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Lỗi từ Google: ' . $response->body()
                ]);
            }

        } catch (Throwable $e) {
            // ĐÂY LÀ CHÌA KHÓA: Trả về lỗi bằng JSON để JS đọc được và in ra bong bóng
            return response()->json([
                'success' => false, 
                'message' => 'Bắt được lỗi Backend: ' . $e->getMessage() . ' (tại dòng ' . $e->getLine() . ')'
            ]);
        }
    }
}