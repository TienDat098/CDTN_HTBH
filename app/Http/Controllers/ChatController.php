<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ChatController extends Controller
{
    /**
     * Hàm hiển thị giao diện quản lý tin nhắn cho Admin
     */
    public function adminChat()
    {
        // Lấy danh sách các cuộc hội thoại duy nhất (gộp theo conversation_id) để hiển thị ở cột bên trái
        $conversations = Message::with('sender')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('conversation_id');

        return view('admin.chat.index', compact('conversations'));
    }
    /**
     * Hàm 1: Lấy lịch sử tin nhắn dựa trên Mã Phòng Chat (Conversation ID)
     */

    public function fetchMessages($conversation_id)
    {
        // Lấy toàn bộ tin nhắn thuộc về mã phòng này (Dành chung cho cả Khách và Admin)
        $messages = Message::with('sender')
            ->where('conversation_id', $conversation_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
{
    $request->validate([
        'message' => 'required|string|max:1000',
        'conversation_id' => 'nullable|string',
    ]);

    $sender_id = auth()->check() ? auth()->id() : null;

    // ADMIN TRẢ LỜI KHÁCH
    if ($request->filled('conversation_id') && auth()->check()) {
        $conversation_id = $request->conversation_id;

        $message = Message::create([
            'conversation_id' => $conversation_id,
            'session_id'      => null,
            'sender_id'       => $sender_id,
            'receiver_id'     => null,
            'message'         => $request->message,
            'is_read'         => 0,
        ]);

        $message->load('sender');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'message' => $message
        ]);
    }

    // KHÁCH VÃNG LAI GỬI TIN
    if (!session()->has('guest_chat_id')) {
        session(['guest_chat_id' => 'guest_' . Str::random(10)]);
    }

    $session_id = session('guest_chat_id');
    $conversation_id = $sender_id ? (string) $sender_id : $session_id;

    $message = Message::create([
        'conversation_id' => $conversation_id,
        'session_id'      => $session_id,
        'sender_id'       => $sender_id,
        'receiver_id'     => 1,
        'message'         => $request->message,
        'is_read'         => 0,
    ]);

    $message->load('sender');

    broadcast(new MessageSent($message))->toOthers();

    return response()->json([
        'message' => $message
    ]);
}
}