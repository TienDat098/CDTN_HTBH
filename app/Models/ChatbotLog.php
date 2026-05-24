<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotLog extends Model
{
    use HasFactory;

    protected $table = 'chatbot_logs';

    protected $fillable = [
        'user_id',
        'session_id',
        'user_message',
        'bot_response',
        'intent_name',
        'confidence_score'
    ];

    // Liên kết với bảng users nếu khách đã đăng nhập
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}