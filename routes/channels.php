<?php

use Illuminate\Support\Facades\Broadcast;

// Định nghĩa quyền truy cập cho kênh chat chung
Broadcast::channel('chat-channel', function ($user) {
    return auth()->check(); // Cho phép tất cả người dùng đã đăng nhập
});

// Nếu sau này bạn làm chat 1-1 (Private Channel)
Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId;
});